<?php

use App\Hook;
use App\HookError;
use Illuminate\Database\Seeder;

class HookErrorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Hook $hook */
        $hook = Hook::first();

        factory(HookError::class)->create([
            "hook_id" => $hook->id,
        ]);
    }
}
