<?php

namespace App\Http\Controllers\Gclub;

use App\Models\Bank;
use App\Models\Brand;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRegisterRequest;

class RegisterController extends Controller
{
    //
    public function index($brand) {
    
        $brand = Brand::whereSubdomain($brand)->first();

        $banks = Bank::all();

        return view('gclub.register', \compact('brand','banks'));

    }

    public function store(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id']);

        if(isset($input['telephone'])) {

            if($input['telephone'] == '' || strlen($input['telephone']) < 10) {

                return response()->json([
                    'code' => 0,
                    'message' => 'กรุณาระบุเบอร์โทรศัพท์ 10 ตัวขึ้นไป'
                ]);

            }

        }

        if(isset($input['line_id'])) {

            if($input['line_id'] == '') {
                
                return response()->json([
                    'code' => 0,
                    'message' => 'กรุณาระบุไลน์ไอดี'
                ]);

            }

        }

        $bank_account = Customer::whereBrandId($brand->id)->whereBankAccount($input['bank_account'])->get();

        if($bank_account->count() > 0) {
                
            return response()->json([
                'code' => 0,
                'message' => 'ขออภัยค่ะ เลขที่บัญชีซ้ำในระบบ กรุณาติดต่อพนักงาน'
            ]);

        }
 
        DB::beginTransaction();

        $input['game_id'] = $brand->game_id;

        $input['status'] = 0;

        $bank = explode(':',$input['bank_id']);

        $input['bank_id'] = $bank[0];

        $input['code_bank'] = $bank[1];

        $input['name'] = $input['fname'].' '.$input['lname'];

        if($input['code_bank'] === 'SCB') {

            $input['bank_account_scb'] = substr($input['bank_account'],-4);

        } else {

            $input['bank_account_scb'] = substr($input['bank_account'],-6);

        }
    
        $input['bank_account_krungsri'] = substr($input['bank_account'],3);
    
        $input['bank_account_kbank'] = substr(substr($input['bank_account'],3),0,-1);

        Customer::create($input);

        //Rich menu Member
        // $rich_menu_id = 'richmenu-32b129ff5badb1fa7c002014a1cf1569';
        $rich_menu_id = $brand->line_menu_member;

        //Line Developer access token
        $token = $brand->line_token;

        //Line Channel secret 
        $channel_secret = $brand->line_channel_secret;

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($token);

        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channel_secret]);

        $response = $bot->linkRichMenu($input['line_user_id'], $rich_menu_id);

        // dd($input,$response);

        DB::commit();
                
        return response()->json([
            'code' => 200,
            'message' => 'สมัครสมาชิกเรียบร้อย ขอบคุณค่ะ _/\_'
        ]);
    }
}
