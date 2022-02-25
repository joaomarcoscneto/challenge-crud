<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'id' => 1,
            'name' => 'User Test',
            'email' => 'usertest@dominioexample.com',
            'city' => 'Montes Claros',
            'state' => 'Minas Gerais',
            'password' => Hash::make('123456')
        ]);
    }
}
