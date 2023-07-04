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
        Schema::create('remuneraciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recorte_id')->nullable();
            $table->foreign('recorte_id')->references('id')->on('recortes')->onDelete('cascade');
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->unsignedBigInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->longText('concepto');
            $table->decimal('monto_total', 8, 2);
            $table->timestamps();
            $table->softDeletes(); // Agregar esta línea para habilitar el borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remuneraciones', function (Blueprint $table) {
            $table->dropForeign(['recorte_id']);
            $table->dropColumn('recorte_id');
        });
        // Schema::dropIfExists('remuneraciones');
    }
};
