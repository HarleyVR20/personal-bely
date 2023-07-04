<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    use HasFactory;

    protected $table = "tipo_contratos";

    protected $fillable = [
        'id', 'tipo', 'plazo',
        'created_at', 'updated_at'
    ];
}
