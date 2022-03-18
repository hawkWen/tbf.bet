<?php

namespace App\Http\Controllers\Agent;

use App\Helpers\Api;
use App\Helpers\Bot;
use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\Customer;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\CustomerCreditHistory;
use App\Helpers\FastbetBotApi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    //
    public function index(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $promotions = Promotion::whereBrandId($brand->id)->whereIn('type_promotion', [4, 5])->get();

        $banks = Bank::all();

        $search = '';

        if (isset($_GET['name'])) {

            $search = $_GET['name'];

            $customers = Customer::where('name', 'LIKE', '%' . $_GET['name'] . '%')
                ->orWhere('bank_account', 'LIKE', '%' . $_GET['name'] . '%')
                ->orWhere('username', 'LIKE', '%' . $_GET['name'] . '%')
                ->orWhere('telephone', 'LIKE', '%' . $_GET['name'] . '%')
                ->orderBy('created_at', 'desc')
                ->whereBrandId($brand->id)
                ->paginate(20);
        } else if(isset($_GET['bank_account'])) {

            $bank_account = $_GET['bank_account'];

            $customers = Customer::where('bank_account_scb',$bank_account)
                ->orderBy('created_at','desc')
                ->whereBrandId($brand->id)
                ->paginate(20);

        } else {

            $customers = Customer::whereBrandId($brand->id)->orderBy('created_at', 'desc')->withTrashed()->paginate(10);
        }
        

        return view('agent.customers.index', compact('brand', 'dates', 'customers', 'banks', 'promotions', 'search'));
    }

    public function lastPromotion(Request $request)
    {

        $input = $request->all();

        $promotion_cost = PromotionCost::whereCustomerId($input['customer_id'])->wherePromotionId($input['promotion_id'])->orderBy('created_at', 'desc')->first();

        if ($promotion_cost) {

            $last_time = Helper::remainTime($promotion_cost->created_at);

            $message = $promotion_cost->created_at->format('d/m/Y H:i:s') . ' (' . $last_time . ')';
        } else {

            $message = 'ยังไม่เคยได้รับโปรนี้';
        }

        return response()->json($message);
    }

    public function show($customer_id)
    {

        $customer = Customer::find($customer_id);

        $brand = Brand::find($customer->brand_id);

        return view('agent.customers.show', compact('customer', 'brand'));
    }

    public function update(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $bank = explode(':', $input['bank_id']);

        $input['bank_id'] = $bank[0];

        $input['code_bank'] = $bank[1];

        if ($input['code_bank'] === 'SCB') {

            $input['bank_account_scb'] = substr($input['bank_account'], -4);
        } else {

            $input['bank_account_scb'] = substr($input['bank_account'], -6);
        }

        $input['bank_account_krungsri'] = substr($input['bank_account'], 3);

        $input['bank_account_kbank'] = substr(substr($input['bank_account'], 3), 0, -1);

        $customer = Customer::find($input['customer_id']);

        $customer->update($input);

        DB::commit();

        UserEvent::create([
            'brand_id' => $customer->brand_id,
            'user_id' => Auth::user()->id,
            'description' => 'พนักงาน ' . Auth::user()->name . ' ได้เปลี่ยนข้อมูลเลขบัญชีธนาคารให้กับ ' . $customer->name,
        ]);

        \Session::flash('alert-success', 'แก้ไขข้อมูลเสร็จแล้ว');

        return redirect()->back();
    }

    public function promotion(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        $promotion = Promotion::find($input['promotion_id']);

        $dates = Helper::getTimeMonitor();

        $input['bonus'] = str_replace(',', '', $input['bonus']);

        $promotion_cost = PromotionCost::whereCustomerId($input['customer_id'])->wherePromotionId($input['promotion_id'])->whereBetween('created_at', [$dates[0], $dates[1]])->orderBy('created_at', 'desc')->first();

        if ($promotion_cost) {

            return response()->json([
                'status' => false,
                'message' => 'ลูกค้ารับโปรโมชั่น ' . $promotion->name . ' ไปแล้ววันนี้ ลองใหม่วันพรุ่งนี้เช้าหลัง 11:00',
            ]);
        }

        $brand = Brand::find($customer->brand_id);

        $total_amount = $input['bonus'];

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['amount'] = $total_amount;

        $data['customer_id'] = $customer->id;

        if ($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;
        }

        $api_deposit = $api->deposit($data);

        if ($brand->game_id == 1) {

            if ($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                //customer online
                // $bank_account_transaction->update([
                //     'status' => 5,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');
            } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {
                //api error
                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                return abort(500, 'API ERROR TRY AGAIN');
            }

            $refer_id = 0;
        } else {

            if ($api_deposit['status'] === false) {

                // $bank_account_transaction->update([
                //     'status' => 0,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');
            }

            $refer_id = ($api_deposit['data']['ref']) ? $api_deposit['data']['ref'] : 0;
        }

        $customer->update([
            'promotion_id' => 0,
            'refer_id' => $refer_id,
        ]);

        //Create Promotion Cost;
        PromotionCost::create([
            'brand_id' => $brand->id,
            'promotion_id' => $promotion->id,
            'customer_id' => $customer->id,
            'username' => $customer->username,
            'amount' => 0,
            'bonus' => $input['bonus'],
            'status' => 1,
        ]);

        //Save User Event;
        UserEvent::create([
            'brand_id' => $brand->id,
            'user_id' => Auth::user()->id,
            'description' => 'พนักงาน ' . Auth::user()->name . ' ได้ให้โบนัสพิเศษกับลูกค้า (' . $promotion->name . ') ' . $customer->name . ' เป็นจำนวน ' . $input['bonus'] . ' เครดิต '
        ]);

        $message = 'ระบบได้ให้โบนัสพิเศษกับลูกค้า (' . $promotion->name . ') ' . $customer->name . ' เป็นจำนวน ' . number_format($input['bonus'], 2) . ' เครดิต ';

        $line_api = new LineApi();

        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $push = $line_api->pushMessage($customer->line_user_id, $message);

        DB::commit();

        return response()->json([
            'status' => true,
        ]);
    }

    public function changePassword(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $input['type'] = (isset($input['type'])) ? '1' : '0';

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        if (isset($input['password'])) {

            $input['password_generate'] = $input['password'];

            $input['password'] = \bcrypt($input['password']);

            $input['status_deposit'] = 1;

            $api = new Api($brand);

            $data['username'] = $customer->username;

            $data['password_old'] = $customer->password_generate;

            $data['password'] = $input['password_generate'];

            $api_password = $api->changePassword($data);
        }

        $customer->update($input);

        //Save User Event;
        UserEvent::create([
            'brand_id' => $customer->brand_id,
            'user_id' => Auth::user()->id,
            'description' => 'พนักงาน ' . Auth::user()->name . ' ได้เปลี่ยนรหัสผ่านให้กับ ' . $customer->username,
        ]);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขข้อมูลเสร็จแล้ว');

        return redirect()->back();
    }

    public function minusCredit(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $input['amount'] = str_replace(',', '', $input['amount']);

        $api = new Api($brand);

        $data['username'] = $customer->username;

        if ($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;
        }

        $api_credit = $api->credit($data);

        if ($api_credit['status'] == false) {

            return redirect()->back()->withErrors(['ระบบเกมส์มีปัญหากรุณาลองใหม่อีกครั้งค่ะ']);
        }

        if ($input['amount'] > $api_credit['data']['credit']) {

            return redirect()->back()->withErrors(['ขออภัยค่ะ เครดิตในเกมส์มีไม่ถึง']);
        }

        $data['amount'] = $input['amount'];

        $api_withdraw = $api->withdraw($data);

        CustomerCreditHistory::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'customer_withdraw_id' => 0,
            'amount_before' => $customer->credit,
            'amount' => $input['amount'],
            'amount_after' => $customer->credit - $input['amount'],
            'type' => 2,
        ]);

        DB::commit();

        \Session::flash('alert-success', 'ดึงเครดิตจำนวน ' . $input['amount'] . ' เรียบร้อยแล้วค่ะ');

        return redirect()->back();
    }
}
