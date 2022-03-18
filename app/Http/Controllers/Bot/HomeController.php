<?php

namespace App\Http\Controllers\Bot;

use App\Models\Brand;
use App\Models\BotEvent;
use Illuminate\Http\Request;
use App\Models\CustomerDeposit;
use App\Models\CustomerWithdraw;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BankAccountTransaction;

class HomeController extends Controller
{
    //
    public function index() {

        $brands = Brand::whereIn('type_api',['1','2'])->get();

        $bot_events = BotEvent::orderBy('created_at','desc')->take(30)->get();

        return view('bot.index', compact('brands','bot_events'));

    }

    public function botEvent() {

        $bot_events = BotEvent::orderBy('created_at','desc')->take(30)->get();

        return view('bot.event', compact('bot_events'));

    }

    public function checkStatus() {

        $brands = Brand::all();

        $collect = collect([
            'status_bot_bank' => collect([]),
            'status_bot_deposit'=> collect([]),
            'status_bot_withdraw' => collect([]),
        ]);

        foreach($brands as $brand) {

            if($brand->status_bot_bank == 1) {
                $collect['status_bot_bank']->push($brand->id);
            }

            if($brand->status_bot_deposit == 1) {
                $collect['status_bot_deposit']->push($brand->id);
            }

            if($brand->status_bot_withdraw == 1) {
                $collect['status_bot_withdraw']->push($brand->id);
            }

        }

        return response()->json($collect);

    }

    public function unlock(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if($input['code'] != 'bot') {
            return response()->json([
                'code' => 500,
                'message' => 'Code Error'
            ]);
        }

        DB::commit();

        return response()->json([
            'code' => 0,
            'message' => 'Success',
        ]);

    }
}
