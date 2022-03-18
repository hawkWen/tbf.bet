<?php

namespace App\Http\Controllers\Agent;

use Carbon\Carbon;
use App\Helpers\Api;
use App\Helpers\Bot;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\Customer;
use App\Helpers\GClubApi;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Models\UserEvent;
use App\Helpers\FastbetApi;
use App\Models\BankAccount;
use App\Models\WheelConfig;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\CustomerCreditHistory;
use App\Helpers\FastbetBotApi;
use App\Models\CustomerDeposit;
use App\Models\BankAccountHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerDepositExport;
use App\Models\BankAccountTransaction;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DepositHoldRequest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Requests\DepositManualRequest;

class ManualController extends Controller
{
    //
    public function index(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])->whereTypeManual(2)->paginate(30)->appends(request()->except('page'));

        $customers = Customer::whereBrandId($brand->id)->whereStatusManual(1)->get();

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)->orderBy('created_at','desc')->paginate(30)->appends(request()->except('page'));

        $promotions = Promotion::whereBrandId($brand->id)->get();
        
        return view('agent.manual.index', \compact('bank_accounts','dates','customer_deposits','brand','dates','bank_account_transactions','customers','promotions'));

    }

    public function transaction(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)->whereCode('X1')->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])->orderBy('created_at','desc')->paginate(30)->appends(request()->except('page'));

        return view('agent.manual.transaction', compact('brand','dates','bank_account_transactions'));

    }

    public function history(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])->whereTypeManual(2)->get();

        return view('agent.manual.history', compact('brand','dates','customer_deposits'));

    }

    public function creditFree(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::whereBrandId(Auth::user()->brand_id)->whereUsername($input['username'])->first();

        if(!$customer) {

            return \redirect()->back()->withErrors(['ไม่พบบัญชีในระบบ']);

        }

        $brand = Brand::find($customer->brand_id);

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $api_credit = $api->credit($data);

        // dd($api_credit);

        if(!$customer) {

            return \redirect()->back()->withErrors(['ไม่พบบัญชีในระบบ']);

        }

        $promotion_cost = PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->first();

        if($promotion_cost && $api_credit['data']['credit'] > 20) {

            return \redirect()->back()->withErrors(['ลูกค้าติดโปรโมชั่น '.$promotion_cost->promotion->name.' หรือ เครดิตยังไม่น้อยกว่า 20']);

        }

        $input['user_id'] = Auth::user()->id;

        $promotion = Promotion::find($input['promotion_id']);

        $input['bonus'] = Helper::bonusCalculator(0,$promotion);

        $total_amount = $input['bonus'];

        $data['amount'] = $total_amount;

        $data['customer_id'] = $customer->id;
    
        $api_deposit = $api->deposit($data);

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $refer_id = 0;

        if($brand->game_id == 1) {

            if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                //customer online
                // $bank_account_transaction->update([
                //     'status' => 5,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');

            } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {
                //api error
                // $bank_account_transaction->update([
                //     'status' => 0,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');

            }

        } else {

            if($api_deposit['status'] === false) {

                // $bank_account_transaction->update([
                //     'status' => 0,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');

            }

            if(isset($api_deposit['data']['ref'])) {

                $refer_id = $api_deposit['data']['ref'];

            }

        }

        $customer->update([
            'promotion_id' => 0,
            'refer_id' => $refer_id,
        ]);

        PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->update([
            'status' => 1,
        ]);

        PromotionCost::create([
            'brand_id' => $brand->id,
            'promotion_id' => $promotion->id,
            'customer_id' => $customer->id,
            'username' => $customer->username,
            'amount' => 0,
            'bonus' => $input['bonus'],
            'status' => 0,
        ]);

        CustomerCreditHistory::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'customer_deposit_id' => 0,
            'promotion_id' => $promotion->id,
            'amount_before' => $customer->credit,
            'amount' => $input['bonus'],
            'amount_after' => $customer->credit + $total_amount,
            'type' => 1,
        ]);

        $message = "ระบบได้ให้เครดิตฟรี ".$customer->username." จำนวน ".$input['bonus']." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

        $line_api = new LineApi();

        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $push = $line_api->pushMessage($customer->line_user_id, $message);

        \Session::flash('alert-success', 'เติมเครดิตฟรีให้กับ Username: '. $customer->username. ' เรียบร้อยแล้วนะคะ');

        DB::commit();

        return \redirect()->back();

    }

    public function store(DepositManualRequest $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::whereBrandId(Auth::user()->brand_id)->whereUsername($input['username'])->first();

        if(!$customer) {

            return \redirect()->back()->withErrors(['ไม่พบบัญชีในระบบ']);

        }

        $brand = Brand::find($customer->brand_id);

        $api = new Api($brand);

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $data['username'] = $customer->username;

        $api_credit = $api->credit($data);

        $promotion_cost = PromotionCost::whereBrandId($brand->id)->where('promotion_id','!=',0)->whereCustomerId($customer->id)->whereStatus(0)->first();

        if($promotion_cost && $api_credit['data']['credit'] > 20) {

            return \redirect()->back()->withErrors(['ลูกค้าติดโปรโมชั่น '.$promotion_cost->promotion->name.' หรือ เครดิตยังไม่น้อยกว่า 20']);

        }

        if(isset($input['slip'])) {
            
            //put new image 
            $storage  = Storage::disk('public')->put('slips', $request->file('slip'));

            if(env('APP_ENV') == 'local') {

                $input['slip_url'] = Storage::url($storage);

            } else {

                $input['slip_url'] = secure_url(Storage::url($storage));

            }

            $input['slip'] = $storage;

        } 

        $input['transfer_at'] = Carbon::createFromFormat('d/m/Y', $input['transfer_date'])->format('Y-m-d'). ' ' .$input['transfer_time'].':00';

        $input['user_id'] = Auth::user()->id;

        $input['bank_account_id'] = $input['bank_account_id'];

        $input['brand_id'] = $customer->brand_id;

        $input['game_id'] = $customer->game_id;

        $input['customer_id'] = $customer->id;

        $input['status'] = 0;

        $input['amount'] = str_replace(',','',$input['amount']);

        $input['username'] = $customer->username;

        $input['name'] = $customer->name;

        $input['status'] = 1;

        if($input['promotion_id'] != 0) {
            //promotion
            $promotion = Promotion::find($input['promotion_id']);

            if($input['amount'] >= $promotion->min) {

                $input['bonus'] = Helper::bonusCalculator($input['amount'],$promotion);

                PromotionCost::create([
                    'brand_id' => $customer->brand_id,
                    'promotion_id' => $promotion->id,
                    'customer_id' => $customer->id,
                    'username' => $customer->username,
                    'amount' => $input['amount'],
                    'bonus' => $input['bonus'],
                    'status' => 0,
                ]);

            } else {

                $input['bonus'] = 0;

            }

        } else {

            $input['bonus'] = 0;

        }

        $total_amount = $input['amount'] + $input['bonus'];

        $data['username'] = $customer->username;

        $data['amount'] = $total_amount;
        
        $data['customer_id'] = $customer->id;

        $api_deposit = $api->deposit($data);

        $refer_id = 0;

        if($brand->game_id == 1) {

            if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                //customer online
                // $bank_account_transaction->update([
                //     'status' => 5,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');

            } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {
                //api error
                // $bank_account_transaction->update([
                //     'status' => 0,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');

            }

        } else {

            if($api_deposit['status'] === false) {

                // $bank_account_transaction->update([
                //     'status' => 0,
                // ]);

                return abort(500, 'API ERROR TRY AGAIN');

            }

            if(isset($api_deposit['data']['ref'])) {

                $refer_id = $api_deposit['data']['ref'];

            }

        }

        $customer_deposit = CustomerDeposit::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'game_id' => $brand->game_id,
            'user_id' => Auth::user()->id,
            'promotion_id' => 0,
            'bank_account_id' => $input['bank_account_id'],
            'name' => $customer->name,
            'username' => $customer->username,
            'amount' => $input['amount'],
            'bonus' => $input['bonus'],
            'type_deposit' => 2,
            'type_manual' => 2,
            'transfer_at' => $input['transfer_at'],
            'status' => 1,
        ]);

        CustomerCreditHistory::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'customer_deposit_id' => $customer_deposit->id,
            'amount_before' => $customer->credit,
            'amount' => $total_amount,
            'amount_after' => $customer->credit + $total_amount,
            'type' => 1,
        ]);

        BankAccountHistory::create([
            'brand_id' => $brand->id,
            'bank_account_id' => $input['bank_account_id'],
            'table_id' => $customer_deposit->id,
            'user_id' => Auth::user()->id,
            'table' => 'customer_deposits',
            'amount' => $input['amount'],
            'type' => 1,
        ]);

        $bank_account = BankAccount::find($input['bank_account_id']);

        $bank_account->increment('amount', $input['amount']);

        $bank_account->update([
            'status' => 2,
        ]);

        //wheel update score
        $wheel_config = WheelConfig::whereBrandId($brand->id)->first();

        if($wheel_config) {

            $wheel_score_total = $customer->wheel_score + $input['amount'];

            if($wheel_score_total >= $wheel_config->amount_condition) {

                $wheel_amount_total = $customer->wheel_amount + 1;
        
                $customer->update([
                    'wheel_score' => 0,
                    'wheel_amount' => $wheel_amount_total
                ]);

            } else {
        
                $customer->update([
                    'wheel_score' => $customer->wheel_score + $input['amount'],
                ]);

            }

        }

        $customer->update([
            'promotion_id' => 0,
            'refer_id' => $refer_id,
        ]);

        UserEvent::create([
            'brand_id' => $brand->id,
            'user_id' => Auth::user()->id,
            'description' => 'พนักงาน '.Auth::user()->name.' ได้เติม manual ให้กับ '.$customer->name.' เป็นจำนวน '.$customer_deposit->amount.' เครดิต '
        ]);

        $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$input['amount']." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

        $line_api = new LineApi();

        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $push = $line_api->pushMessage($customer->line_user_id, $message);

        \Session::flash('alert-success', 'เติมมือให้กับ Username: '. $customer->username. ' เรียบร้อยแล้วนะคะ');

        DB::commit();

        return \redirect()->back();

    }

    public function transactionLists($brand_id) {

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand_id)->where('code', '=', 'X1')->whereIn('status', [0, 1, 2])->orderBy('created_at','desc')->take(10)->get();

        $bank_account_transaction_wrongs = BankAccountTransaction::whereBrandId($brand_id)->where('code', '=', 'X1')->whereIn('status', [4, 5, 6, 8, 9])->orderBy('created_at','desc')->take(10)->get();

        return view('agent.manual.transaction-lists', compact('bank_account_transactions','bank_account_transaction_wrongs'));

    }

    public function update($customer_id) {

        $customer = Customer::find($customer_id);

        $customer->update([
            'status_manual' => 0,
        ]);

        \Session::flash('alert-success', 'อัพเดทสถานะให้กับ Username: '. $customer->username. ' เรียบร้อยแล้วนะคะ');

        return \redirect()->back();

    }

    public function monitorLists($brand_id) {

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand_id)->where('code', '=', 'X1')->whereIn('status', [0, 1, 2])->orderBy('created_at','desc')->take(10)->get();

        return view('agent.manual.monitor-lists', compact('bank_account_transactions'));

    }
    
}
