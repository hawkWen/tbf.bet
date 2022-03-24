<?php

namespace App\Http\Controllers\Support;

use App\Models\Game;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\Customer;
use App\Models\BrandRank;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\BrandBankAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    //
    public function index() {

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $games = Game::all();

        $bank_accounts = BankAccount::all();

        return view('support.brands.index', \compact('brands','games','bank_accounts'));

    }

    public function store(BrandRequest $request) {

        $input = $request->all();

        DB::beginTransaction();

        if($input['logo'] !== 'null') {

            //put new image 
            $api_upload = Helper::uploadApiService('logo', 'casinoauto.logo');

            $input['logo_url'] = $api_upload['data']['url'];

            $input['logo'] = $api_upload['data']['name'];

        } else {

            $input['logo_url'] = 'https://via.placeholder.com/150';

            $input['logo'] = '';

        }

        $input['code_sms'] = strtolower(Helper::generateCode());

        // $input['cost_service'] = str_replace(',','',$input['cost_service']);

        // $input['cost_working'] = str_replace(',','',$input['cost_working']);

        // $input['deposit_min'] = str_replace(',','',$input['deposit_min']);

        // $input['withdraw_min'] = str_replace(',','',$input['withdraw_min']);

        // $input['withdraw_auto_max'] = str_replace(',','',$input['withdraw_auto_max']);

        $input['status_telephone'] = 1;

        $input['status_line_id'] = 1;

        $brand = Brand::create($input);

        $ranks = [
            'bronze',
            'silver',
            'gold',
            'platinum',
            'diamond',
            'conqueror'
        ];

        foreach($ranks as $rank) {

            BrandRank::create([
                'brand_id' => $brand->id,
                'rank' => $rank,
                'min' => 0,
                'reward' => 0,
                'description' => '',
            ]);

        }

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มแบรนด์สำเร็จ');

        return \redirect()->back();

    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        if(isset($input['logo'])) {

            //put new image 
            $api_upload = Helper::uploadApiService('logo', 'casinoauto.logo');

            $input['logo_url'] = $api_upload['data']['url'];

            $input['logo'] = $api_upload['data']['name'];

        } else {

            $input['logo'] = $brand->logo;

            $input['logo_url'] = $brand->logo_url;

        }

        $input['cost_service'] = str_replace(',','',$input['cost_service']);

        $input['cost_working'] = str_replace(',','',$input['cost_working']);

        $input['deposit_min'] = str_replace(',','',$input['deposit_min']);

        $input['withdraw_min'] = str_replace(',','',$input['withdraw_min']);

        $input['withdraw_auto_max'] = str_replace(',','',$input['withdraw_auto_max']);

        $input['status_telephone'] = (isset($input['status_telephone'])) ? '1' : '0';

        $input['status_line_id'] = (isset($input['status_line_id'])) ? '1' : '0';

        $brand->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขแบรนด์สำเร็จ');

        return \redirect()->back();

    }

    public function delete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        // Storage::disk('public')->delete($brand->logo);

        $brand->delete();

        DB::commit();

        \Session::flash('alert-warning', 'ลบแบรนด์สำเร็จ');

        return \redirect()->back();

    }

    public function updateRichMenuMember(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        $customers = Customer::whereBrandId($brand->id)->whereNotNull('line_user_id')->get();

        if($customers->count() > 0) {

            $line_api = new LineApi();

            $line_api->token = $brand->line_token;

            $line_api->channel_secret = $brand->line_channel_secret;

            $unlink_rich_menu = $line_api->unlinkRichMenu($customers->pluck('line_user_id')->toArray());

            $link_rich_menu = $line_api->linkRichMenu($customers->slice(0,500)->pluck('line_user_id')->toArray(),$input['line_menu_member']);

            $link_rich_menu = $line_api->linkRichMenu($customers->slice(501,1000)->pluck('line_user_id')->toArray(),$input['line_menu_member']);

            $link_rich_menu = $line_api->linkRichMenu($customers->slice(1001,1500)->pluck('line_user_id')->toArray(),$input['line_menu_member']);


            $brand->update($input);

        }

        DB::commit();

        if(isset($link_rich_menu['message']) || isset($unlink_rich_menu['message'])) {

            \Session::flash('alert-warning', $link_rich_menu['message'].' '.$unlink_rich_menu['message']);

        } else {

            \Session::flash('alert-success', 'แก้ไขแบรนด์สำเร็จ');

        }

        return redirect()->back();


    }

    public function updateRichMenuRegister(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        $line_api = new LineApi();

        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $line_api->setDefaultRichMenu($input['line_menu_register']);

        $brand->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขแบรนด์สำเร็จ');

        return redirect()->back();


    }
}
