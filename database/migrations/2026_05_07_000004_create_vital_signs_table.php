<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            
            $table->unsignedSmallInteger('blood_pressure_systolic')->nullable();
            $table->unsignedSmallInteger('blood_pressure_diastolic')->nullable();

           
            $table->unsignedSmallInteger('heart_rate')->nullable();

            
            $table->decimal('temperature', 4, 1)->nullable();

            
            $table->decimal('oxygen_saturation', 4, 1)->nullable();

            
            $table->unsignedSmallInteger('blood_glucose')->nullable();

           
            $table->decimal('weight', 5, 2)->nullable();

            
            $table->unsignedSmallInteger('respiratory_rate')->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('recorded_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
