<?php

namespace App\Repositories\Contracts;

use App\Models\Anomaly;

interface AnomalyRepositoryInterface
{
    public function create(array $data): Anomaly;

    public function findById(int $id): ?Anomaly;

    public function listOpenForOrganization(int $organizationId, int $limit = 10);

    public function update(Anomaly $anomaly, array $data): Anomaly;
}
