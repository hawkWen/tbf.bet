<?php

namespace App\Http\Controllers\Agent;

use App\User;
use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Helpers\GClubApi;
use App\Helpers\RachaApi;
use App\Models\UserEvent;
use App\Helpers\FastbetApi;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Helpers\FastbetBotApi;
use App\Models\CustomerDeposit;
use App\Models\CustomerWithdraw;
use App\Models\BankAccountReturn;
use App\Models\BankAccountHistory;
use Illuminate\Support\Facades\DB;
use App\Models\BankAccountWithdraw;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BankAccountTransaction;

class ReportController extends Controller
{
    //
    public function summary(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->whereStatus(1)->get();

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->whereStatus(2)->get();

        $promotion_costs = PromotionCost::whereBrandId($brand->id)->whereIn('status', [0, 1])->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();

        $bank_account_withdraws = BankAccountWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();

        $bank_account_returns = BankAccountReturn::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();

        $total = $customer_deposits->sum('amount') - $customer_withdraws->sum('amount') - $promotion_costs->sum('bonus') - $bank_account_withdraws->sum('amount');

        return view('agent.reports.summary', compact('brand', 'dates', 'customer_deposits', 'customer_withdraws', 'promotion_costs', 'bank_account_withdraws', 'bank_account_returns', 'total'));
    }

    public function customer(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $banks = Bank::all();

        if ($request->get('filter_deposit')) {

            $filter_deposit = $request->get('filter_deposit');

            $customers = collect([]);

            // คำนวนยอดฝากงินสูงสุด
            $customer_deposits = CustomerDeposit::select('customer_id', DB::raw('SUM(amount) as deposit_amount_total'))
                ->with('customer')
                ->whereBrandId($brand->id)
                ->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])
                ->orderBy('deposit_amount_total', 'desc')
                ->groupBy('customer_id')
                ->take(150)
                ->get();

