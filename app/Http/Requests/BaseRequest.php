<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Определить наличие доступа на совершение запроса у пользователя
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
