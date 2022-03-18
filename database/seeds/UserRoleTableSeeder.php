<?php

use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('user_roles')->truncate();

        DB::table('user_roles')->insert([
            'name' => 'Administrator',
        ]);

        DB::table('user_roles')->insert([
            'name' => 'Manager',
        ]);

        DB::table('user_roles')->insert([
            'name' => 'Customer Service',
        ]);

        DB::table('user_roles')->insert([
            'name' => 'Agent',
        ]);
    }
}
