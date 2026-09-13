<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Menu;
use App\Models\MenuAccess;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Company::create([
            'company_name' => 'Optic Grand'
        ]);
        Role::create([
            'name' => 'Superadmin'
        ]);
        User::create([
            'company_id' => 1,
            'role_id' => 1,
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('superadmin')
        ]);
        Menu::create([
            'level' => 1,
            'parent_id' => NULL,
            'name' => 'Settings',
            'icon' => '',
            'url' => '',
        ]);
        Menu::create([
            'level' => 2,
            'parent_id' => 2,
            'name' => 'Menus',
            'icon' => 'ti ti-menu',
            'url' => 'settings/menus',
        ]);
        Menu::create([
            'level' => 2,
            'parent_id' => 1,
            'name' => 'Users',
            'icon' => 'ti ti-users',
            'url' => NULL,
        ]);
        Menu::create([
            'level' => 3,
            'parent_id' => 3,
            'name' => 'User',
            'icon' => 'ti ti-circle',
            'url' => 'settings/users/user',
        ]);
        Menu::create([
            'level' => 3,
            'parent_id' => 3,
            'name' => 'User',
            'icon' => 'ti ti-circle',
            'url' => 'settings/users/user',
        ]);
        MenuAccess::create([
            'user_id' => 1,
            'menu_id' => 1
        ]);
        MenuAccess::create([
            'user_id' => 1,
            'menu_id' => 2
        ]);
        MenuAccess::create([
            'user_id' => 1,
            'menu_id' => 3
        ]);
        MenuAccess::create([
            'user_id' => 1,
            'menu_id' => 4
        ]);
        MenuAccess::create([
            'user_id' => 1,
            'menu_id' => 5
        ]);
    }
}
