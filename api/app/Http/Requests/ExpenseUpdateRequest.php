<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * @OA\Schema(
 *     schema="ExpenseUpdateRequest",
 *     title="Expense Update Request",
 *     required={"description", "date", "amount"},
 *     @OA\Property(property="description", type="string", maxLength=191, example="Expense description"),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-19"),
 *     @OA\Property(property="amount", type="number", minimum=0, example=9.99)
 * )
 */
class ExpenseUpdateRequest extends FormRequest
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::user();
    }

    public function authorize()
    {
        $id = $this->route('expense');
        $expense = Expense::find($id);

        return $expense &&  $this->auth->id == $expense->user_id;

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
            'date' => [
              'required',
              'date_format:Y-m-d',
              'before_or_equal:today',
            ],
            'amount' => [
                'required',
                'between:0,999999.99',
                'numeric',
            ],
            'description' => [
                'required',
                'max:191'
            ],
            'user_id' => [
                'required'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'date.date_format:Y-m-d' => 'Data com formato inválido.',
            'date.before_or_equal' => 'Data não pode ser maior que a data do dia.',
            'date.required' => 'Data é obrigatório.',
            'amount.required' => 'Valor é obrigatório.',
            'amount.between' => 'O valor inválido.',
            'amount.numeric' => 'O valor deve ser um número decimal.',
            'description.max' => 'Descrição tem até 191 caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'user_id.required' => 'Usuário de sessão é orbigatório.'
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
