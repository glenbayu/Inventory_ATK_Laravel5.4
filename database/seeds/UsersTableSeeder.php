<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Akun ADMIN
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@pabrik.com',
            'password' => bcrypt('password'), // Passwordnya 'password'
            'role' => 'admin',
            'department' => 'GA'
        ]);

        // 2. Buat Akun USER BIASA (Staff)
        User::create([
            'name' => 'Budi Staff',
            'email' => 'budi@pabrik.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'department' => 'Production',
        ]);
        User::create([
            'name' => 'Ibud Staff',
            'email' => 'ibud@pabrik.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'department' => 'Office IT'
        ]);
    }
}
