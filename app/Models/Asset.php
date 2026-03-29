<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'path',
        'tipo',
        'assetable_id',
        'assetable_type',
    ];

    public function assetable()
    {
        return $this->morphTo();
    }
}