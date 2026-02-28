<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
// ELIMINAMOS LA LÍNEA DE FORTIFY QUE ESTÁ INTERFIRIENDO

class User extends Authenticatable
{
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

    // Tus relaciones originales se quedan igual
    public function projects(): HasMany { return $this->hasMany(Project::class, 'client_id'); }
    public function messages(): HasMany { return $this->hasMany(Message::class, 'sender_id'); }
    public function labPosts(): HasMany { return $this->hasMany(LabPost::class, 'author_id'); }
}