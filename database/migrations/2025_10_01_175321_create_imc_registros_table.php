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
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
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
