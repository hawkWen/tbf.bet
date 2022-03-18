<?php

namespace App\Console\Commands;

use App\Models\BankAccount;
use Illuminate\Console\Command;

class BankAccountClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank-account:clear';

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
        $bank_accounts = BankAccount::all();

        foreach($bank_accounts as $bank_account) {
            $bank_account->update([
                'active' => 0,
            ]);
        }
    }
}
