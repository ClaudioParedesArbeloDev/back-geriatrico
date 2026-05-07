<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medical_prescription_id')
                ->constrained()
                ->cascadeOnDelete();

            
            $table->time('scheduled_time');

            
            $table->string('label')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_schedules');
    }
};
