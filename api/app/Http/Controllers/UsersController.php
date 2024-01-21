<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API para gerenciamento de usuários"
 * )
 */

class UsersController extends Controller
{
    /**
     * Get Detail
     * @OA\Get(
     *      path="/api/users",
     *      operationId="getUserList",
     *      tags={"Users"},
     *      security={{"sanctum":{}}},
     *      summary="Lista todos os usuários",
     *      description="Retorna uma lista paginada de todos os usuários",
     *      @OA\Response(
     *          response=200,
     *          description="Sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                 property="data",
     *                 type="array",
     *                   @OA\Items(ref="#/components/schemas/UserResource")
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
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', User::class);

            $user = Auth::user();
            $query  = User::where('id', '!=', $user->id);
            if ($request->has('sort')) {

                $sortField = $request->input('sort');
                $order = $request->input('order', 'asc');
                $query->orderBy($sortField, $order);

            }
            $users = $query ->paginate(10);

            return UserResource::collection($users);
        } catch(\Exception $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/user",
     *      operationId="createUser",
     *      tags={"Users"},
     *      security={{"sanctum":{}}},
     *      summary="Cria um novo usuário",
     *      description="Cria um novo usuário com base nos dados fornecidos",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sucesso",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
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
    public function store(RegisterRequest $request)
    {
        try {
            $auth = Auth::user();
            $authId = $auth->id;
            $this->authorize('create', User::class, $authId);

            $validatedValues = $request->validated();

            $user = User::create([
               'name' => $request->name,
               'email' => $request->email,
               'password' => Hash::make($request->password)
           ]);

            return new UserResource($user);
        } catch (ValidationException $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
 * @OA\Get(
 *      path="/api/user/{id}",
 *      operationId="getUserById",
 *      tags={"Users"},
 *       security={{"sanctum":{}}},
 *      summary="Obtém detalhes de um usuário específica",
 *      description="Retorna detalhes de um usuário com base no ID fornecido",
 *      @OA\Parameter(
 *          name="id",
 *          description="ID do usuário",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Sucesso",
 *          @OA\JsonContent(ref="#/components/schemas/UserResource")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Usuário não encontrado",
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

            $expense = User::firstOrFail($id);

            $this->authorize('view', $expense);

            return new UserResource($expense);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }

    /**
 * @OA\Put(
 *      path="/api/user/{id}",
 *      operationId="updateUser",
 *      tags={"Users"},
 *      security={{"sanctum":{}}},
 *      summary="Atualiza um usuário existente",
 *      description="Atualiza um usuário existente com base no ID fornecido e nos dados fornecidos",
 *      @OA\Parameter(
 *          name="id",
 *          description="ID do usuário",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Sucesso",
 *          @OA\JsonContent(ref="#/components/schemas/UserResource")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Usuário não encontrada",
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
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $expense = User::where('id', $id)->firstOrFail();
            $this->authorize('update', $expense);

            $validatedValues = $request->validated();
            $validatedValues['password'] = Hash::make($validatedValues['password']);

            $expense->update($validatedValues);
            return new UserResource($expense);
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
 *      path="/api/User/{id}",
 *      operationId="deleteUser",
 *      tags={"Users"},
 *      security={{"sanctum": {}}},
 *      summary="Exclui um usuário",
 *      description="Exclui um usuário com base no ID fornecido",
 *      @OA\Parameter(
 *          name="id",
 *          description="ID do usuário",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Sucesso",
 *          @OA\JsonContent(ref="#/components/schemas/UserResource")
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
            $expense = User::findOrFail($id);

            $auth = Auth::user();
            $authId = $auth->id;
            $this->authorize('create', User::class, $authId);

            $expense->delete();
            return new UserResource($expense);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success'   => false, 'message' => 'Despesa não encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['success'   => false, 'message' => 'Erro interno do servidor'], 500);
        }
    }
}
