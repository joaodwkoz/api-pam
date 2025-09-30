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
        Schema::create('refeicoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('nome_alimento');
            $table->integer('calorias');
            $table->integer('quantidade');
            $table->integer('total_calorias');
            $table->enum('tipo_refeicao', ['cafe_manha', 'almoco', 'jantar', 'lanche']);
            $table->date('data_refeicao');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refeicoes');
    }
};
