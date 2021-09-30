<?php

namespace App\Http\Requests\Record;

use App\Http\Requests\BaseRequest;

class IndexRecord extends BaseRequest
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'keyword'    => 'nullable|string|max:255',
            'date_start' => 'nullable|date_format:Y-m-d',
            'date_end'   => 'nullable|date_format:Y-m-d|before:tomorrow',
        ];

        if ($this->request->get('date_end')) {
            $rules['date_start'] .= '|before:date_end';
        }

        if ($this->request->get('date_start')) {
            $rules['date_end'] .= '|after:date_start';
        }

        return $rules;
    }

    /**
     * Атрибуты валидации
     *
     * @return array
     */
    public function attributes()
    {
        return [
          'keyword'    => __('records.attributes.keyword'),
          'date_start' => __('records.filters.date_start'),
          'date_end'   => __('records.filters.date_end'),
        ];
    }
}
