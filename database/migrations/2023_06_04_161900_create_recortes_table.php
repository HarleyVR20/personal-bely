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
        Schema::create('recortes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_recorte_id');
            $table->foreign('tipo_recorte_id')->references('id')->on('tipo_recortes')->onDelete('cascade');
            $table->decimal('monto_recorte');
            $table->string('observacion');
            $table->timestamps();
            $table->softDeletes(); // Agregar esta línea para habilitar el borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recortes');
    }
};
