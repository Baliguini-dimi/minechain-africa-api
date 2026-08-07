<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')
                ->unique()
                ->constrained('lots')
                ->cascadeOnDelete();
            $table->string('code_value')->unique();
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};