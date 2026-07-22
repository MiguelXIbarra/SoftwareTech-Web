<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Filtro previo: Si es Super Admin, tiene permiso absoluto para todo.
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'superadmin') {
            return true;
        }
    }

    /**
     * Determina quién puede VER los detalles de un proyecto.
     */
    public function view(User $user, Project $project): bool
    {
        // Administradores, Empleados y Clientes solo ven el proyecto si están asignados a él
        return $user->proyectos->contains($project->id);
    }

    /**
     * Determina quién puede EDITAR o ACTUALIZAR el proyecto.
     */
    public function update(User $user, Project $project): bool
    {
        // Solo el Administrador asignado puede editar; Empleados y Clientes devuelven false
        return $user->role === 'admin' && $user->proyectos->contains($project->id);
    }

    /**
     * Determina quién puede ELIMINAR el proyecto.
     */
    public function delete(User $user, Project $project): bool
    {
        // Siguiendo tu regla: El Admin asignado tiene los mismos permisos que el Super Admin en su proyecto
        return $user->role === 'admin' && $user->proyectos->contains($project->id);
    }
}
