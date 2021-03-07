<?php

namespace App\Console;

use App\Jobs\HookJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        DB::table('hooks')
            ->whereNull("deleted_at")
            ->orderBy("id", "DESC")
            ->chunk(20, function ($hooks) use ($schedule) {
                foreach ($hooks as $hook) {
                    $job = new HookJob($hook->url, $hook->id, $hook->threshold);
                    $schedule->job($job, "hooks", "database")->cron($hook->cron);
                }
            });
    }
}
