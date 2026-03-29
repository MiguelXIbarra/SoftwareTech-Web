<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{

    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 

    protected $fillable = [
        'milestone_id',
        'amount',
        'transaction_id',
        'payment_method',
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function assets()
    {
        return $this->morphMany(\App\Models\Asset::class, 'assetable');
    }
}