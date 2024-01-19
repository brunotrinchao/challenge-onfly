<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $request->validated();
            $credentials = $request->only(['email', 'password']);
            $user = User::whereEmail($credentials['email'])->first();

            if(!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json(['success'   => false,'message' => 'Email ou senha não conferem.'], Response::HTTP_UNAUTHORIZED);
            }

            $user->tokens()->delete();
            $token = $user->createToken('api');
            return response()->json(['token' => $token->plainTextToken], Response::HTTP_ACCEPTED);
        } catch (ValidationException $e) {
            return response()->json(['success'   => false,'message' => $e->errors()], Response::HTTP_UNAUTHORIZED);

        } catch (\Exception $e) {
            return response()->json(['success'   => false,'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(RegisterRequest $request)
    {

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('novotoken')->plainTextToken;

            return response()->json(['token' => $token], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['success'   => false,'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
