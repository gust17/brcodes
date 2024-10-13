<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('codigo_promocionals', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código promocional
            $table->integer('pontuacao'); // Pontuação associada ao código
            $table->boolean('limite_resgate')->default(false); // Número de vezes que o código pode ser resgatado
            $table->integer('qtd_limite_resgate')->default(0);
            $table->boolean('resgate_unico')->default(true); // Se o resgate é único ou múltiplo
            $table->boolean('decrescimo')->default(false); // Se o valor da pontuação diminui a cada resgate
            $table->integer('valor_decrescimo')->nullable(); // Valor que diminui a cada resgate, se aplicável
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID do usuário que gerou o código
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_promocionals');
    }
};
