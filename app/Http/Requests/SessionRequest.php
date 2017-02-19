<?php

namespace App\Http\Requests;

use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return User::authenticated()->hasRole('teacher');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'number' => 'required|numeric|min:0'
        ];
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return void
     */
    public function response(array $errors){
        throw new StoreResourceFailedException(
            'The given data failed to pass validation.',
            $errors
        );
    }
}
