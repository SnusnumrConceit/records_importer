<?php

namespace App\Translators;

class ImportStatusTranslator
{
    /**
     * Перевести данные
     *
     * @param array $data
     * @return string
     */
    public static function translate(array $data) : string
    {
        if (array_key_exists('finished', $data)) {
            $data['finished'] = date(__('formats.datetime_H:i'), $data['finished']);

            return __('records.import.finished', $data);
        } elseif (array_key_exists('error', $data)) {
            return __('records.import.error', $data);
        }

        return __('records.import.processing', $data);
    }
}
