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
        'name',
        'cost',
        'is_paid',
        'due_date',
        'status',
        'approval_status',
        'approved_at',
        'approved_by',
        'approval_notes',
        'approval_ip',
        'feedback_changes',
        'feedback_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'feedback_at' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assets()
    {
        return $this->morphMany(\App\Models\Asset::class, 'assetable');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}