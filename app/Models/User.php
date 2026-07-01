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
        'name', 'email', 'password', 'role', 'active', 'activation_token', 'profile_photo',
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

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = password_get_info($value)['algo'] === 0
                ? bcrypt($value)
                : $value;
        }
    }

    public function getAuthPassword()
    {
        if (strlen($this->password) === 64) {
            return password_hash($this->password, PASSWORD_BCRYPT);
        }

        return $this->password;
    }

    public function adminlte_desc()
    {
        return 'Super Admin';
    }

    public function assets()
    {
        return $this->morphMany(\App\Models\Asset::class, 'assetable');
    }

    public function projects(): HasMany { return $this->hasMany(Project::class, 'user_id'); }
    public function messages(): HasMany { return $this->hasMany(Message::class, 'sender_id'); }
    public function labPosts(): HasMany { return $this->hasMany(LabPost::class, 'author_id'); }
}
