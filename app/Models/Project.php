<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'servicio',
        'estado',
        'priority',
        'progreso',
        'siguiente_entrega',
        'user_id',
        'developer_id',
        'clickup_list_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function team()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
            ->withPivot('sueldo_proyecto', 'importancia')
            ->withTimestamps();
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
}
