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
        Schema::create('alergia_reacao', function (Blueprint $table) {
            $table->unsignedBigInteger('alergia_id');
            $table->foreign('alergia_id')->references('id')->on('alergias')->onDelete('cascade');
            $table->unsignedBigInteger('reacao_id');
            $table->foreign('reacao_id')->references('id')->on('reacoes')->onDelete('cascade');
            $table->primary(['alergia_id', 'reacao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alergia_reacao');
    }
};