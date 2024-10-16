<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodigoPromocionalController;
use App\Http\Controllers\RankingController;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResgateController;
use App\Models\User;



/**
 * @OA\Info(
 *     title="API de Gerenciamento de Códigos Promocionais",
 *     version="1.0.0",
 *     description="Documentação da API de registro de competidores, patrocinadores e administradores.",
 *     @OA\Contact(
 *         email="gustavo@codegus.com"
 *     )
 * )
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'role:administrador'])->group(function () {
    Route::post('/admin/create-user', [AdminController::class, 'createUser']);
    Route::post('/codigos', [CodigoPromocionalController::class, 'store']);
    Route::get('admin/codigos', [CodigoPromocionalController::class, 'index']);
    Route::put('/codigos/{codigoPromocional}', [CodigoPromocionalController::class, 'update']);
    Route::delete('/codigos/{codigoPromocional}', [CodigoPromocionalController::class, 'destroy']);
    Route::get('/codigos/{codigoPromocional}/resgatavel', [CodigoPromocionalController::class, 'checkCodigoResgatavel']);
    Route::post('admin/codigos/gerar-multiplos', [CodigoPromocionalController::class, 'generateMultiple']);
    Route::get('/admin/competidores', [AdminController::class, 'listarCompetidores']);
    Route::post('/admin/competidores', [AdminController::class, 'storeCompetidores']);
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    Route::post('/admin/codigos/gerar-manual', [CodigoPromocionalController::class, 'gerarCodigoManual']);
    Route::get('admin/ranking', [RankingController::class, 'getTopCompetitors']);


});
Route::middleware(['auth:sanctum', 'role:competidor'])->group(function () {
    Route::post('/codigos/{codigoPromocional}/resgatar', [CodigoPromocionalController::class, 'resgatar'])->middleware(middleware: ThrottleRequests::class . ':5,1');;
    Route::get('/competidor/pontuacao', [CodigoPromocionalController::class, 'mostrarPontuacao']);
    Route::get('/competidor/codigos-resgatados', [ResgateController::class, 'getCodigosResgatados']);
});


Route::get('gerar-admin', function () {
    $dados  = [
        "email" => "admin@example.com",
        "password" => "password123",
        "name" => "gus",
        "role" => "administrador"

    ];

    return User::create($dados);
});

Route::post('/login', [AuthController::class, 'login']);
//Route::post('/login', [AuthController::class, 'login'])->middleware(ThrottleRequests::class.':5,10');
