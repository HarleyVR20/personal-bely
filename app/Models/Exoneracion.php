<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exoneracion extends Model
{

    use HasFactory;

    protected $table = "exoneraciones";

    protected $fillable = [
        'id', 'empleado_id', 'motivo_exoneracion_id',
        'fecha_inicio', 'fecha_fin', 'observacion', 'created_at', 'updated_at'
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function motivoExoneracion(): BelongsTo
    {
        return $this->belongsTo(MotivoExoneracion::class);
    }
}
