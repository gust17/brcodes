<?php


namespace App\Http\Controllers;

use App\Models\CodigoPromocional;
use App\Models\Resgate;
use App\Models\User;
use App\Services\CompetidorService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use ApiResponse;

    protected $competidorService;

    public function __construct(CompetidorService $competidorService)
    {
        $this->competidorService = $competidorService;
    }

    /**
     * Cria um novo usuário com o tipo definido pelo administrador
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        // dd($request->all());
        // Validação dos dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:competidor,patrocinador,administrador',
        ]);

        // Criação do usuário
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        // Retornar resposta de sucesso
        return $this->successResponse('Usuário criado com sucesso', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ], 201);
    }

    public function listarCompetidores()
    {
        // Busca todos os usuários com o papel de "competidor"
        $competidores = User::where('role', 'competidor')->get();

        return $this->successResponse('Lista de competidores.', $competidores);
    }

    public function dashboard()
    {
        $competidores = User::where('role', 'competidor')->count();
        $resgates = Resgate::count();
        $codigos = CodigoPromocional::count();

        $dados = [
            "competidores" => $competidores,
            "resgates" => $resgates,
            "codigos" => $codigos
        ];
        return $this->successResponse('Dados do Dashboard.', $dados);
    }

    public function storeCompetidores(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        $dadosDashboard = $this->competidorService->criarCompetidor($validatedData);

        return $this->successResponse('Competidor cadastrado com sucesso.', $dadosDashboard);
    }

    /**
     * Valida os dados do request.
     *
     * @param Request $request
     * @return array
     */
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
    }
}
