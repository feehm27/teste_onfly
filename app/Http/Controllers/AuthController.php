<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *  path="/register",
     *  summary="Gera um token para autenticação de usuário",
     *  tags={"Autenticação"},
     *  @OA\RequestBody(
     *      description="Dados do usuário para criação e autenticação",
     *      required=true,
     *      @OA\JsonContent(
     *          required={"name", "email", "password"},
     *          @OA\Property(property="name", type="string", example="João da Silva"),
     *          @OA\Property(property="email", type="string", example="usuario@example.com"),
     *          @OA\Property(property="password", type="string", example="senha_do_usuario"),
     *          @OA\Property(property="password_confirmation", type="string", example="senha_do_usuario")
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Usuário registrado e token gerado com sucesso.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="user", type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="João da Silva"),
     *              @OA\Property(property="email", type="string", example="usuario@example.com")
     *          ),
     *          @OA\Property(property="token", type="string", example="token_gerado_aqui")
     *      )
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Campos inválidos.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="message", type="string", example="O campo email é obrigatório.")
     *      )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Validação de dados falhou.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="message", type="string", example="O campo email é obrigatório.")
     *      )
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Erro inesperado ao criar o usuário e gerar o token.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="error", type="string", example="Erro inesperado ao criar o usuário.")
     *      )
     *  ),
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user, 'token' => $user->createToken('YourAppName')->plainTextToken]);
    }

    /**
     * @OA\Post(
     *  path="/login",
     *  summary="Realiza o login do usuário e gera um token de autenticação",
     *  tags={"Autenticação"},
     *  @OA\RequestBody(
     *      description="Credenciais do usuário para autenticação",
     *      required=true,
     *      @OA\JsonContent(
     *          required={"email", "password"},
     *          @OA\Property(property="email", type="string", example="usuario@example.com"),
     *          @OA\Property(property="password", type="string", example="senha_do_usuario")
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Token gerado com sucesso.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="token", type="string", example="token_gerado_aqui")
     *      )
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Credenciais inválidas.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="error", type="string", example="Unauthorized")
     *      )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Campos inválidos.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="message", type="string", example="O campo email é obrigatório.")
     *      )
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Erro inesperado ao gerar o token.",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="error", type="string", example="Erro inesperado ao gerar o token.")
     *      )
     *  ),
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            return response()->json([
                'token' => $user->createToken('YourAppName')->plainTextToken,
                'user' => $user
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['As credenciais informadas não são válidas.'],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Desconectado com sucesso.']);
    }
}
