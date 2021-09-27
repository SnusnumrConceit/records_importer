<?php

namespace App\Http\Requests\Record;

use App\Record;
use App\Http\Requests\BaseRequest;

class ImportRecord extends BaseRequest
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => sprintf('required|file|max:%s|mimes:%s',
                Record::getMaxFileSize(), $this->getFileAvailableExtensions()
            )
        ];
    }

    /**
     * Атрибуты валидации
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'file' => __('records.import.file'),
        ];
    }

    /**
     * Перечень допустимых расширений для файла
     *
     * @return string
     */
    private function getFileAvailableExtensions() : string
    {
        return implode(',', Record::getAvailableExtensions());
    }
}
