<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['email' => 'direktur@peroniks.com', 'role' => 'direktur'],
            ['email' => 'mr@peroniks.com', 'role' => 'mr'],
            ['email' => 'adminppic@peroniks.com', 'role' => 'adminppic'],
            ['email' => 'adminqcfitting@peroniks.com', 'role' => 'adminqcfitting'],
            ['email' => 'adminqcflange@peroniks.com', 'role' => 'adminqcflange'],
            ['email' => 'qcinspektorpd@peroniks.com', 'role' => 'qcinspektorpd'],
            ['email' => 'qcinspectorfl@peroniks.com', 'role' => 'qcinspectorfl'],
            ['email' => 'admink3@peroniks.com', 'role' => 'admink3'],
            ['email' => 'sales@peroniks.com', 'role' => 'sales'],
        ];

        foreach ($users as $user) {
            \App\Models\User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => ucfirst($user['role']),
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => $user['role'],
                ]
            );
        }
    }
}
