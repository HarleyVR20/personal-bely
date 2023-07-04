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
        Schema::create('perfil_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id')->unique(); // Cambiar a clave única
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->string('profesion');
            $table->string('cuenta_bancaria');
            $table->timestamps();
            $table->softDeletes(); // Agregar esta línea para habilitar el borrado suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_empleados');
    }
};
