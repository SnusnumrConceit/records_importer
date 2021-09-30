<?php

namespace App\Contracts;

interface Importable
{
    /**
     * Доступные расширения
     *
     * @return array
     */
    public static function getAvailableExtensions() : array;

    /**
     * Допустимый максимальный размер файла
     *
     * @return int
     */
    public static function getMaxFileSize() : int;
}
