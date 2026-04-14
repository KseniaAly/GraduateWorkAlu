<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'fio' => 'Алышева Ксения',
                'email' => 'ksyusha.alysheva@gmail.com',
                'role' => 'admin',
                'phone' => '+79588364985',
                'password' => Hash::make('12345678'),
                'avatar' => '/images/default.png'
            ],
        ];
        foreach ($admins as $admin) {
            User::create($admin);
        }
    }
}
