<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medications', function (Blueprint $table) {
           
            $table->string('concentration')
                ->nullable()
                ->after('presentation');

            
            $table->enum('drug_form', [
                'tablet',
                'capsule',
                'syrup',
                'injectable',
                'drops',
                'cream',
                'patch',
                'suppository',
                'inhaler',
                'other',
            ])->nullable()->after('concentration');

            
            $table->text('contraindications')
                ->nullable()
                ->after('drug_form');

            
            $table->boolean('controlled')
                ->default(false)
                ->after('contraindications');
        });
    }

    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn([
                'concentration',
                'drug_form',
                'contraindications',
                'controlled',
            ]);
        });
    }
};
