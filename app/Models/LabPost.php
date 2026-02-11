<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabPost extends Model
{
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
}