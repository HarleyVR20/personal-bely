<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PerfilEmpleado extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'empleado_id', 'profesion',
        'cuenta_bancaria', 'created_at', 'updated_at'
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
}
