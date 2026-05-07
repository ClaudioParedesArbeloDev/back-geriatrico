<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_allergies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('allergy_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('severity', [
                'mild',
                'moderate',
                'severe',
            ])->default('mild');

            $table->text('reaction')->nullable();

            $table->timestamps();

            $table->unique(['patient_id', 'allergy_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_allergies');
    }
};
