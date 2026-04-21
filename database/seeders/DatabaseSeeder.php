<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::updateOrCreate(['name' => 'admin'], ['name' => 'admin', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'user'], ['name' => 'user', 'guard_name' => 'web']);


        $admin = User::updateOrCreate([
            'username' => 'admin',
            'role' => 'admin'
        ], [
            'name' => 'Admin',
            'nrp' => '123456789',
            'username' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email' => 'admin@test.com'
        ]);

        // $user = User::updateOrCreate([
        //     'username' => '123456789',
        //     'role' => 'user'
        // ], [
        //     'name' => 'Yudha',
        //     'nrp' => '987654321',
        //     'username' => '123456789',
        //     'role' => 'user',
        //     'password' => Hash::make('password'),
        //     'email' => 'yudha@test.com'
        // ]);

        $admin->assignRole('admin');
        // $user->assignRole('user');
    }
}
