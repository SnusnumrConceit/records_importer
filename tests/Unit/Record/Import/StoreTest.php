<?php

namespace Tests\Unit\Record\Import;

use App\Record;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Imports\Record\RecordImport;
use Maatwebsite\Excel\Facades\Excel;

class StoreTest extends TestCase
{
    /**
     * Маршрут импорта
     *
     * @return string
     */
    protected function getImportRoute() : string
    {
        return route('records.import.store');
    }

    /**
     * Маршрут формы импорта
     *
     * @return string
     */
    protected function getIndexRoute() : string
    {
        return route('records.import.index');
    }

    /**
     * Проверки неудачного запроса
     *
     * @param \Illuminate\Testing\TestResponse $response
     */
    protected function assertFailed($response)
    {
        $response->assertRedirect($this->getIndexRoute());
        $response->assertSessionHasErrors('file');
    }

    /**
     * Тест, что любой может импортировать records с правильным файлом
     */
    public function testAnyoneCanImportRecords()
    {
        Excel::fake();

        foreach (Record::getAvailableExtensions() as $extension) {
            $file = UploadedFile::fake()->create(sprintf('file.%s', $extension), 5120);

            $response = $this->from($this->getIndexRoute())
                ->post($this->getImportRoute(), ['file' => $file]);

            Excel::assertQueued($file->getFilename(), function (RecordImport $import) {
                return true;
            });

            $response->assertRedirect(route('records.index'));
            $response->assertSessionHas('success', __('records.messages.importing'));
        }
    }

    /**
     * Тест, что любой НЕ может импортировать records, если файл отсутствует
     */
    public function testAnyoneCannotImportRecordsWhenFileNotExists()
    {
        $response = $this->from($this->getIndexRoute())
            ->post($this->getImportRoute(), ['file' => '']);

        $this->assertFailed($response);
    }

    /**
     * Тест, что любой НЕ может импортировать records, если в качестве файла отправлен не файл
     */
    public function testAnyoneCannotImportRecordsWhenFileIsNotAFile()
    {
        $response = $this->from($this->getIndexRoute())
            ->post($this->getImportRoute(), ['file' => 'invalid data']);

        $this->assertFailed($response);
    }

    /**
     * Тест, что любой НЕ может импортировать records, если размер файла превышает допустимый лимит
     */
    public function testAnyoneCannotImportRecordsWhenMaxFileSizeIsInvalid()
    {
        $file = UploadedFile::fake()->create('file.xlsx', Record::getMaxFileSize() + 1);

        $response = $this->from($this->getIndexRoute())
            ->post($this->getImportRoute(), ['file' => $file]);

        $this->assertFailed($response);
    }

    /**
     * Тест, что любой НЕ может импортировать records, если файл недопустимого расширения
     */
    public function testAnyoneCannotImportRecordsWhenFileHasInvalidExtension()
    {
        $file = UploadedFile::fake()->create('file.pdf', 5120);

        $response = $this->from($this->getIndexRoute())
            ->post($this->getImportRoute(), ['file' => $file]);

        $this->assertFailed($response);
    }
}
