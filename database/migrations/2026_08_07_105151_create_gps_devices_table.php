<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')
                ->nullable()
                ->constrained('lots')
                ->nullOnDelete();
            $table->string('device_identifier')->unique();
            $table->enum('status', ['active', 'inactive', 'lost'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gps_devices');
    }
};