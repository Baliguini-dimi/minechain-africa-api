<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country');
            $table->text('address')->nullable();
            $table->jsonb('contacts')->nullable();
            $table->string('logo_url')->nullable();
            $table->jsonb('admin_documents')->nullable();
            $table->enum('status', ['active', 'suspended', 'pending_validation'])
                ->default('pending_validation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};