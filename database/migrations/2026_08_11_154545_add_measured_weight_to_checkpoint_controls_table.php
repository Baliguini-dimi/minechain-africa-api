<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checkpoint_controls', function (Blueprint $table) {
            $table->decimal('measured_weight', 15, 3)->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('checkpoint_controls', function (Blueprint $table) {
            $table->dropColumn('measured_weight');
        });
    }
};