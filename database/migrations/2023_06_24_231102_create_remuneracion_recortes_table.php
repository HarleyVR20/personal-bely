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
        Schema::create('remuneracion_recortes', function (Blueprint $table) {
            $table->unsignedBigInteger('remuneracion_id');
            $table->foreign('remuneracion_id')->references('id')->on('remuneraciones')->onDelete('cascade');
            $table->unsignedBigInteger('recorte_id');
            $table->foreign('recorte_id')->references('id')->on('recortes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); // Agregar esta línea para habilitar el borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remuneracion_recortes');
    }
};
