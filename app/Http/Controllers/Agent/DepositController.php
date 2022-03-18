<?php

namespace App\Http\Controllers\Agent;

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
use Illuminate\Http\Request;
use App\Models\CustomerRefer;
use App\Models\PromotionCost;
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

class DepositController extends Controller
{
    //
    public function index(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $bank_accounts = BankAccount::whereBrandId($brand->id)->whereType(1)->get();

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])->get();

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)->whereIn('status',[1,3,4])->get();
        
        return view('agent.deposits.index', \compact('bank_accounts','dates','customer_deposits','brand','dates','bank_account_transactions'));

    }

    public function lists(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $bank_accounts = BankAccount::whereBrandId($brand->id)->whereIn('type',[1,2])->get();

        $customer_deposits = CustomerDeposit::whereTypeDeposit(1)->whereBrandId($brand->id)->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])->get();

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)->whereIn('status',[1,3,4])->get();

        return view('agent.deposits.lists', \compact('brand','dates','bank_accounts','customer_deposits','bank_account_transactions'));

    }

    public function export(Request $request) 
    {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->withTrashed()->get();

        return view('agent.deposits.excel', compact('customer_deposits'));

    }

    public function history(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->withTrashed()->paginate(30)->appends(request()->except('page'));

        return view('agent.deposits.history', compact('dates','customer_deposits','brand'));

    }

    public function store(DepositHoldRequest $request) {

        $input = $request->all();

        DB::beginTransaction();
        
        $bank_account_transaction = BankAccountTransaction::find($input['bank_transaction_id']);

        $customer = Customer::find($input['bank_account']);

        if(!$customer) {

            return \redirect()->back()->withErrors(['ไม่พบบัญชีในระบบ']);

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

        $input['user_id'] = Auth::user()->id;

        $input['bank_account_id'] = $bank_account_transaction->bank_account_id;

        $input['brand_id'] = $customer->brand_id;

        $input['game_id'] = $customer->game_id;

        $input['customer_id'] = $customer->id;

        $input['status'] = 0;

        $input['amount'] = $bank_account_transaction->amount;

        $input['username'] = $customer->username;

        $input['name'] = $customer->name;

        $input['status'] = 1;

        if($input['promotion_id'] != 0) {

            if($bank_account_transaction->amount >= $promotion->min) {
                //promotion
                $promotion = Promotion::find($input['promotion_id']);

                $input['bonus'] = Helper::bonusCalculator($bank_account_transaction->amount,$promotion);

                PromotionCost::create([
                    'brand_id' => $brand->id,
                    'promotion_id' => $promotion->id,
                    'customer_id' => $customer->id,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $input['bonus'],
                    'status' => 0,
                ]);

            } else {

                $input['bonus'] = 0;

            }

        } else {

            $input['bonus'] = 0;

        }

        $brand = Brand::find($customer->brand_id);

        $total_amount = $bank_account_transaction->amount + $input['bonus'];

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['amount'] = $total_amount;

        $data['customer_id'] = $customer->id;

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $api_deposit = $api->deposit($data);

        if($brand->game_id == 1) {

            if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                //customer online
                $bank_account_transaction->update([
                    'status' => 5,
                ]);

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

            if($api_deposit['status'] === false) {

                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                return abort(500, 'API ERROR TRY AGAIN');

            }

            $refer_id = $api_deposit['data']['ref'];

        }

        $customer->update([
            'promotion_id' => 0,
            'refer_id' => $refer_id,
        ]);

        $bank_account_transaction->update([
            'status' => 2,
        ]);

        $customer_deposit = CustomerDeposit::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'game_id' => $brand->game_id,
            'promotion_id' => $customer->promotion_id,
            'bank_account_id' => $bank_account_transaction->bank_account_id,
            'name' => $customer->name,
            'username' => $customer->username,
            'amount' => $bank_account_transaction->amount,
            'bonus' => $bank_account_transaction->bonus,
            'type_deposit' => 2,
            'status' => 1,
        ]);

        BankAccountHistory::create([
            'brand_id' => $brand->id,
            'bank_account_id' => $bank_account_transaction->bank_account_id,
            'table_id' => $customer_deposit->id,
            'user_id' => Auth::user()->id,
            'table' => 'customer_deposits',
            'amount' => $bank_account_transaction->amount,
            'type' => 1,
        ]);

        $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

        $bank_account_transaction->update([
            'status' => 2,
        ]);

        $customer->update([
            'status_deposit' => 1,
        ]);

        UserEvent::create([
            'brand_id' => $brand->id,
            'user_id' => Auth::user()->id,
            'description' => 'พนักงาน '.Auth::user()->name.' ได้เติมเงินให้กับ '.$customer->name.' เป็นจำนวน '.$customer_deposit->amount.' เครดิต '
        ]);

        DB::commit();

        return \redirect()->back();

    }

    public function manual(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $result = true;

        $customer_deposit = CustomerDeposit::find($input['customer_deposit_id']);

        $customer = Customer::find($customer_deposit->customer_id);

        $brand = Brand::find(Auth::user()->brand_id);

        if($customer_deposit->promotion_id != 0) {

            $promotion = Promotion::find($customer_deposit->promotion_id);

            if($customer_deposit->amount >= $promotion->min) {
                
                //promotion
                $bonus = Helper::bonusCalculator($customer_deposit->amount,$promotion);

                PromotionCost::create([
                    'brand_id' => $brand->id,
                    'promotion_id' => $promotion->id,
                    'customer_id' => $customer->id,
                    'username' => $customer->username,
                    'amount' => $customer_deposit->amount,
                    'bonus' => $bonus,
                    'status' => 0,
                ]);

            } else {

                $bonus = 0;

            }

        } else {

            $bonus = 0;

        }

        $total_amount = $customer_deposit->amount + $bonus;

        if($brand->game_id == 1) {
            
            $response = Bot::depositGclub($brand,$customer,$total_amount);

            if($response['online'] === true && $response['stataus'] === false) {

                //customer online
                $bank_account_transaction->update([
                    'status' => 5,
                ]);

                DB::rollback();

                $result = false;

            } else if ($response['online'] === false && $response['status'] === false) {

                //api error
                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                DB::rollback();

                $result = false;

            }

        } else if($brand->game_id == 3) {

            //Deposit Racha Casino
            $racha_api = new RachaApi();

            $racha_api->agent = $brand->agent_prefix;

            $racha_api->app_id = $brand->app_id;

            $data['username'] = $customer->username;

            $data['amount'] = $total_amount;

            $data['type'] = 2;

            $racha_api_deposit = $racha_api->transfer($data);

            if($racha_api_deposit['code'] !== 0) {

                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                DB::rollback();

                $result = false;

            }

        } else if($brand->game_id == 5) {
        
            //Fastbet
            $response = Bot::depositFastbet($brand,$customer,$total_amount);

            if($response['status'] === false) {

                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                DB::rollback();

                $result = false;

            }
            
        } else if($brand->game_id == 6) {
        
            //Fastbet
            $response = Bot::depositUking($brand,$customer,$total_amount);

            if($response['status'] === false) {

                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                DB::rollback();

                $result = false;

            }
        }

        if($result === true) {

            $customer_deposit->update([
                'status' => 1,
                'user_id' => Auth::user()->id,
            ]);
            
            $customer->update([
                'promotion_id' => 0,
            ]);

            BankAccountHistory::create([
                'brand_id' => $brand->id,
                'bank_account_id' => $customer_deposit->bank_account_id,
                'table_id' => $customer_deposit->id,
                'user_id' => Auth::user()->id,
                'table' => 'customer_deposits',
                'amount' => $customer_deposit->amount,
                'type' => 1,
            ]);

            $customer_deposit->bankAccount->increment('amount', $customer_deposit->amount);

            UserEvent::create([
                'brand_id' => $brand->id,
                'user_id' => Auth::user()->id,
                'description' => 'พนักงาน '.Auth::user()->name.' ได้เติมเงินให้กับ '.$customer->name.' เป็นจำนวน '.$customer_deposit->amount.' เครดิต '
            ]);

        }

        DB::commit();

        return response()->json($result);

    }

    public function cancel(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer_deposit = CustomerDeposit::find($input['customer_deposit_id']);

        $customer = Customer::find($customer_deposit->customer_id);

        $input['status'] = 2;

        $customer_deposit->update($input);

        $customer_deposit->delete();

        DB::commit();

        return redirect()->back();

    }

    public function updateTypeDeposit(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        Brand::find($input['brand_id'])->update([
            'type_deposit' => $input['type_deposit'],
        ]);

        DB::commit();

    }

    public function notify() {

        $brand = Brand::find(Auth::user()->brand_id);

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereTypeDeposit(2)->whereStatus(0)->count();

        return response()->json([
            'count' => $customer_deposits,
            'brand' => $brand
        ]);

    }

    public function findCustomer() {

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_account = $_GET['q'];

        $customer = Customer::with('bank')->whereBrandId($brand->id)->where('bank_account','LIKE','%'. $bank_account.'%')->get();

        return response()->json($customer);

    }

}
