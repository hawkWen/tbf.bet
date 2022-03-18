<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Helpers\LineApi;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineController extends Controller
{
    //
    public function index($subdomain) {

        $brand = Brand::whereSubdomain($subdomain)->first();

        return view('line.index', compact('brand'));

    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if($input['username'] == null ) {
            return response()->json(['status' => false, 'message' => ' ❗️ กรุณาระบุไอดีเข้าเกมส์']);
        }

        $customer = Customer::whereUsername($input['username'])->first();

        if(!$customer ) {
            return response()->json(['status' => false, 'message' => ' ❌ ไม่พบไอดีนี้ในระบบค่ะ']);
        }

        // if($customer->line_user_id != null) {
        //     return response()->json(['status' => false, 'message' => ' ❗️ คุณได้เชื่อมต่อบัญชี LINE เรียบร้อยแล้ว']);
        // }
        
        $customer->update([
            'line_user_id' => $input['line_user_id'],
            'img_url' => $input['picture']
        ]);

        $brand = Brand::find($customer->brand_id);

        $rich_menu_id = $brand->line_menu_member;

        $token = $brand->line_token;

        $channel_secret = $brand->line_channel_secret;

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($token);

        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channel_secret]);

        $response = $bot->linkRichMenu($input['line_user_id'], $rich_menu_id);

        $line_api = new LineApi();
                
        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $message1 = "ขอบคุณที่กดปุ่มกระดิ่งแจ้งเตือนกับเรานะคะ \n";

        $message1 .= "Username: ".$customer->username." \n";

        $message1 .= "Password: ".$customer->password_generate." \n";

        $message1 .= "กดเติมเงินได้ที่เมนูเติมเงินเลยนะคะ \n";

        if($brand->game_id == 5) {

            $message1 .= 'ทางเข้าเล่น: https://fastbet98.com/#/';

        }
    
        $push = $line_api->pushMessage($customer->line_user_id, $message1);

        DB::commit();

        return response()->json(['status' => true, 'message' => ' ✅ เชื่อมต่อกับบัญชี LINE เรียบร้อยขอบคุณค่ะ']);

    }
}
