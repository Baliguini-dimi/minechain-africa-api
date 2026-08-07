<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')
                ->nullable()
                ->after('id')
                ->constrained('organizations')
                ->nullOnDelete();

            $table->foreignId('role_id')
                ->after('organization_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->string('phone')->nullable()->after('email');
            $table->string('two_factor_secret')->nullable()->after('password');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_secret');
            $table->enum('status', ['active', 'suspended', 'invited'])
                ->default('invited')
                ->after('two_factor_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['phone', 'two_factor_secret', 'two_factor_enabled', 'status']);
        });
    }
};