<?php

namespace App\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => 'required',
            'password' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
