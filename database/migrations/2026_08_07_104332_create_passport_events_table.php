<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passport_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passport_id')
                ->constrained('passports')
                ->cascadeOnDelete();
            $table->enum('event_type', [
                'creation',
                'departure',
                'checkpoint_control',
                'anomaly',
                'delivery',
                'closure',
            ]);
            $table->foreignId('actor_user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->jsonb('location')->nullable();
            $table->jsonb('payload')->nullable();
            $table->string('prev_hash')->nullable();
            $table->string('hash');
            $table->string('signature');
            $table->timestamp('occurred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_events');
    }
};