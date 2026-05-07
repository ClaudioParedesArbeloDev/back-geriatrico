<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_diagnoses', function (Blueprint $table) {
            
            $table->string('cie10_code', 10)
                ->nullable()
                ->after('diagnosis');

            
            $table->string('cie10_label')
                ->nullable()
                ->after('cie10_code');
        });
    }

    public function down(): void
    {
        Schema::table('patient_diagnoses', function (Blueprint $table) {
            $table->dropColumn(['cie10_code', 'cie10_label']);
        });
    }
};
