<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resgate;

class ResgateController extends Controller
{
    /**
     * Retorna os códigos já resgatados pelo competidor autenticado.
     *
     * @OA\Get(
     *     path="/competidor/codigos-resgatados",
     *     tags={"Resgates"},
     *     summary="Obter códigos resgatados pelo competidor",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(response=200, description="Códigos resgatados retornados com sucesso."),
     *     @OA\Response(response=401, description="Não autorizado"),
     * )
     */
    public function getCodigosResgatados(Request $request)
    {
        // Obtém o competidor autenticado
        $competidor = $request->user();

        // Busca os resgates realizados pelo competidor, ordenados pela data de criação
        $resgates = Resgate::with('codigoPromocional')
            ->where('user_id', $competidor->id)
            ->orderBy('created_at', 'desc') // Ordena pelo mais recente
            ->get();

        // Mapear os resgates com um identificador (ordenador)
        $resgatesFormatados = $resgates->map(function ($resgate, $index) {
            // Mascarar o código (mostrar os 3 primeiros caracteres e ocultar o resto)
            $codigoMascarado = substr($resgate->codigoPromocional->codigo, 0, 3) . str_repeat('#', strlen($resgate->codigoPromocional->codigo) - 3);

            return [
                'id' => $index + 1, // Identificador numérico
                'codigo' => $codigoMascarado,
                'data_hora' => $resgate->created_at->format('Y-m-d H:i:s'), // Formato padrão de data/hora
            ];
        });

        return response()->json([
            'message' => 'Códigos resgatados retornados com sucesso.',
            'data' => $resgatesFormatados,
            'status_code' => 200,
        ], 200);
    }
}
