<?php

namespace App\Repositories\Contracts;

use App\Models\GpsDevice;

interface GpsDeviceRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?GpsDevice;

    public function findById(int $id): ?GpsDevice;

    public function update(GpsDevice $device, array $data): GpsDevice;
}