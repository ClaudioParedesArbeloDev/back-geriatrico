<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_studies', function (Blueprint $table) {
            
            $table->string('file_path')
                ->nullable()
                ->after('conclusion');

            
            $table->text('conclusion')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('medical_studies', function (Blueprint $table) {
            $table->dropColumn('file_path');

            $table->text('conclusion')
                ->nullable(false)
                ->change();
        });
    }
};
