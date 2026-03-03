<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{

    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 

    protected $fillable = [
        'project_id',
        'title',
        'cost',
        'is_paid',
        'due_date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}