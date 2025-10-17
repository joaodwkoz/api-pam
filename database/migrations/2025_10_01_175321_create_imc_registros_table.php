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
        Schema::create('imc_registros', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->decimal('peso', 5, 2);
            $table->decimal('altura', 3, 2);
            $table->decimal('imc', 4, 2);
            $table->timestamps();
        });
    }
       

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imc_registros');
    }
};
