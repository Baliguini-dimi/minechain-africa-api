<?php

namespace App\Services;

use App\Models\Lot;
use App\Models\Passport;
use App\Models\User;
use App\Repositories\Contracts\LotRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LotService
{
    public function __construct(
        protected LotRepositoryInterface $lotRepository,
        protected PassportChainService $passportChainService,
        protected QrCodeService $qrCodeService
    ) {
    }

    public function listByOrganization(?int $organizationId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->lotRepository->paginateByOrganization($organizationId, $perPage);
    }

    public function find(int $id): ?Lot
    {
        return $this->lotRepository->findById($id);
    }

    /**
     * Crée un lot ET génère automatiquement son passeport numérique
     * avec le premier événement de la chaîne (event_type: creation),
     * conformément à 01-cahier-des-charges.md §4.
     */
    public function create(array $data, User $actor): Lot
    {
        return DB::transaction(function () use ($data, $actor) {
            $lot = $this->lotRepository->create([
                ...$data,
                'organization_id' => $actor->organization_id,
                'responsible_user_id' => $actor->id,
                'creation_date' => now(),
                'status' => 'created',
            ]);

            $passport = $this->createPassportFor($lot);

            $this->passportChainService->appendEvent(
                $passport,
                'creation',
                $actor,
                payload: [
                    'weight_volume' => $lot->weight_volume,
                    'resource_type_id' => $lot->resource_type_id,
                    'source_id' => $lot->source_id,
                ]
            );

            $this->qrCodeService->generateFor($lot);

            $this->logAudit($actor, 'create', $lot);

            return $lot->fresh(['passport.events', 'qrCode']);
        });
    }

    /**
     * Marque le lot comme expédié : date de départ auto, ajout de l'événement
     * "departure" à la chaîne. Nécessite validation Superviseur selon
     * 04-roles-et-permissions.md §6 (à brancher via Policy sur la route).
     */
    public function markAsDeparted(Lot $lot, User $actor): Lot
    {
        return DB::transaction(function () use ($lot, $actor) {
            $updated = $this->lotRepository->update($lot, [
                'departure_date' => now(),
                'status' => 'in_transit',
            ]);

            $this->passportChainService->appendEvent(
                $lot->passport,
                'departure',
                $actor,
                payload: ['destination' => $lot->destination, 'transport_mode' => $lot->transport_mode]
            );

            $this->logAudit($actor, 'departure', $updated);

            return $updated->fresh(['passport.events']);
        });
    }

    /**
     * Dernier contrôle effectué, lot validé comme livré.
     * Ajoute l'événement "delivery" à la chaîne.
     */
    public function markAsDelivered(Lot $lot, User $actor): Lot
    {
        return DB::transaction(function () use ($lot, $actor) {
            $updated = $this->lotRepository->update($lot, [
                'status' => 'delivered',
            ]);

            $this->passportChainService->appendEvent(
                $lot->passport,
                'delivery',
                $actor,
                payload: ['delivered_at' => now()->toIso8601String()]
            );

            $this->logAudit($actor, 'delivery', $updated);

            return $updated->fresh(['passport.events']);
        });
    }

    /**
     * Clôture définitive du passeport (01-cahier-des-charges.md §5) :
     * dernier événement "closure" ajouté à la chaîne, passeport et lot clôturés.
     * Historique reste consultable et auditable en permanence (rien n'est supprimé).
     */
    public function closePassport(Lot $lot, User $actor): Lot
    {
        return DB::transaction(function () use ($lot, $actor) {
            if ($lot->status !== 'delivered') {
                throw ValidationException::withMessages([
                    'status' => ["Le lot doit être livré avant que son passeport puisse être clôturé."],
                ]);
            }

            $this->passportChainService->appendEvent(
                $lot->passport,
                'closure',
                $actor
            );

            $lot->passport->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            $updated = $this->lotRepository->update($lot, [
                'status' => 'closed',
            ]);

            $this->logAudit($actor, 'closure', $updated);

            return $updated->fresh(['passport.events']);
        });
    }

    protected function createPassportFor(Lot $lot): Passport
    {
        return Passport::create([
            'lot_id' => $lot->id,
            'unique_identifier' => 'PASS-' . strtoupper(Str::random(10)),
            'status' => 'open',
        ]);
    }

    protected function logAudit(User $actor, string $action, Lot $lot): void
    {
        $actor->auditLogs()->create([
            'action' => $action,
            'entity_type' => Lot::class,
            'entity_id' => $lot->id,
            'new_value' => $lot->only(['status', 'weight_volume', 'destination']),
            'occurred_at' => now(),
        ]);
    }
}