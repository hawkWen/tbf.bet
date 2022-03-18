<?php

namespace App\Console;

use App\Models\Brand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // foreach($brands as $brand) {

        //     if($brand->bot_bank == 1) {
        //         $schedule->command(BotBankCommand::class,[$brand->id])->withoutOverlapping();
        //     }

        //     if($brand->bot_deposit == 1) {
        //         $schedule->command(BotDepositCommand::class,[$brand->id])->withoutOverlapping();
        //     }

        //     if($brand->bot_withdraw == 1) {
        //         $schedule->command(BotWithdrawCommand::class,[$brand->id])->withoutOverlapping();
        //     }

        // }
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
