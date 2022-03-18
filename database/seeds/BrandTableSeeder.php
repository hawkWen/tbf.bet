<?php

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('brands')->truncate();

        Brand::create( [
            'id' => 1,
            'game_id' => 1,
            'logo' => 'brands/wtVa8xazJuEDfWaXsnItxtMNwPShGz69RCKyDvnD.png',
            'logo_url' => '/storage/brands/wtVa8xazJuEDfWaXsnItxtMNwPShGz69RCKyDvnD.png',
            'name' => 'Gclub88',
            'line_id' => 'gclub88',
            'website' => NULL,
            'telephone' => '087-4379405',
            'subdomain' => 'gclub88',
            'agent_username' => 'gclub88',
            'agent_password' => '123123++',
            'agent_credit' => 0.00,
            'agent_member_value' => NULL,
            'cost_service' => 10000.00,
            'cost_working' => 1.00,
            'deposit_min' => 100.00,
            'withdraw_min' => 300.00,
            'withdraw_auto_max' => 10000.00,
            'stock' => 0.00,
            'status_telephone' => 1,
            'created_at' => '2020-07-15 16:43:51',
            'updated_at' => '2020-07-15 16:43:51'
        ]);
    }
}
