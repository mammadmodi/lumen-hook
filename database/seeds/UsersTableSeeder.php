<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'admin',
            'email' => 'admin@depart.com',
            'phone_number' => '09121234567',
            'activation_code' => '1111',
            'password' => Hash::make("123465"),
        ]);
    }
}
