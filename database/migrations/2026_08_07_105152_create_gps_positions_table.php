<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gps_device_id')
                ->constrained('gps_devices')
                ->cascadeOnDelete();
            $table->foreignId('lot_id')
                ->constrained('lots')
                ->cascadeOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->decimal('speed', 8, 2)->nullable();
            $table->timestamp('recorded_at');
            $table->boolean('is_anomaly_flagged')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gps_positions');
    }
};