<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_bed_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('bed_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('assigned_at');

            $table->timestamp('released_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'patient_bed_assignments'
        );
    }
};