<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    /**
     * Construtor para injetar o AuthService
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Registrar um novo competidor",
     *     description="Rota para registrar um novo usuário como competidor.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário registrado com sucesso"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="role", type="string", example="competidor")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro de validação"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        // Validação dos dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Usar o service para criar o usuário
        $user = $this->authService->registerUser($validatedData);

        // Retornar a resposta padronizada
        return $this->successResponse('Usuário registrado com sucesso', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ], 201);
    }

    public function login(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Verificar se as credenciais estão corretas
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Credenciais inválidas.', 401);
        }

        // Encontrar o usuário pelo email
        $user = User::where('email', $request['email'])->firstOrFail();

        // Criar um token para o usuário autenticado
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retornar o token e os dados do usuário
        return $this->successResponse('Login realizado com sucesso', [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }
}
