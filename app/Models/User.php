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
        'clickup_user_id',
        'activation_token',
        'active',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'two_factor_recovery_codes',
        'notification_preferences',
        'last_login_ip',
        'last_login_at',
        'known_ips',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'notification_preferences' => 'array',
            'known_ips' => 'array',
        ];
    }

    public function hasTwoFactorEnabled(): bool
    {
        return !empty($this->two_factor_secret) && $this->two_factor_confirmed_at !== null;
    }

    public function getNotificationPreference(string $key, bool $default = true): bool
    {
        $prefs = $this->notification_preferences ?? [];
        return isset($prefs[$key]) ? (bool) $prefs[$key] : $default;
    }

    public function isClient(): bool
    {
        return $this->role === 'cliente';
    }

    public function isTeamMember(): bool
    {
        return in_array($this->role, ['superadmin', 'admin', 'empleado']);
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
