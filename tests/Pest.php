<?php

use App\Models\Bed;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

// -----------------------------------------------------------------------
// Helpers globales disponibles en todos los tests
// -----------------------------------------------------------------------

/**
 * Crea un usuario con el rol indicado y lo devuelve listo para actingAs().
 */
function userWithRole(string $roleName): User
{
    $role = Role::firstOrCreate(
        ['name' => $roleName],
        ['display_name' => ucfirst(str_replace('_', ' ', $roleName))]
    );

    $user = User::factory()->create();
    $user->roles()->attach($role);

    return $user;
}

/**
 * Crea todos los roles del sistema (útil para tests de permisos).
 */
function seedRoles(): void
{
    $roles = [
        ['name' => 'admin',         'display_name' => 'Administrador'],
        ['name' => 'doctor',        'display_name' => 'Médico'],
        ['name' => 'nurse',         'display_name' => 'Enfermero/a'],
        ['name' => 'kinesiologist', 'display_name' => 'Kinesiólogo/a'],
        ['name' => 'nutritionist',  'display_name' => 'Nutricionista'],
        ['name' => 'social_worker', 'display_name' => 'Trabajador/a Social'],
    ];

    foreach ($roles as $role) {
        Role::firstOrCreate(['name' => $role['name']], $role);
    }
}

/**
 * Crea un paciente con datos mínimos válidos.
 */
function createPatient(array $overrides = []): Patient
{
    return Patient::create(array_merge([
        'first_name'       => 'Ana',
        'last_name'        => 'García',
        'dni'              => fake()->unique()->numerify('########'),
        'birth_date'       => '1945-03-10',
        'gender'           => 'female',
        'admission_date'   => now()->toDateString(),
        'mobility_status'  => 'normal',
        'dependency_level' => 'low',
        'status'           => 'active',
    ], $overrides));
}

/**
 * Crea un medicamento válido en el vademecum.
 */
function createMedication(array $overrides = []): Medication
{
    return Medication::create(array_merge([
        'name'         => fake()->word() . ' ' . fake()->numerify('###mg'),
        'generic_name' => fake()->word(),
        'controlled'   => false,
    ], $overrides));
}

/**
 * Crea una habitación con una cama disponible y devuelve la cama.
 */
function createAvailableBed(): Bed
{
    $room = Room::create([
        'number'   => fake()->unique()->numerify('##'),
        'capacity' => 4,
        'type'     => 'shared',
        'status'   => 'available',
    ]);

    return Bed::create([
        'room_id'    => $room->id,
        'bed_number' => '1',
        'status'     => 'available',
    ]);
}
