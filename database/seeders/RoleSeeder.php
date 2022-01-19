<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'change user role']);

        Role::create(['name' => 'user']);

        $role2 = Role::create(['name' => 'moderator']);
        $role2->givePermissionTo('add user');
        $role2->givePermissionTo('edit user');

        $role3 = Role::create(['name' => 'admin']);
        $role3->givePermissionTo('add user');
        $role3->givePermissionTo('edit user');
        $role3->givePermissionTo('delete user');
        $role3->givePermissionTo('change user role');

        $user = User::factory()->create([
            'login' => 'moderator'
        ]);
        $user->assignRole($role2);

        $admin = User::factory()->create([
            'login' => 'admin'
        ]);
        $admin->assignRole($role3);
    }
}
