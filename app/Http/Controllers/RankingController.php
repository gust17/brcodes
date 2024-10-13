<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{
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
