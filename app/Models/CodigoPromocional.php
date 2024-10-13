<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoPromocional extends Model
{
    use HasFactory;


    protected $fillable = [
        'codigo',
        'pontuacao',
        'limite_resgate',
        'resgate_unico',
        'decrescimo',
        'qtd_limite_resgate',
        'valor_decrescimo',
        'user_id'  // Adiciona o ID do usuário que gerou o código
    ];

    /**
     * Relacionamento com o usuário que gerou o código promocional.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
