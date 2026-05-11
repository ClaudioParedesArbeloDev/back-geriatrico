<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Cie10CodesSeeder extends Seeder
{
    public function run()
    {
        DB::table('cie10_codes')->truncate();

        $filePath = storage_path('app/cie10-argentina.csv'); 

        if (!file_exists($filePath)) {
            $this->command->error("❌ Archivo no encontrado en: " . $filePath);
            $this->command->info("Descárgalo aquí: https://datos.ciudaddecorrientes.gov.ar/dataset/1d7319fa-cd26-4d14-9ecc-4732a8b00648/resource/2f0583e8-68ae-4714-973b-0fbcb4bac215/download/tabla-salud-id_cie10.csv");
            return;
        }

        $data = array_map('str_getcsv', file($filePath));
        $inserts = [];
        $count = 0;

        
        foreach ($data as $index => $row) {
            if ($index < 2) continue;

            if (count($row) < 2) continue;

            $code = trim($row[0] ?? '');          
            $description = trim($row[2] ?? '');   

            if (empty($code) || empty($description)) continue;

            $inserts[] = [
                'code'        => $code,
                'description' => $description,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            $count++;

            if (count($inserts) >= 500) {
                DB::table('cie10_codes')->insert($inserts);
                $inserts = [];
            }
        }

        if (count($inserts) > 0) {
            DB::table('cie10_codes')->insert($inserts);
        }

        $this->command->info("✅ Se importaron {$count} códigos CIE-10 correctamente.");
    }
}