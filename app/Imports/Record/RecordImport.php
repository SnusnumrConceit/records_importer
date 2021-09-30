<?php

namespace App\Imports\Record;

use App\Record;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;

use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Events\BeforeImport;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;

class RecordImport implements ToModel, WithBatchInserts, WithChunkReading, WithEvents, SkipsEmptyRows,
    WithStartRow, WithHeadings, ShouldQueue, WithValidation
{
    use Importable, RemembersChunkOffset;

    private $uuid, $processed = 0, $total;

    /**
     * Конструктор импорта
     *
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Признак, что импорт происходит через модель
     *
     * @param array $row
     * @return \App\Record
     */
    public function model(array $row)
    {
        $data = [];

        foreach ($this->headings() as $key => $heading) {
            $data[$heading] = $row[$key];
        }

        $record = new Record($data);

        $this->refreshProcessed();

        return $record;
    }

    /**
     * Обновить прогресс импорта
     */
    protected function refreshProcessed()
    {
        ++$this->processed;

        if ($this->processed === $this->chunkSize()) {
            cache()->tags('records_import')->increment($this->getProcessedCacheKey(), $this->processed);

            $this->refreshCachedImportProgress(function () {
                return [
                    'processed' => (int) cache()->tags('records_import')->get($this->getProcessedCacheKey()),
                    'total'     => $this->total,
                ];
            });
        }
    }

    /**
     * Размер чанка на вставку
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Размер чанка на чтение
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Регистрация хуков событий
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                $total = $event->getReader()->getTotalRows();

                $this->total = (int) $total[key($total)]; // ['worksheet' => 'totalRows']

                $this->refreshCachedImportProgress(function () {
                    return [
                        'processed' => 0,
                        'total'     => $this->total,
                    ];
                });
            },

            AfterImport::class => function(AfterImport $event) {
                $this->refreshCachedImportProgress(function() {
                    return [
                        'processed' => cache()->tags('records_import')->get($this->getProcessedCacheKey()),
                        'total'     => $this->total,
                        'finished'  => strtotime('now'),
                    ];
                });

                cache()->tags('records_import')->forget($this->getProcessedCacheKey());
            },

            ImportFailed::class => function(ImportFailed $event) {
                $this->refreshCachedImportProgress(function () {
                    return [
                        'error'   => 'import_has_error', // храним ключ перевода для мультиязычности
                    ];
                });
            }
        ];
    }

    /**
     * Обновление кэша прогресса об импорте
     *
     * @param \Closure $callback
     */
    private function refreshCachedImportProgress(\Closure $callback)
    {
        Cache::tags('records_import')->forget($this->uuid);
        Cache::tags('records_import')->forever($this->uuid, $callback());
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '0' => [
                'required', 'string', 'max:255',
            ],
            '1' => [
                'required', 'int',
            ]
        ];
    }

    /**
     * Подготовка данных на валидацию
     *
     * @param $data
     * @param $index
     * @return array
     */
    public function prepareForValidation($data, $index)
    {
        $data[0] = trim($data[0]);
        $data[1] = $this->getPreparedDate($data[1]);

        return $data;
    }

    /**
     * Получить данные о дате в числовом формате
     *
     * @param $date
     * @return int|string
     */
    protected function getPreparedDate($date)
    {
        if (($result = strtotime($date)) === false) return '';

        return $result;
    }

    /**
     * Стартовый номер строки импорта
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Заголовки
     *
     * @return array
     */
    public function headings(): array
    {
        return ['name', 'date'];
    }

    /**
     * Получить ключ кэша о процессе импорта
     *
     * @return string
     */
    private function getProcessedCacheKey() : string
    {
        return sprintf('%s_processed', $this->uuid);
    }
}
