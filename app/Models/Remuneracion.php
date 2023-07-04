<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remuneracion extends Model
{
    use HasFactory;
    protected $table = 'remuneraciones';
    protected $fillable = [
        'id', 'empleado_id', 'contrato_id',
        'concepto', 'monto_total', 'created_at',
        'updated_at'
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function calcularVerInacistencias()
    {
        $asistencias = [];
        return $asistencias;
    }
    public function recortes()
    {
        return $this->belongsToMany(Recorte::class, 'remuneracion_recortes', 'remuneracion_id', 'recorte_id');
    }
}
