<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 

    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'profile_photo',
        'two_factor_secret',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_desc()
    {
        return 'Super Admin'; 
    }

    public function assets()
    {
        return $this->morphMany(\App\Models\Asset::class, 'assetable');
    }

    // Tus relaciones originales se quedan igual
    public function projects(): HasMany { return $this->hasMany(Project::class, 'client_id'); }
    public function messages(): HasMany { return $this->hasMany(Message::class, 'sender_id'); }
    public function labPosts(): HasMany { return $this->hasMany(LabPost::class, 'author_id'); }
}