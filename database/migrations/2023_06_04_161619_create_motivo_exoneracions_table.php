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
        Schema::create('motivo_exoneraciones', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes(); // Agregar esta línea para habilitar el borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motivo_exoneraciones');
    }
};
