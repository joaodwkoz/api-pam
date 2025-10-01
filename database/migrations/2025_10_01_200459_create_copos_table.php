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
        Schema::create('copos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('capacidade');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('icone_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('icone_id')->references('id')->on('icones')->onDelete('set null'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copos');
    }
};
