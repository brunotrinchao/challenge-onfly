<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="ExpenseStoreRequest",
 *     title="Expense Store Request",
 *     required={"description", "date", "amount"},
 *     @OA\Property(property="description", type="string", maxLength=191, example="Expense description"),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-19"),
 *     @OA\Property(property="amount", type="number", minimum=0, example=9.99)
 * )
 */
class ExpenseStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
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

    protected function prepareForValidation()
    {
        $auth = Auth::user();
        $this->merge([
            'user_id' => $auth->id
        ]);
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

        throw new HttpResponseException(response()->json([
            'success'   => false,
            'error'      => $validator->errors()
        ]));

    }
}