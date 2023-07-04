<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoRecorte extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'description', 'tipo', 'created_at',
        'updated_at'
    ];

    public function recortes(): HasMany
    {
        return $this->hasMany(Recorte::class, 'tipo_recorte_id');
    }
}
