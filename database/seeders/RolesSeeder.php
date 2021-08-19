<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'ADMINISTRADOR',
                'permissions' => [
                    'LEER USUARIO',
                    'CREAR USUARIO',
                    'EDITAR USUARIO',
                    'ELIMINAR USUARIO',
                    'LEER ROL',
                    'LEER TIPO DOCUMENTO',
                    'LEER REQUISITO',
                    'CREAR REQUISITO',
                    'EDITAR REQUISITO',
                    'ELIMINAR REQUISITO',
                    'CREAR TIPO TRÁMITE',
                    'EDITAR TIPO TRÁMITE',
                    'ELIMINAR TIPO TRÁMITE',
                ],
            ], [
                'name' => 'RECEPCIÓN',
                'permissions' => [
                    'CREAR TRÁMITE',
                    'LEER REQUISITO',
                    'LEER TRÁMITE',
                    'EDITAR TRÁMITE',
                    'ELIMINAR TRÁMITE',
                    'DERIVAR TRÁMITE',
                ],
            ], [
                'name' => 'VERIFICADOR',
                'permissions' => [
                    'LEER REQUISITO',
                    'LEER TRÁMITE',
                    'DERIVAR TRÁMITE',
                    'ARCHIVAR TRÁMITE',
                ],
            ], [
                'name' => 'SECRETARÍA',
                'permissions' => [
                    'LEER TRÁMITE',
                    'DERIVAR TRÁMITE',
                    'ARCHIVAR TRÁMITE',
                ],
            ],
        ];

        foreach($roles as $role) {
            $new_role = Role::firstOrCreate([
                'name' => $role['name'],
            ]);

            foreach($role['permissions'] as $permission) {
                $new_permission = Permission::where('name', $permission)->first();
                if (!$new_permission) {
                    $new_permission = Permission::create([
                        'name' => $permission,
                    ]);
                }
                $new_role->givePermissionTo($new_permission);
            }
        }
    }
}
