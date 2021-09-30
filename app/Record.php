<?php

namespace App;

use App\Contracts\Importable;
use Illuminate\Database\Eloquent\Model;

class Record extends Model implements Importable
{
    protected $guarded = [];

    public $timestamps = false;

    protected $dates = ['date', 'imported_at'];

    protected $perPage = 10;

    /**
     * Доступные расширения
     *
     * @return array
     */
    public static function getAvailableExtensions(): array
    {
        return ['xls', 'xlsx'];
    }

    /**
     * Допустимый максимальный размер файла в килобайтах
     *
     * @return int
     */
    public static function getMaxFileSize(): int
    {
        return 10240; // 10 MB
    }
}