            return view('agent.reports.customer', compact('brand', 'dates', 'customer_deposits', 'banks', 'filter_deposit'));;
        } else {

            $filter_deposit = $request->get('filter_deposit');

            $customers = Customer::whereBrandId($brand->id)
                ->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])
                ->orderBy('created_at', 'desc')
                ->withTrashed()
                ->paginate(30)
                ->appends(request()
                    ->except('page'));;

            return view('agent.reports.customer', compact('brand', 'dates', 'customers', 'banks', 'filter_deposit'));
        }
    }

    public function customerUpdate(Request $request)
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

        Customer::find($input['customer_id'])->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขข้อมูลเสร็จแล้ว');

        return redirect()->back();
    }

    public function customerExcel(Request $request)
    {

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $customers = Customer::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->withTrashed()->get();

        return view('agent.reports.customer-excel', compact('customers'));
    }

    public function deposit(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->orderBy('created_at', 'desc')->withTrashed()->paginate(30)->appends(request()->except('page'));

        return view('agent.reports.deposit', compact('brand', 'dates', 'customer_deposits'));
    }

    public function depositExport(Request $request)
    {

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->withTrashed()->get();

        return view('agent.reports.deposit-excel', compact('customer_deposits'));
    }

    public function withdraw(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->withTrashed()->paginate(30)->appends(request()->except('page'));

        return view('agent.reports.withdraw', compact('brand', 'dates', 'customer_withdraws'));
    }

    public function withdrawExcel(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->withTrashed()->get();

        return view('agent.reports.withdraw-excel', compact('brand', 'dates', 'customer_withdraws'));
    }

    public function promotion(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $promotion_costs = PromotionCost::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));

        return view('agent.reports.promotion', compact('brand', 'dates', 'promotion_costs'));
    }

    public function promotionExcel(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $promotion_costs = PromotionCost::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();

        return view('agent.reports.promotion-excel', compact('brand', 'dates', 'promotion_costs'));
    }

    public function statement(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        if (isset($_GET['bank_account_id'])) {

            $bank_account_select = $_GET['bank_account_id'];

            $bank_account_histories = BankAccountHistory::whereBankAccountId($_GET['bank_account_id'])->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));
        } else {

            $bank_account_select = '';

            $bank_account_histories = BankAccountHistory::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));
        }

        return view('agent.reports.statement', compact('brand', 'dates', 'bank_account_histories', 'bank_accounts', 'bank_account_select'));
    }

    public function statementExcel(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);


        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        if (isset($_GET['bank_account_id'])) {

            $bank_account_histories = BankAccountHistory::whereBankAccountId($_GET['bank_account_id'])->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();
        } else {

            $bank_account_histories = BankAccountHistory::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();
        }

        return view('agent.reports.statement-excel', compact('brand', 'dates', 'bank_account_histories'));
    }

    public function transaction(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        if (isset($_GET['bank_account_id'])) {

            $bank_account_histories = BankAccountHistory::whereBankAccountId($_GET['bank_account_id'])->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));
        } else {

            $bank_account_histories = BankAccountHistory::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));
        }

        return view('agent.reports.transaction', compact('brand', 'dates', 'bank_account_histories'));
    }

    public function transactionExcel(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        if (isset($_GET['bank_account_id'])) {

            $bank_account_histories = BankAccountHistory::whereBankAccountId($_GET['bank_account_id'])->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();
        } else {

            $bank_account_histories = BankAccountHistory::whereBrandId($brand->id)->get()->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->get();
        }

        return view('agent.reports.transaction', compact('brand', 'dates', 'bank_account_histories'));
    }

    public function event(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        $users = User::whereBrandId($brand->id)->get();

        if (isset($_GET['user_id'])) {

            $user_id_select = $_GET['user_id'];

            $user_events = UserEvent::whereUserId($_GET['user_id'])->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));
        } else {

            $user_id_select = '';

            $user_events = UserEvent::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));
        }

        return view('agent.reports.event', compact('brand', 'dates', 'user_events', 'user_id_select', 'users'));
    }

    public function eventExcel(Request $request)
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'), $request->get('end_date'));

        if (isset($_GET['user_id'])) {

            $user_id_select = $_GET['user_id'];

            $user_events = UserEvent::whereUserId($_GET['user_id'])->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->get();
        } else {

            $user_id_select = '';

            $user_events = UserEvent::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'], $dates['end_date']])->get();
        }

        return view('agent.reports.event-excel', compact('brand', 'dates', 'user_events', 'user_id_select', 'users'));
    }

    public function summaryCredit(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        if ($brand->game_id == 1) {

            $customer = Customer::find($input['customer_id']);

            //Gclub Store And Create
            $response = $this->creditGClub($brand, $customer);

            if ($response['code'] == 0) {

                $customer->update([
                    'credit' => $response['Balance'],
                    'last_update_credit' => date('Y-m-d H:i:s'),
                    'status_credit' => 2,
                    'update_log' => $response,
                ]);
            } else {

                $customer->update([
                    'status_credit' => 0,
                    'update_log' => $response,
                ]);
            }
        } else if ($brand->game_id == 2) {

            //Ufabet Store And Create
            $response = $this->creditUfabet($brand, $customer);
        } else if ($brand->game_id == 3) {

            //Racha Store And Create
            $response = $this->creditRacha($brand, $customer);
        } else if ($brand->game_id == 5) {

            $fastbet_bot_api = new FastbetBotApi();

            $fastbet_bot_api->ip = $brand->server_api;

            $fastbet_bot_api->username = $brand->agent_username;

            $credit_remain = $fastbet_bot_api->creditRemain();

            if ($credit_remain['code'] == 200) {

                $brand->update([
                    'credit_remain' => $credit_remain['bet'],
                    'last_update_credit_remain' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $customer_finish = Customer::whereBrandId($brand->id)->whereNotNull('username')->whereStatusCredit(2)->get()->count();

        DB::commit();

        return response()->json([
            'count' => $customer_finish,
        ]);
    }

    public function creditGClub($brand, $customer)
    {

        $response = json_decode(\file_get_contents($brand->server_api . '/server-api/gclub/?credit&username=' . $brand->agent_username . '&password=' . $brand->agent_password . '&user=' . $customer->username), true);

        return $response;
    }

    public function creditUfabet()
    {
    }

    public function creditFastbet($brand, $customer)
    {

        $fastbet_api = new FastbetApi();

        $fastbet_api->agent = $brand->agent_username;

        $fastbet_api->api_key = $brand->app_id;

        $data = [
            "username" => $customer->username,
        ];

        $credit = $fastbet_api->credit($data);

        return $credit;
    }

    public function creditRacha()
    {

        $racha_api = new RachaApi();

        $racha_api->agent = $brand->agent_username;

        $racha_api->app_id = $brand->app_id;

        $data = json_encode([
            "username" => $customer->username,
        ]);

        $balance = $racha_api->credit($data)['response']['credit'];

        return $balance;
    }

    public function bankAccountTransaction()
    {

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)->orderBy('created_at', 'desc')->paginate(30)->appends(request()->except('page'));

        return view('agent.reports.bank-account-transaction', compact('bank_account_transactions', 'brand'));
    }

    public function bankAccountTransactionUpdate(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account_transaction = BankAccountTransaction::find($input['bank_account_transaction_id']);

        // dd($input,$bank_account_transaction);

        $bank_account_transaction->update([
            'status' => $input['status'],
        ]);

        DB::commit();

        \Session::flash('alert-success', 'เปลี่ยนสถานะสำเร็จ');

        return \redirect()->back();
    }
}
