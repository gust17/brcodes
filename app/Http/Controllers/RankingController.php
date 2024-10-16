<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{

    /**
 * @OA\Get(
 *     path="/admin/ranking",
 *     summary="Obter ranking dos competidores",
 *     description="Retorna os 10 competidores com maior pontuação.",
 *     tags={"admin"},
 *     @OA\Response(
 *         response=200,
 *         description="Ranking dos competidores retornado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Ranking dos competidores."),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="name", type="string", example="João Silva"),
 *                     @OA\Property(property="pontuacao", type="integer", example=1500)
 *                 )
 *             ),
 *             @OA\Property(property="status_code", type="integer", example=200)
 *         )
 *     )
 * )
 */

    public function getTopCompetitors()
    {
        // Obtém os 10 competidores com maior pontuação
        $competitors = User::where('role', 'competidor')
                            ->orderBy('pontuacao', 'desc')
                            ->take(10)
                            ->get(['name','pontuacao']);

        return response()->json([
            'message' => 'Ranking dos competidores.',
            'data' => $competitors,
            'status_code' => 200,
        ]);
    }
}
