<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('patient_contacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();
            
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('dni')->unique()->nullable();
            $table->string('relationship')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_contacts');
    }
};
