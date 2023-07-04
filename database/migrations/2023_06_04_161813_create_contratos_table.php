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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('tipo_contrato_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->longText('marco_legal');
            $table->longText('observacion');
            $table->date('fecha_vinculacion');
            $table->date('fecha_retiro')->nullable();
            $table->json('dias_laborales'); //array
            $table->time('horario_entrada');
            $table->time('horario_salida');
            $table->decimal('salario_base', 8, 2);

            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('tipo_contrato_id')->references('id')->on('tipo_contratos')->onDelete('cascade');
            $table->foreign('modalidad_id')->references('id')->on('modalidades')->onDelete('cascade');

            $table->softDeletes(); // Agregar esta línea para habilitar el borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
