<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'user_username' => 'admin',
            'user_password' => Hash::make('password123'),
            'user_fullname' => 'Administrator Thrift',
            'user_email' => 'admin@thrift.com',
            'user_nohp' => '081234567890',
            'user_alamat' => 'Kota Malang',
            'user_profil_url' => 'url_placeholder_profil',
            'user_level' => 'Admin',
        ]);

        // 2. Akun Pengguna / Customer
        User::create([
            'user_username' => 'buyer',
            'user_password' => Hash::make('password123'),
            'user_fullname' => 'Pembeli Thrift',
            'user_email' => 'buyer@gmail.com',
            'user_nohp' => '089876543210',
            'user_alamat' => 'Lowokwaru, Kota Malang',
            'user_profil_url' => 'url_placeholder_profil',
            'user_level' => 'Pengguna',
        ]);
    }
}
