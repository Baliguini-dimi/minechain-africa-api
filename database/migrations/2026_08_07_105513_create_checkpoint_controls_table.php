<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkpoint_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkpoint_id')
                ->constrained('checkpoints')
                ->restrictOnDelete();
            $table->foreignId('lot_id')
                ->constrained('lots')
                ->cascadeOnDelete();
            $table->foreignId('agent_user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamp('control_datetime');
            $table->enum('status', ['ok', 'anomaly_reported'])->default('ok');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkpoint_controls');
    }
};