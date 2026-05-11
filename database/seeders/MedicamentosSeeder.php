<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('medications')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now   = now();
        $total = 0;

       
        $jsonPath = storage_path('app/vademecum-ospsa.json');

        if (file_exists($jsonPath)) {
            $registros = json_decode(file_get_contents($jsonPath), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $inserts = [];

                foreach ($registros as $row) {
                    $inserts[] = [
                        'code'                 => null,
                        'nombre_comercial'     => $row['nombre_comercial']     ?? '',
                        'presentacion'         => $row['presentacion']         ?? null,
                        'accion_farmacologica' => $row['accion_farmacologica'] ?? null,
                        'principio_activo'     => $row['principio_activo']     ?? null,
                        'laboratorio'          => $row['laboratorio']          ?? null,
                        'porcentaje'           => (int) ($row['porcentaje']    ?? 40),
                        'seccion'              => $row['seccion']              ?? 'SANIDAD',
                        'created_at'           => $now,
                        'updated_at'           => $now,
                    ];

                    if (count($inserts) >= 500) {
                        DB::table('medications')->insert($inserts);
                        $total  += count($inserts);
                        $inserts = [];
                        $this->command->getOutput()->write('.');
                    }
                }

                if (! empty($inserts)) {
                    DB::table('medications')->insert($inserts);
                    $total += count($inserts);
                }

                $this->command->newLine();
                $this->command->info("✅ Vademecum OSPSA: " . count($registros) . " registros");
            } else {
                $this->command->warn("⚠️  JSON inválido: storage/app/vademecum-ospsa.json");
            }
        } else {
            $this->command->warn("⚠️  No encontrado: storage/app/vademecum-ospsa.json");
        }

        
        $csvPath = storage_path('app/drugs.csv');

        if (file_exists($csvPath)) {
            $handle = fopen($csvPath, 'r');

           
            $headers = fgetcsv($handle, 0, ';');
            $headers = array_map(fn($h) => trim($h, " \t\n\r\0\x0B\""), $headers);

            $inserts  = [];
            $csvCount = 0;

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count($row) < count($headers)) continue;

                $data = array_combine($headers, $row);

                $inserts[] = [
                    'code'                 => trim($data['code']         ?? ''),
                    'nombre_comercial'     => trim($data['name']         ?? ''),
                    'presentacion'         => trim($data['presentation'] ?? '') ?: null,
                    'accion_farmacologica' => null,
                    'principio_activo'     => trim($data['generic_name'] ?? '') ?: null,
                    'laboratorio'          => trim($data['laboratory']   ?? '') ?: null,
                    'porcentaje'           => 70,      
                    'seccion'              => 'SANIDAD',
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ];

                $csvCount++;

                if (count($inserts) >= 500) {
                    DB::table('medications')->insert($inserts);
                    $total  += count($inserts);
                    $inserts = [];
                    $this->command->getOutput()->write('.');
                }
            }

            if (! empty($inserts)) {
                DB::table('medications')->insert($inserts);
                $total += count($inserts);
            }

            fclose($handle);

            $this->command->newLine();
            $this->command->info("✅ CSV drogas: {$csvCount} registros");
        } else {
            $this->command->warn("⚠️  No encontrado: storage/app/drugs_cvs.csv");
        }

       
        $this->command->newLine();
        $this->command->info("📦 Total importado: {$total} medicamentos");
    }
}