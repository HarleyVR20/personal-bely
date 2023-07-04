<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cargo extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'area_id', 'nombre', 'created_at',
        'updated_at'
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
