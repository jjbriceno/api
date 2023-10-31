<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create-music-sheet']);
        Permission::create(['name' => 'edit-music-sheet']);
        Permission::create(['name' => 'delete-music-sheet']);
        Permission::create(['name' => 'download-music-sheet']);
        Permission::create(['name' => 'view-music-sheet']);
        Permission::create(['name' => 'borrow-music-sheet']);

        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        $adminRole->givePermissionTo([
            'create-music-sheet',
            'edit-music-sheet',
            'delete-music-sheet',
            'download-music-sheet',
            'view-music-sheet',
            'borrow-music-sheet'
        ]);

        $userRole->givePermissionTo([
            'download-music-sheet',
            'view-music-sheet',
            'borrow-music-sheet'
        ]);
    }
}
