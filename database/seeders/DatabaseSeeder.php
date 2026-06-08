<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Privilegios
        $privilegios = [
            ['nombre' => 'REGISTRAR_POSTULANTE', 'descripcion' => 'Permite registrar nuevos postulantes en el sistema'],
            ['nombre' => 'MODIFICAR_POSTULANTE', 'descripcion' => 'Permite modificar datos de postulantes'],
            ['nombre' => 'ELIMINAR_POSTULANTE', 'descripcion' => 'Permite eliminar/dar de baja postulantes'],
            ['nombre' => 'REGISTRAR_PAGO', 'descripcion' => 'Permite cobrar y registrar transacciones de pago'],
            ['nombre' => 'GESTIONAR_ROLES', 'descripcion' => 'Permite crear, modificar y borrar roles'],
            ['nombre' => 'ASIGNAR_PRIVILEGIOS', 'descripcion' => 'Permite asignar y revocar privilegios a roles'],
            ['nombre' => 'VER_AUDITORIA', 'descripcion' => 'Permite ver los logs de auditoría/bitácora'],
        ];

        $privsCreados = [];
        foreach ($privilegios as $priv) {
            $privsCreados[] = \App\Models\Privilegio::create($priv);
        }

        // 2. Crear Roles
        $adminRol = \App\Models\Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Superusuario del sistema con acceso total'
        ]);

        \App\Models\Rol::create([
            'nombre' => 'Secretaria',
            'descripcion' => 'Usuario encargado del registro y cobro'
        ]);

        \App\Models\Rol::create([
            'nombre' => 'Postulante',
            'descripcion' => 'Usuario postulante con acceso a notas'
        ]);

        // 3. Asignar todos los privilegios al rol Administrador
        $adminRol->privilegios()->attach(
            array_map(fn($p) => $p->id, $privsCreados)
        );

        // 4. Crear un usuario administrador de prueba
        User::factory()->create([
            'name' => 'Usuario Admin',
            'email' => 'admin@cup.edu.bo',
            'password' => bcrypt('password123'),
        ]);
    }
}
