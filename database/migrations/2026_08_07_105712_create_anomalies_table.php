<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anomalies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')
                ->constrained('lots')
                ->cascadeOnDelete();
            $table->enum('type', [
                'ecart_poids',
                'sceau_brise',
                'itineraire_inhabituel',
                'document_manquant',
                'autre',
            ]);
            $table->text('description')->nullable();
            $table->enum('severity', ['faible', 'moyenne', 'critique']);
            $table->enum('detected_by', ['system_ia', 'agent']);
            $table->enum('status', ['open', 'investigating', 'resolved', 'dismissed'])
                ->default('open');
            $table->foreignId('reported_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('resolved_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anomalies');
    }
};