<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_evolutions', function (Blueprint $table) {
            
            $table->enum('type', [
                'medical',      
                'nursing',      
                'kinesiology', 
                'nutrition',    
                'social',        
                'general',       
            ])->default('general')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('patient_evolutions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
