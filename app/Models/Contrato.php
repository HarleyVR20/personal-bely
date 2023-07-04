<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrato extends Model
{
    use HasFactory;

    protected $table = "contratos";

    protected $fillable = [
        'id', 'empleado_id', 'tipo_contrato_id', 'modalidad_id',
        'fecha_vinculacion', 'fecha_retiro', 'dias_laborales',
        'horario_entrada', 'horario_salida', 'salario_base',
        'marco_legal', 'observacion', 'created_at',
        'updated_at',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoContrato(): BelongsTo
    {
        return $this->belongsTo(TipoContrato::class);
    }

    public function modalidad(): BelongsTo
    {
        return $this->belongsTo(Modalidad::class);
    }
}
