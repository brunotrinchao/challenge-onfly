<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API de autenticação"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Efetua login do usuário",
     *      description="Efetua login do usuário com as credenciais fornecidas",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="token", type="string", example="api_token_here")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Credenciais inválidas",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Email ou senha não conferem.")
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
    public function login(LoginRequest $request)
    {
        try {
            $request->validated();
            $credentials = $request->only(['email', 'password']);
            $user = User::whereEmail($credentials['email'])->first();

            if(!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json(['success'   => false,'message' => 'Email ou senha não conferem.'], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('api');
            return response()->json(['success'   => true, 'token' => $token->plainTextToken, 'user' => $user], Response::HTTP_ACCEPTED);
        } catch (ValidationException $e) {
            return response()->json(['success'   => false,'message' => $e->errors()], Response::HTTP_UNAUTHORIZED);

        } catch (\Exception $e) {
            return response()->json(['success'   => false,'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="Registra um novo usuário",
     *      description="Registra um novo usuário com as informações fornecidas",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="token", type="string", example="api_token_here")
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
    public function signup(RegisterRequest $request)
    {

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('novotoken')->plainTextToken;

            return response()->json(['success'   => true, 'token' => $token, 'user' => $user], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['success'   => false,'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
