<?php

namespace App\Repositories;

use App\Models\GpsDevice;
use App\Repositories\Contracts\GpsDeviceRepositoryInterface;

class GpsDeviceRepository implements GpsDeviceRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?GpsDevice
    {
        return GpsDevice::where('device_identifier', $identifier)->first();
    }

    public function findById(int $id): ?GpsDevice
    {
        return GpsDevice::find($id);
    }

    public function update(GpsDevice $device, array $data): GpsDevice
    {
        $device->update($data);

        return $device->fresh();
    }
}