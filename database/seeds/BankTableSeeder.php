<?php

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('banks')->truncate();

        Bank::create( [
            'id'=>1,
            'logo'=>'images/logo-banks/scb.png',
            'code'=>'SCB',
            'name'=>'ธนาคารไทยพาณิชย์',
            'bg_color'=>'#4E2E7E',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>2,
            'logo'=>'images/logo-banks/bbl.png',
            'code'=>'BBL',
            'name'=>'ธนาคารกรุงเทพ',
            'bg_color'=>'#1E4598',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>3,
            'logo'=>'images/logo-banks/ktb.png',
            'code'=>'KTB',
            'name'=>'ธนาคารกรุงไทย',
            'bg_color'=>'#18A5E1',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>4,
            'logo'=>'images/logo-banks/kbank.png',
            'code'=>'KBANK',
            'name'=>'ธนาคารกสิกรไทย',
            'bg_color'=>'#148F2D',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>5,
            'logo'=>'images/logo-banks/bay.png',
            'code'=>'BAY',
            'name'=>'ธนาคารกรุงศรีอยุธยา',
            'bg_color'=>'#FEC43C',
            'font_color'=>'#000000'
        ] );
                    
        Bank::create( [
            'id'=>6,
            'logo'=>'images/logo-banks/cimb.png',
            'code'=>'CIMB',
            'name'=>'ธนาคารซีไอเอ็มบี',
            'bg_color'=>'#FD0C1A',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>13,
            'logo'=>'images/logo-banks/nbank.png',
            'code'=>'NBANK',
            'name'=>'ธนาคารธนชาต',
            'bg_color'=>'#FB4E1F',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>7,
            'logo'=>'images/logo-banks/tmb.png',
            'code'=>'TMB',
            'name'=>'ธนาคารทหารไทย',
            'bg_color'=>'#1278BE',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>8,
            'logo'=>'images/logo-banks/nbank.png',
            'code'=>'NBANK',
            'name'=>'ธนาคารธนชาต',
            'bg_color'=>'#FB4E1F',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>9,
            'logo'=>'images/logo-banks/uob.png',
            'code'=>'UOB',
            'name'=>'ธนาคารยูโอบี',
            'bg_color'=>'#093979',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>10,
            'logo'=>'images/logo-banks/gsb.png',
            'code'=>'GSB',
            'name'=>'ธนาคารออมสิน',
            'bg_color'=>'#EB188D',
            'font_color'=>'#FFFCFB'
        ] );
                    
        Bank::create( [
            'id'=>11,
            'logo'=>'images/logo-banks/ghb.png',
            'code'=>'GHB',
            'name'=>'ธนาคารอาคารสงเคราะห์'
        ] );
                    
        Bank::create( [
            'id'=>12,
            'logo'=>'images/logo-banks/tks.png',
            'code'=>'TKS',
            'name'=>'ธนาคารเพื่อนการเกษตรและสหกรณ์'
        ] );
    }
}
