<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Notifications\ExpenseNewNotification;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * @OA\Tag(
 *     name="Expenses",
 *     description="API para gerenciamento de despesas"
 * )
 */
class ExpenseController extends Controller
{
    /**
     * Get Detail
     * @OA\Get(
     *      path="/api/expenses",
     *      operationId="getExpensesList",
     *      tags={"Expenses"},
     *      summary="Lista todas as despesas do usuário autenticado",
     *      description="Retorna uma lista paginada de todas as despesas do usuário autenticado",
     *      @OA\Response(
     *          response=200,
     *          description="Sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                 property="data",
     *                 type="array",
     *                   @OA\Items(ref="#/components/schemas/ExpenseResource")
     *              ),
     *              @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string"),
     *                 @OA\Property(property="next", type="string")
     *             ),
     *               @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string"),
     *                         @OA\Property(property="label", type="string"),
     *                         @OA\Property(property="active", type="boolean")
     *                     )
     *                 ),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Mensagem de erro")
     *          )
     *      )
     * )
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', Expense::class);

            $user = Auth::user();
            $expenses = Expense::where('user_id', $user->id)->paginate(5);
            return ExpenseResource::collection($expenses);
        } catch(\Exception $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/expenses",
     *      operationId="createExpense",
     *      tags={"Expenses"},
     *      security={{"sanctum":{}}},
     *      summary="Cria uma nova despesa",
     *      description="Cria uma nova despesa com base nos dados fornecidos",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ExpenseStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sucesso",
     *          @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erro de validação",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Mensagem de erro de validação")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Mensagem de erro")
     *          )
     *      )
     * )
     */
    public function store(ExpenseStoreRequest $request)
    {
        try {
            $this->authorize('create', Expense::class);

            $validatedValues = $request->validated();

            $expense = Expense::create($validatedValues);

            $expense->user->notify(new ExpenseNewNotification($expense));

            return new ExpenseResource($expense);
        } catch (ValidationException $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
 * @OA\Get(
 *      path="/api/expenses/{id}",
 *      operationId="getExpenseById",
 *      tags={"Expenses"},
 *       security={{"sanctum":{}}},
 *      summary="Obtém detalhes de uma despesa específica",
 *      description="Retorna detalhes de uma despesa com base no ID fornecido",
 *      @OA\Parameter(
 *          name="id",
 *          description="ID da despesa",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Sucesso",
 *          @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Despesa não encontrada",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Despesa não encontrada")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Erro interno do servidor",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Mensagem de erro")
 *          )
 *      )
 * )
 */
    public function show(Request $request, $id)
    {
        try {

            $expense = Expense::firstOrFail($id);

            $this->authorize('view', $expense);

            return new ExpenseResource($expense);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }

    /**
 * @OA\Put(
 *      path="/api/expenses/{id}",
 *      operationId="updateExpense",
 *      tags={"Expenses"},
 *      summary="Atualiza uma despesa existente",
 *      description="Atualiza uma despesa existente com base no ID fornecido e nos dados fornecidos",
 *      @OA\Parameter(
 *          name="id",
 *          description="ID da despesa",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/ExpenseUpdateRequest")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Sucesso",
 *          @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Despesa não encontrada",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Despesa não encontrada")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Erro de validação",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Mensagem de erro de validação")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Erro interno do servidor",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Mensagem de erro")
 *          )
 *      )
 * )
 */
    public function update(ExpenseUpdateRequest $request, $id)
    {
        try {
            $expense = Expense::where('id', $id)->firstOrFail();
            $this->authorize('update', $expense);

            $validatedValues = $request->validated();

            $expense->update($validatedValues);
            return new ExpenseResource($expense);
        } catch (AuthorizationException $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 404);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }


    /**
 * @OA\Delete(
 *      path="/api/expenses/{id}",
 *      operationId="deleteExpense",
 *      tags={"Expenses"},
 *      security={{"sanctum": {}}},
 *      summary="Exclui uma despesa",
 *      description="Exclui uma despesa com base no ID fornecido",
 *      @OA\Parameter(
 *          name="id",
 *          description="ID da despesa",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Sucesso",
 *          @OA\JsonContent(ref="#/components/schemas/ExpenseResource")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Despesa não encontrada",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Despesa não encontrada")
 *          )
 *      ),
 *  
 *  * )
 */
    public function destroy(Request $request, $id)
    {
        try {
            $expense = Expense::findOrFail($id);

            // Autoriza a exclusão da despesa
            $this->authorize('delete', $expense);

            $expense->delete();
            return new ExpenseResource($expense);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }
}
