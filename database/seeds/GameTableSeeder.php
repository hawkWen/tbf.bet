<?php

use Illuminate\Database\Seeder;

class GameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('games')->truncate();

        DB::table('games')->insert([
            'name' => 'G-Club',
            'logo' => 'games/8pFQ4HJPOFqYGkgIAi5OXKESXMkfeU3hAKZOPcrs.png',
            'logo_url' => '/storage/games/8pFQ4HJPOFqYGkgIAi5OXKESXMkfeU3hAKZOPcrs.png'
        ]);

        DB::table('games')->insert([
            'name' => 'UFAbet',
            'logo' => 'games/7yaQCzt2YOgAgfrUKh9NQp2DNPTLlzQBKLRMG3wD.png',
            'logo_url' => '/storage/games/7yaQCzt2YOgAgfrUKh9NQp2DNPTLlzQBKLRMG3wD.png'
        ]);
    }
}
