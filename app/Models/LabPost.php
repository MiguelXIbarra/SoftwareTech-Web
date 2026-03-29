<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabPost extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'body',
        'type',
        'is_public',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assets()
    {
        return $this->morphMany(\App\Models\Asset::class, 'assetable');
    }
}