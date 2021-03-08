<?php

use App\Hook;
use App\User;
use Illuminate\Database\Seeder;

class HooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $adminUser */
        $adminUser = User::first();

        factory(Hook::class)->create([
            "user_id" => $adminUser->id,
        ]);
    }
}
