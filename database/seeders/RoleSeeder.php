<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Super Administrador']);
        $role2 = Role::create(['name' => 'Administrador']);

        // Companies
        Permission::create([
            'name' => 'admin.companies.index', 
            'description' => 'Ver listado de empresas'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.companies.create', 
            'description' => 'Ingresar nuevas empresas'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.companies.update', 
            'description' => 'Modificar empresas'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.companies.delete', 
            'description' => 'Eliminar empresas'
        ])->syncRoles([$role1]);

         // Accounts
         Permission::create([
            'name' => 'admin.accounts.index', 
            'description' => 'Ver listado de cuentas'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.accounts.create', 
            'description' => 'Ingresar nuevas cuentas'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.accounts.update', 
            'description' => 'Modificar cuentas'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.accounts.delete', 
            'description' => 'Eliminar cuentas'
        ])->syncRoles([$role1]);
    }
}
