<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relación con los proyectos del usuario.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Relación con los mensajes enviados por el usuario.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Relación con las publicaciones en el laboratorio.
     */
    public function labPosts(): HasMany
    {
        return $this->hasMany(LabPost::class, 'author_id');
    }
}