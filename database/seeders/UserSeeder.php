<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::create([
            'name' => 'Admin KPPN',
            'email' => 'adminkppn@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Staff KPPN',
            'email' => 'staffkppn@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name'       => 'User KPPN',
            'email'      => 'userkppn@gmail.com',
            'password'   => Hash::make('password'),
            'nip'        => '123456789',
            'no_hp'      => '081234567890',      
            'jabatan'    => 'Staf Pelaksana',           
            'nama_satker'=> 'KANTOR PELAYANAN PERBENDAHARAAN NEGARA YOGYAKARTA', 
            'role'       => 'user',
        ]);
    
    }
}

