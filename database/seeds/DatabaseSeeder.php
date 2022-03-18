<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(GameTableSeeder::class);
        $this->call(BankTableSeeder::class);
        $this->call(BrandTableSeeder::class);
        $this->call(BrandBankAccountTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
    }
}
