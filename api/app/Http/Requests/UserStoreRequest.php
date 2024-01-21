<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

/**
 * @OA\Schema(
 *     schema="UserStoreRequest",
 *     title="User Store Request",
 *      required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="Expense name"),
 *     @OA\Property(property="email", type="string", example="email@email.com"),
 *     @OA\Property(property="password", type="string", example="exemple9299")
 * )
 */

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'email.required' => 'O campo email é obrigatório.',
            'email.string' => 'O email deve ser uma string.',
            'email.email' => 'O email deve ser um endereço de email válido.',
            'email.unique' => 'Este email já está sendo utilizado.',
            'password.required' => 'O campo senha é obrigatório.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errorsArray = $validator->errors()->toArray();

        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'      => $this->convertArrayToString($errorsArray)

        ]));

    }

    protected function convertArrayToString($array)
    {
        $result = '';

        foreach ($array as $key => $messages) {
            $result .= implode('<br/>', $messages) . '<br/>';
        }

        $result = rtrim($result, '<br/>');


        return $result;
    }
}
