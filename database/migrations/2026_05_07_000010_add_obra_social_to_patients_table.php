<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('obra_social')
                ->nullable()
                ->after('emergency_phone');

            $table->string('numero_afiliado')
                ->nullable()
                ->after('obra_social');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['obra_social', 'numero_afiliado']);
        });
    }
};
