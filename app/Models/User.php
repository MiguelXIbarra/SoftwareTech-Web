<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Project;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'activation_token',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function proyectos()
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id')
                    ->withPivot('sueldo_proyecto', 'importancia')
                    ->withTimestamps();
    }

    public function corporation()
    {
        return $this->hasOne(TeamCorporation::class, 'user_id');
    }

    public function proyectosLiderados()
    {
        return $this->hasMany(Project::class, 'developer_id');
    }
}
