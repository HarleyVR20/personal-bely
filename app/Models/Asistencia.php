<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'dia', 'hora_entrada', 'hora_salida', 'empleado_id',
        'area_id', 'created_at', 'updated_at',

    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
