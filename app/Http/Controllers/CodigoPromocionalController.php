<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodigoPromocionalRequest;
use App\Models\CodigoPromocional;
use App\Models\Resgate;
use App\Services\CodigoPromocionalService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;


class CodigoPromocionalController extends Controller
{
    use ApiResponse;

    protected $codigoPromocionalService;

    public function __construct(CodigoPromocionalService $codigoPromocionalService)
    {
        $this->codigoPromocionalService = $codigoPromocionalService;
    }

    /**
     * Cria um novo código promocional.
     *
     * @param CodigoPromocionalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CodigoPromocionalRequest $request)
    {
        try {
            $codigo = $this->codigoPromocionalService->createCodigo($request->validated());
            return $this->successResponse('Código promocional criado com sucesso.', $codigo);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Atualiza um código promocional existente.
     *
     * @param CodigoPromocionalRequest $request
     * @param CodigoPromocional $codigoPromocional
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CodigoPromocionalRequest $request, CodigoPromocional $codigoPromocional)
    {
        try {
            $codigo = $this->codigoPromocionalService->updateCodigo($codigoPromocional, $request->validated());
            return $this->successResponse('Código promocional atualizado com sucesso.', $codigo);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Exclui um código promocional (Soft Delete).
     *
     * @param CodigoPromocional $codigoPromocional
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CodigoPromocional $codigoPromocional)
    {
        try {
            $this->codigoPromocionalService->deleteCodigo($codigoPromocional);
            return $this->successResponse('Código promocional excluído com sucesso.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Lista todos os códigos promocionais.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $codigos = CodigoPromocional::all();
        return $this->successResponse('Lista de códigos promocionais.', $codigos);
    }

    /**
     * Verifica se o código pode ser resgatado.
     *
     * @param CodigoPromocional $codigoPromocional
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCodigoResgatavel(CodigoPromocional $codigoPromocional)
    {
        $isResgatavel = $this->codigoPromocionalService->isCodigoResgatavel($codigoPromocional);

        if ($isResgatavel) {
            return $this->successResponse('O código é resgatável.', ['codigo' => $codigoPromocional->codigo]);
        } else {
            return $this->errorResponse('O código não é mais resgatável.', 400);
        }
    }

    public function generateMultiple(Request $request)
    {
        $validatedData = $request->validate([
            'quantidade' => 'required|integer|min:1',
            'pontuacao' => 'required|integer',
            'limite_resgate' => 'nullable|integer|min:1|exclude_if:decrescimo,true|required_if:resgate_unico,false', // Exclui se descrescimo for true
            'resgate_unico' => 'required|boolean',
            'decrescimo' => 'nullable|boolean|exclude_if:resgate_unico,true', // Não obrigatório se resgate_unico for true
            'valor_decrescimo' => 'nullable|integer|required_if:decrescimo,true|exclude_if:resgate_unico,true', // Obrigatório apenas se decrescimo for true e resgate_unico for false
        ]);

        try {
            $quantidade = $request->input('quantidade');
            $codigos = $this->codigoPromocionalService->generateMultipleCodigos($quantidade, $validatedData);

            return $this->successResponse("{$quantidade} códigos promocionais gerados com sucesso.", $codigos);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function gerarCodigoManual(Request $request)
    {
        try {
            // Validação dos dados
            $validatedData = $request->validate([
                'codigo' => 'required|string|unique:codigo_promocionals,codigo',
                'pontuacao' => 'required|integer',
                'limite_resgate' => 'nullable|integer|min:1|required_if:resgate_unico,false',
                'resgate_unico' => 'required|boolean',
                'decrescimo' => 'nullable|boolean|exclude_if:resgate_unico,true',
                'valor_decrescimo' => 'nullable|integer|required_if:decrescimo,true|exclude_if:resgate_unico,true',
            ]);

            // Chama o serviço para gerar o código manual
            $codigo = $this->codigoPromocionalService->createManualCodigo($validatedData);

            return $this->successResponse('Código gerado com sucesso.', $codigo);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 422); // Retorna os erros de validação
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400); // Retorna qualquer outro erro
        }
    }
    public function resgatar(Request $request, $codigoPromocional)
    {
        try {
            // Tenta buscar o código pelo campo 'codigo'
            $codigoPromocional = CodigoPromocional::where('codigo', $codigoPromocional)->first();

            if (!$codigoPromocional) {
                throw new NotFoundHttpException('Código promocional não encontrado.');
            }

            $resgate = $this->codigoPromocionalService->resgatarCodigo($codigoPromocional);
            return $this->successResponse('Código resgatado com sucesso.', $resgate);
        } catch (NotFoundHttpException $e) {
            return $this->errorResponse($e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function mostrarPontuacao()
    {
        // Obtém o ID do usuário autenticado (competidor)
        $userId = Auth::id();

        // Calcula a pontuação total a partir dos resgates do competidor
        $pontuacaoTotal = Resgate::where('user_id', $userId)->sum('pontuacao');

        return $this->successResponse('Pontuação total obtida com sucesso.', [
            'pontuacao_total' => $pontuacaoTotal
        ]);
    }
}
