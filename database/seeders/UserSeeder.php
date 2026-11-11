<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun superadmin utama
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@gmail.com',
                'password' => bcrypt('superadmin123'),
                'role'     => 'superadmin',
            ]
        );

        // Akun admin biasa (tidak bisa tambah/hapus akun)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
                'role'     => 'admin',
            ]
        );
    }
}