<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recorte extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'tipo_recorte_id',
        'monto_recorte', 'observacion', 'created_at',
        'updated_at'
    ];

    public function tipo_recorte(): BelongsTo
    {
        return $this->belongsTo(TipoRecorte::class, 'tipo_recorte_id');
    }
}
