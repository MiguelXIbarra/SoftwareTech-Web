<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TeamCorporation extends Model
{
    use HasFactory;

    protected $table = 'team_corporation';

    protected $fillable = [
        'user_id',
        'edad',
        'capacity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
