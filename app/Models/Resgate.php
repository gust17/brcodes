<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resgate extends Model
{
    protected $fillable = [
        'user_id',
        'codigo_promocional_id',
        'pontuacao',
    ];

    /**
     * Relacionamento com o usuário (competidor).
     */
    public function competidor()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * Relacionamento com o código promocional.
     */
    public function codigoPromocional()
    {
        return $this->belongsTo(CodigoPromocional::class,'codigo_promocional_id');
    }
}
