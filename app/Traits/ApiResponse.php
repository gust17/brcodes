<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Padroniza o retorno de resposta em JSON
     *
     * @param string $message Mensagem a ser exibida
     * @param mixed|null $data Dados adicionais (pode ser nulo)
     * @param int $status Código de status HTTP (padrão 200)
     * @return JsonResponse
     */
    protected function jsonResponse(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status_code' => $status,
        ], $status);
    }

    /**
     * Resposta de sucesso
     */
    protected function successResponse(string $message, mixed $data = null): JsonResponse
    {
        return $this->jsonResponse($message, $data);
    }

    /**
     * Resposta de erro
     */
    protected function errorResponse(string $message, int $status = 400, mixed $data = null): JsonResponse
    {
        return $this->jsonResponse($message, $data, $status);
    }
}
