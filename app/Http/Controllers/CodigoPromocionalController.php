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
     * @OA\Post(
     *     path="/codigos",
     *     summary="Criação de um novo código promocional",
     *     description="Permite criar um novo código promocional com as informações fornecidas.",
     *     tags={"admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pontuacao", "resgate_unico"},
     *             @OA\Property(property="pontuacao", type="integer", example=100, description="Pontuação associada ao código promocional"),
     *             @OA\Property(property="resgate_unico", type="boolean", example=true, description="Se o código pode ser resgatado uma única vez"),
     *             @OA\Property(property="limite_resgate", type="integer", example=5, description="Número máximo de resgates permitidos"),
     *             @OA\Property(property="decrescimo", type="boolean", example=false, description="Se o valor do resgate diminui após cada uso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código promocional criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Código promocional criado com sucesso."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao criar código promocional",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao criar código promocional.")
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/codigos/{codigoPromocional}",
     *     summary="Atualização de código promocional",
     *     description="Permite atualizar as informações de um código promocional existente.",
     *     tags={"admin"},
     *     @OA\Parameter(
     *         name="codigoPromocional",
     *         in="path",
     *         required=true,
     *         description="ID do código promocional a ser atualizado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pontuacao", "resgate_unico"},
     *             @OA\Property(property="pontuacao", type="integer", example=100, description="Pontuação associada ao código promocional"),
     *             @OA\Property(property="resgate_unico", type="boolean", example=true, description="Se o código pode ser resgatado uma única vez"),
     *             @OA\Property(property="limite_resgate", type="integer", example=5, description="Número máximo de resgates permitidos"),
     *             @OA\Property(property="decrescimo", type="boolean", example=false, description="Se o valor do resgate diminui após cada uso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código promocional atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Código promocional atualizado com sucesso."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao atualizar código promocional",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao atualizar código promocional.")
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/codigos/{codigoPromocional}",
     *     summary="Exclusão de código promocional",
     *     description="Realiza a exclusão (soft delete) de um código promocional.",
     *     tags={"admin"},
     *     @OA\Parameter(
     *         name="codigoPromocional",
     *         in="path",
     *         required=true,
     *         description="ID do código promocional a ser excluído",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código promocional excluído com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Código promocional excluído com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao excluir código promocional",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao excluir código promocional.")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/admin/codigos",
     *     summary="Listar códigos promocionais",
     *     description="Retorna a lista de todos os códigos promocionais cadastrados.",
     *     tags={"admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de códigos promocionais retornada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lista de códigos promocionais."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $codigos = CodigoPromocional::all();
        return $this->successResponse('Lista de códigos promocionais.', $codigos);
    }

    /**
     * @OA\Get(
     *     path="/codigos/{codigoPromocional}/resgatavel",
     *     summary="Verificar se o código é resgatável",
     *     description="Verifica se um código promocional pode ser resgatado.",
     *     tags={"admin"},
     *     @OA\Parameter(
     *         name="codigoPromocional",
     *         in="path",
     *         required=true,
     *         description="ID do código promocional a ser verificado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="O código é resgatável",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O código é resgatável."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="O código não é mais resgatável",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O código não é mais resgatável.")
     *         )
     *     )
     * )
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


    /**
     * @OA\Post(
     *     path="/admin/codigos/gerar-multiplos",
     *     summary="Gerar múltiplos códigos promocionais",
     *     description="Gera vários códigos promocionais de uma vez, com base nos parâmetros fornecidos.",
     *     tags={"admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantidade", "pontuacao", "resgate_unico"},
     *             @OA\Property(property="quantidade", type="integer", example=10, description="Quantidade de códigos a serem gerados"),
     *             @OA\Property(property="pontuacao", type="integer", example=100, description="Pontuação associada a cada código promocional"),
     *             @OA\Property(property="resgate_unico", type="boolean", example=true, description="Se o código pode ser resgatado apenas uma vez"),
     *             @OA\Property(property="limite_resgate", type="integer", example=5, description="Número máximo de resgates permitidos"),
     *             @OA\Property(property="decrescimo", type="boolean", example=false, description="Se o valor do resgate diminui após cada uso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Códigos promocionais gerados com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="10 códigos promocionais gerados com sucesso."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao gerar códigos promocionais",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao gerar códigos promocionais.")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Post(
     *     path="/admin/codigos/gerar-manual",
     *     summary="Gerar código promocional manualmente",
     *     description="Permite a criação manual de um código promocional.",
     *     tags={"admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codigo", "pontuacao", "resgate_unico"},
     *             @OA\Property(property="codigo", type="string", example="PROMO2024", description="Código promocional manual"),
     *             @OA\Property(property="pontuacao", type="integer", example=100, description="Pontuação associada ao código"),
     *             @OA\Property(property="resgate_unico", type="boolean", example=true, description="Se o código pode ser resgatado uma única vez"),
     *             @OA\Property(property="limite_resgate", type="integer", example=5, description="Número máximo de resgates permitidos"),
     *             @OA\Property(property="decrescimo", type="boolean", example=false, description="Se o valor do resgate diminui após cada uso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código promocional gerado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Código gerado com sucesso."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao gerar código promocional",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao gerar código promocional.")
     *         )
     *     )
     * )
     */


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

    /**
     * @OA\Post(
     *     path="/codigos/{codigoPromocional}/resgatar",
     *     summary="Resgatar um código promocional",
     *     description="Permite que o competidor resgate um código promocional.",
     *     tags={"competidor"},
     *     @OA\Parameter(
     *         name="codigoPromocional",
     *         in="path",
     *         required=true,
     *         description="Código promocional a ser resgatado",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código resgatado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Código resgatado com sucesso."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao resgatar código promocional",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao resgatar código promocional.")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Get(
     *     path="/competidor/pontuacao",
     *     summary="Mostrar pontuação do competidor",
     *     description="Retorna a pontuação total do competidor com base nos resgates realizados.",
     *     tags={"competidor"},
     *     @OA\Response(
     *         response=200,
     *         description="Pontuação total retornada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pontuação total obtida com sucesso."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="pontuacao_total", type="integer", example=500)
     *             )
     *         )
     *     )
     * )
     */


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
