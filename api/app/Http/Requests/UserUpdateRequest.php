<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * @OA\Schema(
 *     schema="UserUpdateRequest",
 *     title="User Update Request",
 *     required={"name", "password"},
 *     @OA\Property(property="name", type="string", example="Expense name"),
 *     @OA\Property(property="password", type="string", example="exemple9299")
 * )
 */
class UserUpdateRequest extends FormRequest
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::user();
    }

    public function authorize()
    {
        $id = $this->route('user');
        $user = User::find($id);
        return  $user &&  $this->auth->id != $user->user_id;

    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Você não está autorizado a acessar esta despesa.');
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->auth->id
        ]);
    }

    public function rules()
    {

        return [
                    'name' => 'required|string',
                    'password' => 'required'
                ];

    }

    public function messages(): array
    {


        return [
                    'name.required' => 'O campo nome é obrigatório.',
                    'name.string' => 'O nome deve ser uma string.',
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
