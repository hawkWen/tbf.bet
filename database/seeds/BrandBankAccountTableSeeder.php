<?php

use Illuminate\Database\Seeder;

class BrandBankAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('brand_bank_accounts')->truncate();

        DB::table('brand_bank_accounts')->insert([
            'brand_id' => 1,
            'bank_account_id' => 1,
        ]);

        DB::table('brand_bank_accounts')->insert([
            'brand_id' => 1,
            'bank_account_id' => 2,
        ]);

        DB::table('brand_bank_accounts')->insert([
            'brand_id' => 1,
            'bank_account_id' => 3,
        ]);

        DB::table('brand_bank_accounts')->insert([
            'brand_id' => 1,
            'bank_account_id' => 4,
        ]);
    }
}
