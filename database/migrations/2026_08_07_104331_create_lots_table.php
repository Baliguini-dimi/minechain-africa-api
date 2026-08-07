<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();
            $table->foreignId('source_id')
                ->constrained('sources')
                ->restrictOnDelete();
            $table->foreignId('resource_type_id')
                ->constrained('resource_types')
                ->restrictOnDelete();
            $table->decimal('weight_volume', 15, 3);
            $table->string('weighing_mode')->nullable();
            $table->date('extraction_date');
            $table->timestamp('creation_date');
            $table->timestamp('departure_date')->nullable();
            $table->string('destination')->nullable();
            $table->string('transport_mode')->nullable();
            $table->foreignId('responsible_user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->enum('status', ['created', 'in_transit', 'delivered', 'closed', 'anomaly'])
                ->default('created');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};