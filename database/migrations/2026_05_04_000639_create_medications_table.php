<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable();
            $table->string('nombre_comercial', 250);
            $table->string('presentacion', 250)->nullable();
            $table->string('accion_farmacologica', 250)->nullable();
            $table->string('principio_activo', 250)->nullable();
            $table->string('laboratorio', 150)->nullable();
            $table->tinyInteger('porcentaje')->unsigned()->default(40);
            $table->string('seccion', 100)->default('SANIDAD');
            $table->timestamps();

            $table->index('code');
            $table->index('nombre_comercial');
            $table->index('principio_activo');
            $table->index('laboratorio');
            $table->index('porcentaje');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};