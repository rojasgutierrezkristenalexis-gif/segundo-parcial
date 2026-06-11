<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use App\Models\Privilegio;
use App\Models\Carrera;
use App\Models\Bitacora;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Carreras
        $carreras = [
            ['id' => 1, 'nombre' => 'Ingeniería Informática', 'cupo' => 80],
            ['id' => 2, 'nombre' => 'Ingeniería de Sistemas', 'cupo' => 100],
            ['id' => 3, 'nombre' => 'Ingeniería en Redes y Telecomunicaciones', 'cupo' => 60],
        ];

        foreach ($carreras as $c) {
            Carrera::updateOrCreate(['id' => $c['id']], [
                'nombre' => $c['nombre'],
                'cupo' => $c['cupo'],
            ]);
        }

        // 2. Seed Privilegios
        $privilegiosList = [
            ['nombre' => 'gestionar_usuarios', 'descripcion' => 'Permite administrar usuarios, roles y privilegios'],
            ['nombre' => 'configurar_cupos', 'descripcion' => 'Permite configurar los cupos por carrera'],
            ['nombre' => 'registrar_postulantes', 'descripcion' => 'Permite registrar, editar y dar de baja postulantes'],
            ['nombre' => 'registrar_pagos', 'descripcion' => 'Permite registrar y validar pagos de postulantes'],
            ['nombre' => 'gestionar_notas', 'descripcion' => 'Permite registrar y subir notas'],
            ['nombre' => 'gestionar_grupos', 'descripcion' => 'Permite organizar y ver grupos del curso'],
            ['nombre' => 'contratar_docentes', 'descripcion' => 'Permite registrar e interactuar con contratos de docentes'],
            ['nombre' => 'consultar_bitacora', 'descripcion' => 'Permite auditar la bitácora de acciones del sistema'],
            ['nombre' => 'consultar_reportes', 'descripcion' => 'Permite generar y visualizar reportes en PDF/Excel'],
            ['nombre' => 'ver_dashboard', 'descripcion' => 'Permite acceder al panel estadístico principal'],
            ['nombre' => 'ver_horarios', 'descripcion' => 'Permite al docente consultar su carga horaria asignada'],
            ['nombre' => 'registrar_asistencia', 'descripcion' => 'Permite al docente marcar asistencia en clase'],
        ];

        $privs = [];
        foreach ($privilegiosList as $p) {
            $privs[$p['nombre']] = Privilegio::updateOrCreate(['nombre' => $p['nombre']], [
                'descripcion' => $p['descripcion']
            ]);
        }

        // 3. Seed Roles
        $rolesData = [
            'Administrador' => [
                'descripcion' => 'Acceso total al sistema de admisión',
                'privilegios' => array_keys($privs) // Todos los privilegios
            ],
            'Docente' => [
                'descripcion' => 'Imparte clases, ve horarios y marca asistencia',
                'privilegios' => ['ver_horarios', 'registrar_asistencia']
            ],
            'Coordinador' => [
                'descripcion' => 'Monitorea avance, grupos y genera reportes',
                'privilegios' => ['consultar_reportes', 'gestionar_grupos', 'ver_dashboard']
            ],
            'Autoridad' => [
                'descripcion' => 'Acceso de solo consulta gerencial y reportes',
                'privilegios' => ['ver_dashboard', 'consultar_reportes']
            ]
        ];

        $roles = [];
        foreach ($rolesData as $rolNombre => $data) {
            $rol = Rol::updateOrCreate(['nombre' => $rolNombre], [
                'descripcion' => $data['descripcion']
            ]);
            $roles[$rolNombre] = $rol;

            // Sincronizar privilegios
            $privIds = [];
            foreach ($data['privilegios'] as $pn) {
                if (isset($privs[$pn])) {
                    $privIds[] = $privs[$pn]->id;
                }
            }
            $rol->privilegios()->sync($privIds);
        }

        // 4. Seed Users
        $usersData = [
            [
                'name' => 'Administrador Principal',
                'email' => 'admin@admin.com',
                'rol' => 'Administrador'
            ],
            [
                'name' => 'Docente Preu',
                'email' => 'docente@admin.com',
                'rol' => 'Docente'
            ],
            [
                'name' => 'Coordinador Admisiones',
                'email' => 'coordinador@admin.com',
                'rol' => 'Coordinador'
            ],
            [
                'name' => 'Autoridad Directiva',
                'email' => 'autoridad@admin.com',
                'rol' => 'Autoridad'
            ]
        ];

        foreach ($usersData as $ud) {
            User::updateOrCreate(
                ['email' => $ud['email']],
                [
                    'name' => $ud['name'],
                    'password' => Hash::make('12345678'),
                    'rol_id' => $roles[$ud['rol']]->id
                ]
            );
        }

        // 5. Sembrar un registro inicial en la Bitácora
        Bitacora::updateOrCreate(
            ['accion' => 'Inicialización del Sistema'],
            [
                'usuario' => 'sistema@admin.com',
                'detalle' => 'Carreras, roles, privilegios y usuarios de prueba sembrados correctamente.'
            ]
        );
    }
}
