<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class WheelResetRewardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wheel:reset-reward';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $customers = Customer::where('wheel_amount','>',0)->get();

        foreach($customers as $customer) {

            $customer->update([
                'wheel_amount' => 0
            ]);

        }

    }
}
