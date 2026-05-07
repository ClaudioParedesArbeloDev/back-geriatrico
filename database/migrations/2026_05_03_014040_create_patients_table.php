<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');

            $table->string('dni')->unique();

            $table->date('birth_date');

            $table->enum('gender', ['male', 'female', 'other']);

            $table->string('blood_type')->nullable();

            $table->date('admission_date');

            $table->enum('mobility_status', [
                'normal',
                'reduced',
                'wheelchair',
                'bedridden',
            ])->default('normal');

            $table->enum('dependency_level', [
                'low',
                'medium',
                'high',
            ])->default('low');

            $table->string('emergency_phone')->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'deceased',
            ])->default('active');

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
