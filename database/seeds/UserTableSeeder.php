<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'user_role_id' => 1,
            'brand_id' => 0,
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => bcrypt('123123'),
            'last_login' => date('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'user_role_id' => 2,
            'brand_id' => 1,
            'name' => 'manager',
            'username' => 'manager',
            'password' => bcrypt('123123'),
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
}
