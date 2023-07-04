<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empleado extends Model
{

    use HasFactory;

    protected $fillable = [
        'id', 'nombre', 'apellidos',
        'dni', 'fecha_nacimiento', 'domicilio_fiscal',
        'telf', 'correo', 'created_at',
        'updated_at'
    ];

    /**
     * Obtener las asistencias del empleado.
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    public function exoneraciones(): HasMany
    {
        return $this->hasMany(Exoneracion::class);
    }

    public function perfilEmpleado(): HasOne
    {
        return $this->hasOne(PerfilEmpleado::class);
    }
}
