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
        Schema::create('tipo_contratos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->double('plazo', 15, 8)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Agregar esta línea para habilitar el borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_contratos');
    }
};
