<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => [
              'required',
              'email'
            ],
            'password' => [
                'required'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'E-mail inválido.',
            'email.required' => 'E-mail é obrigatório.',
            'password.required' => 'Senha é obrigatório.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

            'success'   => false,
            'message'      => $validator->errors()

        ]));

    }
}
