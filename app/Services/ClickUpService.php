<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickUpService
{
    protected ?string $token;
    protected ?string $folderId;
    protected ?string $lastError = null;
    protected string $baseUrl = 'https://api.clickup.com/api/v2';

    public function __construct()
    {
        $this->token = config('services.clickup.token');
        $this->folderId = config('services.clickup.folder_id');
    }

    /**
     * Devuelve el último error capturado de la API de ClickUp.
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Verifica si las credenciales de ClickUp están configuradas.
     */
    public function isConfigured(): bool
    {
        return !empty($this->token) && !empty($this->folderId);
    }

    /**
     * Cliente HTTP base autenticado para ClickUp.
     */
    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => $this->token,
            'Content-Type' => 'application/json',
        ])->timeout(12);
    }

    /**
     * Obtiene los equipos/workspaces de ClickUp.
     */
    public function getWorkspaceTeams(): array
    {
        $this->lastError = null;

        if (!$this->token) {
            $this->lastError = 'Token de ClickUp no configurado.';
            return [];
        }

        try {
            $response = $this->client()->get("{$this->baseUrl}/team");
            if ($response->successful()) {
                return $response->json('teams') ?? [];
            }
            $body = $response->json();
            $this->lastError = $body['err'] ?? $body['message'] ?? $response->body();
            Log::warning("ClickUp: Error al consultar equipos - " . $response->body());
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            Log::error("ClickUp: Excepción al consultar equipos - " . $e->getMessage());
        }

        return [];
    }

    /**
     * Obtiene una lista unificada de todos los usuarios (members e invitados) del Workspace.
     */
    public function getWorkspaceUsers(): array
    {
        $teams = $this->getWorkspaceTeams();
        $users = [];

        foreach ($teams as $team) {
            $members = $team['members'] ?? [];
            foreach ($members as $member) {
                if (isset($member['user'])) {
                    $u = $member['user'];
                    $users[strtolower(trim($u['email'] ?? ''))] = [
                        'id' => (string) ($u['id'] ?? ''),
                        'username' => $u['username'] ?? '',
                        'email' => strtolower(trim($u['email'] ?? '')),
                        'role' => $u['role'] ?? null,
                    ];
                }
            }
        }

        return $users;
    }

    /**
     * Resuelve y persiste el ClickUp User ID para un usuario operativo del portal.
     * Si el usuario es un cliente, retorna NULL (nunca se sincroniza con ClickUp).
     */
    public function resolveClickUpUserId(User $user): ?string
    {
        // Los clientes nunca se sincronizan a ClickUp
        if ($user->isClient()) {
            return null;
        }

        if (!empty($user->clickup_user_id)) {
            return $user->clickup_user_id;
        }

        $email = strtolower(trim($user->email));
        if (empty($email)) {
            return null;
        }

        $workspaceUsers = $this->getWorkspaceUsers();
        if (isset($workspaceUsers[$email])) {
            $clickupId = $workspaceUsers[$email]['id'];
            $user->clickup_user_id = $clickupId;
            $user->saveQuietly();
            return $clickupId;
        }

        Log::info("ClickUp: No se encontró usuario en el workspace con email {$email}");
        return null;
    }

    /**
     * Crea una nueva lista en la carpeta configurada de ClickUp.
     */
    public function createProjectList(string $name, ?string $content = null): ?string
    {
        if (!$this->isConfigured()) {
            Log::warning("ClickUp: No se puede crear lista porque falta configuración.");
            return null;
        }

        try {
            $payload = ['name' => $name];
            if ($content) {
                $payload['content'] = $content;
            }

            $response = $this->client()->post("{$this->baseUrl}/folder/{$this->folderId}/list", $payload);

            if ($response->successful()) {
                $listId = $response->json('id');
                Log::info("ClickUp: Lista '{$name}' creada con ID {$listId}");
                return (string) $listId;
            }

            Log::error("ClickUp: Error al crear lista '{$name}' - " . $response->body());
        } catch (\Exception $e) {
            Log::error("ClickUp: Excepción al crear lista - " . $e->getMessage());
        }

        return null;
    }

    /**
     * Elimina una lista en ClickUp.
     */
    public function deleteProjectList(string $listId): bool
    {
        if (!$this->token || empty($listId)) {
            return false;
        }

        try {
            $response = $this->client()->delete("{$this->baseUrl}/list/{$listId}");
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("ClickUp: Excepción al eliminar lista {$listId} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Asigna un usuario del portal a una lista de ClickUp con un nivel de permiso específico.
     * Nivel de permiso: 'full' (Admin/Líder) o 'edit' / 'create' (Desarrollador).
     */
    public function assignUserToList(string $listId, User $user, string $permissionLevel = 'edit'): bool
    {
        // Doble validación: descartar clientes
        if ($user->isClient()) {
            return false;
        }

        $clickupUserId = $this->resolveClickUpUserId($user);
        if (!$clickupUserId) {
            Log::warning("ClickUp: No se pudo asignar a la lista {$listId} porque el usuario {$user->email} no tiene clickup_user_id.");
            return false;
        }

        $permissionLevel = strtolower($permissionLevel);
        if (!in_array($permissionLevel, ['full', 'edit', 'create', 'comment', 'read'])) {
            $permissionLevel = 'edit';
        }

        try {
            // Intentar asignar como Guest / Miembro en la lista
            $response = $this->client()->post("{$this->baseUrl}/list/{$listId}/guest/{$clickupUserId}", [
                'permission_level' => $permissionLevel,
            ]);

            if ($response->successful()) {
                Log::info("ClickUp: Usuario {$user->email} (ID {$clickupUserId}) asignado a lista {$listId} con permiso '{$permissionLevel}'.");
                return true;
            }

            // Si falla como guest, intentar endpoint /member/
            $responseMember = $this->client()->post("{$this->baseUrl}/list/{$listId}/member/{$clickupUserId}", [
                'permission_level' => $permissionLevel,
            ]);

            if ($responseMember->successful()) {
                Log::info("ClickUp: Miembro {$user->email} asignado a lista {$listId} con permiso '{$permissionLevel}'.");
                return true;
            }

            Log::warning("ClickUp: Respuesta al asignar usuario {$clickupUserId} a lista {$listId} - Guest: {$response->status()}, Member: {$responseMember->status()}");
            return false;
        } catch (\Exception $e) {
            Log::error("ClickUp: Excepción al asignar usuario {$user->email} a lista {$listId} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remueve un usuario de una lista de ClickUp.
     */
    public function removeUserFromList(string $listId, User $user): bool
    {
        if ($user->isClient()) {
            return false;
        }

        $clickupUserId = $this->resolveClickUpUserId($user);
        if (!$clickupUserId) {
            return false;
        }

        try {
            $response = $this->client()->delete("{$this->baseUrl}/list/{$listId}/guest/{$clickupUserId}");
            if ($response->successful()) {
                return true;
            }

            $responseMember = $this->client()->delete("{$this->baseUrl}/list/{$listId}/member/{$clickupUserId}");
            return $responseMember->successful();
        } catch (\Exception $e) {
            Log::error("ClickUp: Excepción al remover usuario {$user->email} de lista {$listId} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sincroniza todos los miembros asignados y el líder del proyecto con la lista de ClickUp.
     * - Líder (developer_id): Permiso 'full' (Administrador de la lista).
     * - Desarrolladores (team): Permiso 'edit' (Colaboradores).
     * - Cliente (user_id): EXCLUIDO estrictamente.
     */
    public function syncProjectMembers(Project $project): array
    {
        if (empty($project->clickup_list_id)) {
            return [
                'status' => 'skipped',
                'message' => 'El proyecto no tiene una lista de ClickUp asociada.',
            ];
        }

        $project->loadMissing(['developer', 'team', 'user']);
        $listId = $project->clickup_list_id;
        $synced = [];

        // 1. Sincronizar Líder del Proyecto (Permiso FULL / Admin de la lista)
        if ($project->developer && !$project->developer->isClient()) {
            $success = $this->assignUserToList($listId, $project->developer, 'full');
            $synced[] = [
                'user' => $project->developer->name,
                'email' => $project->developer->email,
                'role' => 'Líder / Admin de Lista',
                'permission' => 'full',
                'success' => $success,
            ];
        }

        // 2. Sincronizar Desarrolladores / Miembros del Equipo (Permiso EDIT)
        foreach ($project->team as $member) {
            // Evitar duplicar si es el mismo líder y asegurar que no sea cliente
            if ($member->id === $project->developer_id || $member->isClient()) {
                continue;
            }

            $success = $this->assignUserToList($listId, $member, 'edit');
            $synced[] = [
                'user' => $member->name,
                'email' => $member->email,
                'role' => 'Desarrollador',
                'permission' => 'edit',
                'success' => $success,
            ];
        }

        return [
            'status' => 'completed',
            'list_id' => $listId,
            'members' => $synced,
        ];
    }
}
