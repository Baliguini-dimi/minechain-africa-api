<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserManagementService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function listByOrganization(?int $organizationId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->paginateByOrganization($organizationId, $perPage);
    }

    public function find(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Crée un utilisateur invité : mot de passe temporaire généré,
     * statut "invited" (il devra le changer à la première connexion — logique à ajouter
     * plus tard côté frontend/flow de première connexion).
     */
    public function invite(array $data, User $actor): User
    {
        return DB::transaction(function () use ($data, $actor) {
            $temporaryPassword = Str::random(16);

            $user = $this->userRepository->create([
                ...$data,
                'organization_id' => $actor->organization_id,
                'password' => bcrypt($temporaryPassword),
                'status' => 'invited',
            ]);

            $this->logAudit($actor, 'create', $user);

            // TODO: envoyer un email d'invitation avec le mot de passe temporaire
            // (Service Mail à créer avec Resend, une fois la config mail branchée)

            return $user;
        });
    }

    public function update(User $user, array $data, User $actor): User
    {
        return DB::transaction(function () use ($user, $data, $actor) {
            $oldValues = $user->only(array_keys($data));

            $updated = $this->userRepository->update($user, $data);

            $this->logAudit($actor, 'update', $updated, $oldValues, $data);

            return $updated;
        });
    }

    public function suspend(User $user, User $actor): User
    {
        return $this->update($user, ['status' => 'suspended'], $actor);
    }

    public function reactivate(User $user, User $actor): User
    {
        return $this->update($user, ['status' => 'active'], $actor);
    }

    protected function logAudit(User $actor, string $action, User $user, array $oldValue = [], array $newValue = []): void
    {
        $actor->auditLogs()->create([
            'action' => $action,
            'entity_type' => User::class,
            'entity_id' => $user->id,
            'old_value' => $oldValue ?: null,
            'new_value' => $newValue ?: $user->only(['name', 'email', 'role_id', 'status']),
            'occurred_at' => now(),
        ]);
    }
}