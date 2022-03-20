<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Helpers\Api;
use App\Helpers\Bot;
use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\PgApi;
use App\Helpers\Helper;
use App\Helpers\UfaApi;
use App\Helpers\LineApi;
use App\Models\Customer;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Models\UserEvent;
use App\Models\CreditFree;
use App\Models\BankAccount;
use App\Models\CustomerBet;
use App\Models\WheelConfig;
use Illuminate\Http\Request;
use App\Models\CustomerWheel;
use App\Models\PromotionCost;
use App\CustomerCreditHistory;
use App\Models\CustomerDeposit;
use App\Models\WheelSlotConfig;
use App\Models\CustomerWithdraw;
use App\Models\CustomerBetDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AmbKingApi;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
        return auth()->shouldUse('api-customer');
    }

    public function guard() {
        return Auth::guard('api-customer');
    }

    public function check()
    {
      return response()->json(auth()->user());
    }

    public function checkBrand(Request $request)
    {

        $input = $request->all();

        $result = true;

        DB::beginTransaction();

        $brand = Brand::select('id', 'game_id', 'logo', 'logo_url', 'name', 'line_id', 'agent_prefix', 'status_telephone', 'type_deposit', 'line_liff_connect', 'line_liff_connect_react', 'status_rank', 'status', 'maintenance', 'withdraw_min', 'deposit_min')
            ->with('bankAccountWebs:id,bank_id,brand_id,name,account,type,status_bot,status', 'bankAccountWebs.bank')->whereSubdomain($input['subdomain'])->first();

        DB::commit();

        return response()->json([
            'status' => ($brand) ? true : false,
            'data' => $brand,
        ]);
    }

    public function promotion(Request $request) {

        $input = $request->all();

        $customer = Customer::find($input['customer_id']);

        $promotion_queries = Promotion::whereBrandId($customer->brand_id)->get();

        $promotions = collect([]);

        $dates = Helper::getTimeMonitor();
        
        foreach ($promotion_queries as $promotion_query) {

            if($promotion_query->type_promotion == 2) {
                
                // เติมเงินทุกครั้ง
                $promotion_query['active'] = 1;

                $promotions->push($promotion_query);

            } else if ($promotion_query->type_promotion == 1) {

                // ครั้งแรกของวัน
                $promotion_costs = PromotionCost::whereCustomerId($customer->id)->wherePromotionId($promotion_query->id) 
                        ->whereBetween('created_at', [$dates[0], $dates[1]])->get();

                if($promotion_costs->count() > 0) {

                    $promotion_query['active'] = 0;

                    $promotions->push($promotion_query);

                } else {

                    $promotion_query['active'] = 1;

                    $promotions->push($promotion_query);

                }

            } else if ($promotion_query->type_promotion == 3) {


                $promotion_costs = PromotionCost::whereCustomerId($customer->id)->wherePromotionId($promotion_query->id)->get();

                if($promotion_costs->count() > 0) {

                    $promotion_query['active'] = 0;

                    $promotions->push($promotion_query);

                } else {

                    $promotion_query['active'] = 1;

                    $promotions->push($promotion_query);

                }
                
            } else {
                
                $promotion_query['active'] = 0;

                $promotions->push($promotion_query);

            }

        }

        return response()->json([
            'status' => ($promotions) ? true : false,
            'data' => $promotions,
        ]);

    }

    protected function encryptAgent($key, $text)
    {
        $password = $key;
        $method = 'aes-256-cbc';
        $password = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        return rawurlencode(base64_encode(openssl_encrypt($text, $method, $password, OPENSSL_RAW_DATA, $iv)));
    }

    public function url(Request $request)
    {

        $input = $request->all();

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        if ($brand->game_id == 1) {

            //gclub
            $url = 'https://www.royal558.com/Home/Index';

        } else if ($brand->game_id == 2) {

            //ufa
            $ufa_api = new UfaApi();

            $ufa_api->api_key = $brand->app_id;

            $ufa_api->agent_username = $brand->agent_username;

            $ufa_api->agent_password = $brand->agent_password;

            $data['username'] = $customer->username;

            $data['password'] = $customer->password_generate;

            $ufa_api_login = $ufa_api->login($data);

            if($ufa_api_login['status'] === 'success') {

                $url = $ufa_api_login['gameUrl'];

            } else {

                $url = 'https://ufabet.com/Default8.aspx?lang=EN-GB';

            }

        } else if ($brand->game_id == 3) {

            $url = 'https://slotracha.com/api/login?username=' . $customer->username . '&password=' . $customer->password_generate;
            
        } else if ($brand->game_id == 5) {

            //fastbet
            $url = 'https://m.fastbet98.com/login/auto/?username=' . $customer->username . '&password=' . $customer->password_generate . '&url=LANDING_PAGE&hash=' . $brand->hash.'&lang=th';

        } else if ($brand->game_id == 6) {

            if ($brand->id == 21) {

                $url = 'https://ukingbet.com/#!/redirect?username=' . $customer->username . '&password=' . $customer->password_generate . '&url=LANDING_PAGE&hash=' . $brand->hash.'&lang=th';
            } else {

                $url = 'https://ukingbet.com/#!/redirect?username=' . $customer->username . '&password=' . $customer->password_generate . '&url=LANDING_PAGE&hash=' . $brand->hash.'&lang=th';
            }

        } else if($brand->game_id == 7) {

            $url = 'https://m.ambbet.com/login/auto/?username=' . $customer->username . '&password=' . $customer->password_generate . '&hash=' . $brand->hash.'&lang=th';

        } else if($brand->game_id == 8) {

            $pg_api = new PgApi();

            $pg_api->agent = $brand->agent_username;

            $pg_api->app_id = $brand->app_id;

            $data['username'] = $customer->username;

            $pg_api_url = $pg_api->lanuch($data);

            if($pg_api_url['status']['code'] == 0) {
                $url = $pg_api_url['data']['url'];
            } else {
                $url = 'https://pgslot.cc';
            }

        } else if($brand->game_id ==9) {

            $url = 'https://mvpatm168.com/#!/redirect?username=' . $customer->username . '&password=' . $customer->password_generate . '&url=LANDING_PAGE&hash=' . $brand->hash;

        } else if($brand->game_id == 10) {

            $url = 'https://456bett.com/login/auto/?username=' . $customer->username . '&password=' . $customer->password_generate . '&url=LANDING_PAGE&hash=' . $brand->hash.'&lang=th';

        } else if($brand->game_id == 11) {

            $url = 'https://ambfun.com/login/auto/?username=' . $customer->username . '&password=' . $customer->password_generate . '&url=LANDING_PAGE&hash=' . $brand->hash.'&lang=th';

        } else if($brand->game_id == 12) {


            $amb_king_api = new AmbKingApi();

            $amb_king_api->agent = $brand->agent_username;

            $amb_king_api->hash = $brand->hash;

            $amb_king_api->key = $brand->app_id;

            $data['username'] = $customer->username;

            $amb_king_api_login = $amb_king_api->redirectLogin($data);

            if($amb_king_api_login['code'] == 0) {

                $url = $amb_king_api_login['url'];

            } else {
                $url = 'https://ambking.com';
            }

        }

        return response()->json([
            'status' => 200,
            'data' => $url
        ]);
    }

    public function getBank()
    {

        $banks = Bank::all();

        return response()->json([
            'status' => ($banks) ? true : false,
            'data' => $banks,
        ]);
    }

    public function checkPhone(Request $request)
    {

        $input = $request->all();

        $result = true;

        DB::beginTransaction();

        $customer = Customer::whereBrandId($input['brand_id'])->get();

        $telephone = $customer->where('telephone', '=', $input['telephone'])->first();

        if ($telephone) {

            $result = false;
        }

        DB::commit();

        return response()->json([
            'status' => $result,
            'data' => [],
        ]);
    }

    public function checkBank(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $response = true;

        $input['bank_account'] = str_replace('-', '', $input['bank_account']);

        $input['bank_account'] = \str_replace('_','',$input['bank_account']);

        $bank_account_unqiue = Customer::whereBrandId($input['brand_id'])->where('bank_account', '=', $input['bank_account'])->first();

        if ($bank_account_unqiue) {
            $response = false;
        }

        $brand = Brand::find($input['brand_id']);

        DB::commit();

        return response()->json([
            'status' => $response,
            'data' => []
        ]);
    }

    public function checkOtp(Request $request)
    {

        $input = $request->all();

        $brand = Brand::whereCodeSms($input['code_sms'])->first();

        if ($brand) {


            $bank_accounts = BankAccount::select('bank_id')->with('bank')->whereBrandId($brand->id)->groupBy('bank_id')->get();
            
            $data = collect([]);

            foreach ($bank_accounts as $bank_account) {

                $inbox = '';

                $type = '';

                if($bank_account->bank_id == 0) {

                    $type = 'TrueMoney';

                    $inbox = 'truemoney';

                } else if($bank_account->bank_id ==1) {

                    $type = '027 777 777';

                    $inbox = 'scb';

                } else if($bank_account->bank_id == 4) {

                    $type = 'KBank';

                    $inbox = 'KBank';

                }

                $data->push([
                    'bank_account' => $bank_account->account,
                    'type' => $type,
                    'url' => 'https://bot.casinoauto.io/otp/get/' . $bank_account->id,
                    'logo' => 'https://bot.casioauto.io/'.$bank_account->bank->logo,
                    'inbox' => $inbox,
                ]);
            }

            return [
                'code' => 200,
                'data' => $data,
                'brand_name' => $brand->name,
                // 'url' => 'https://bot.casinoauto.io/otp/get/' . $bank_account->id,
            ];
        } else {

            return [
                'code' => 404,
                'url' => null
            ];
        }
    }

    public function deposit(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        if ($input['slip'] !== 'null') {

            //put new image 
            $storage  = Storage::disk('public')->put('slips', $request->file('slip'));

            // return response()->json($storage);

            if (env('APP_ENV') == 'local') {

                $input['slip_url'] = Storage::url($storage);
            } else {

                $input['slip_url'] = secure_url(Storage::url($storage));
            }

            $input['slip'] = $storage;
        }

        $input['brand_id'] = $customer->brand_id;

        $input['game_id'] = $customer->game_id;

        $input['customer_id'] = $customer->id;

        $input['status'] = 0;

        $input['amount'] = str_replace(',', '', $input['amount']);

        $input['username'] = $customer->username;

        $input['name'] = $customer->name;

        if ($input['promotion_id'] != 0) {

            //promotion
            $promotion = Promotion::find($input['promotion_id']);

            $input['bonus'] = Helper::bonusCalculator($input['amount'], $promotion);
        } else {

            $input['bonus'] = 0;
        }

        $customer_deposit = CustomerDeposit::create($input);

        DB::commit();

        return response()->json([
            'code' => 200,
            'data' => $customer_deposit,
        ]);
    }

    public function withdraw(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        $input['amount'] = str_replace(',', '', $input['amount']);

        $brand = Brand::find($customer->brand_id);

        $customer_withdraw_last = CustomerWithdraw::whereBrandId($brand->id)->whereCustomerId($customer->id)->orderBy('created_at', 'desc')->whereStatus(2)->first();

        if ($customer_withdraw_last) {

            $last = Carbon::parse($customer_withdraw_last->updated_at);

            $now = Carbon::parse(date('Y-m-d H:i:s'));

            $duration = $now->diffInSeconds($last);

            if ($duration <= 600) {

                return response()->json([
                    'code' => 400,
                    'data' => '',
                    'msg' => 'คุณทำรายการถอนเร็วเกินไป ทำรายการใหม่อีกครั้ง ภายในเวลา 10 นาที',
                ]);
            }
        }

        $customer_withdraw_unique = CustomerWithdraw::whereBrandId($brand->id)->whereCustomerId($customer->id)->orderBy('created_at', 'desc')->whereNotIn('status', [2, 5])->first();

        if ($customer_withdraw_unique) {

            return response()->json([
                'code' => 400,
                'data' => '',
                'msg' => 'มีรายการถอนที่กำลังตรวจสอบ หรือ โอนเงิน กรุณาติดต่อเจ้าหน้าที่',
            ]);
        }

        if ($input['amount'] < $brand->withdraw_min) {

            return response()->json([
                'code' => 400,
                'data' => '',
                'msg' => 'ถอนขั้นต่ำ ' . $brand->withdraw_min,
            ]);
        }

        $api = new Api($brand);

        $data['username'] = $customer->username;

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $api_credit = $api->credit($data);

        if($api_credit['status'] == false) {

            return response()->json([
                'code' => 0,
                'data' => '',
                'msg' => 'ระบบเกมส์มีปัญหากรุณาลองใหม่อีกครั้งค่ะ',
            ]);

        }

        // dd($api_credit['data']['credit']);

        if ($input['amount'] > $api_credit['data']['credit']) {

            return response()->json([
                'code' => 400,
                'data' => '',
                'msg' => 'ขออภัยค่ะ เครดิตในเกมส์มีไม่ถึง',
            ]);

        }

        $promotion_cost = PromotionCost::whereBrandId($brand->id)->where('promotion_id','!=',0)->whereCustomerId($customer->id)->whereStatus(0)->first();

        //ติดโปร 
        if($promotion_cost){

            $input['promotion_id'] = $promotion_cost->promotion->id;

            $input['promotion_cost_id'] = $promotion_cost->id;

            $credit_cut = $api_credit['data']['credit'];

            $credit_withdraw = ($promotion_cost->promotion->withdraw_max != 0 && $credit_cut > $promotion_cost->promotion->withdraw_max) ? $promotion_cost->promotion->withdraw_max : $api_credit['data']['credit'];

            // pro turn over amount ;
            if($promotion_cost->promotion->type_turn_over == 1) {
    
                $total_turn_over = ($promotion_cost->amount + $promotion_cost->bonus) * $promotion_cost->promotion->turn_over;

                //creditFree 
                if($promotion_cost->promotion->type_promotion == 6) {

                    if($api_credit['data']['credit'] < $total_turn_over) {

                        return response()->json([
                            'code' => 0,
                            'data' => '',
                            'msg' => 'คุณยังทำเทิร์นไม่ถึง กรุณาตรวจสอบค่ะ',
                        ]);

                    }

                    $credit_cut = $api_credit['data']['credit'];

                    $credit_withdraw = ($promotion_cost->promotion->withdraw_max != 0 && $credit_cut > $promotion_cost->promotion->withdraw_max) ? $promotion_cost->promotion->withdraw_max : $api_credit['data']['credit'];

                    //ถอนออโต้ไม่เกินวงเงิน
                    if ($credit_withdraw <= $brand->withdraw_auto_max) {
    
                        $input['status_credit'] = 1;
    
                        $input['type_withdraw'] = 1;
                        
                    } else {
    
                        $input['type_withdraw'] = 2;
    
                        $input['status_credit'] = 1;
                    }

                //normal
                } else {

                    if($api_credit['data']['credit'] < $total_turn_over) {

                        return response()->json([
                            'code' => 0,
                            'data' => '',
                            'msg' => 'คุณยังทำเทิร์นไม่ถึง กรุณาตรวจสอบค่ะ',
                        ]);

                    }

                    $credit_cut = $api_credit['data']['credit'];

                    $credit_withdraw = ($promotion_cost->promotion->withdraw_max != 0 && $credit_cut > $promotion_cost->promotion->withdraw_max) ? $promotion_cost->promotion->withdraw_max : $api_credit['data']['credit'];

                    //ถอนออโต้ไม่เกินวงเงิน
                    if ($credit_withdraw <= $brand->withdraw_auto_max) {
    
                        $input['status_credit'] = 1;
    
                        $input['type_withdraw'] = 1;
                        
                    } else {
    
                        $input['type_withdraw'] = 2;
    
                        $input['status_credit'] = 1;
                    }

                }
                

            } else {

                $credit_cut = $api_credit['data']['credit'];

                $credit_withdraw = ($promotion_cost->promotion->withdraw_max > 0) ? $promotion_cost->promotion->withdraw_max : $api_credit['data']['credit'];

                //ถอนออโต้ไม่เกินวงเงิน
                if ($credit_withdraw <= $brand->withdraw_auto_max) {

                    $input['status_credit'] = 1;

                    $input['type_withdraw'] = 1;
                    
                } else {

                    $input['type_withdraw'] = 2;

                    $input['status_credit'] = 1;
                }

            }
            
        } else {

            $credit_cut = $input['amount'];
    
            $credit_withdraw = $input['amount'];

            //ถอนออโต้ไม่เกินวงเงิน
            if ($input['amount'] <= $brand->withdraw_auto_max) {

                $input['status_credit'] = 1;

                $input['type_withdraw'] = 1;
                
            } else {

                $input['type_withdraw'] = 2;

                $input['status_credit'] = 1;
            }

        }

        $data['amount'] = $credit_cut;

        $api_withdraw = $api->withdraw($data);

        if ($api_withdraw['status'] === false) {

            return response()->json([
                'code' => 400,
                'data' => '',
                'msg' => 'เครดิตไม่พอคุณมี เครดิตคงเหลือ ' . $api_credit['data']['credit'],
            ]);
        }

        if ($brand->line_notify_token) {

            $message = "แจ้งถอน\n";
            $message .= "------------------- \n";
            $message .= "ลูกค้า : " . $customer->name . "(" . $customer->username . ")\n";
            $message .= "จำนวนที่ถอน : " . number_format($input['amount'], 2) . " บาท \n";
            $message .= "------------------- \n";

            if ($promotion_cost) {

                $message .= "โปรโมชั่นที่รับล่าสุด : " . $promotion_cost->promotion->name;
            }

            if ($input['type_withdraw'] == 2) {

                $message .= "อนุมัติการถอนเงิน: https://agent.".  env('APP_NAME') .".".env('APP_DOMAIN')."/withdraw";
            } else {

                $message .= "ประวัติการถอนเงิน: https://agent.".  env('APP_NAME') .".".env('APP_DOMAIN')."/withdraw/history";
            }

            $message = str_replace('%', '', $message);

            $msg = trim($message);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://notify-api.line.me/api/notify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "message=$msg",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $brand->line_notify_token,
                    "Content-Type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        }

        //ไม่ติดโปร

        $input['brand_id'] = $customer->brand_id;

        $input['game_id'] = $customer->game_id;

        $input['customer_id'] = $customer->id;

        $input['status'] = 0;

        $input['amount'] = $credit_withdraw;

        $input['username'] = $customer->username;

        $input['name'] = $customer->name;

        $customer_withdraw = CustomerWithdraw::create($input);

        CustomerCreditHistory::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'customer_withdraw_id' => $customer_withdraw->id,
            'amount_before' => $customer->credit,
            'amount' => $input['amount'],
            'amount_after' => $customer->credit - $input['amount'],
            'type' => 2,
        ]);

        DB::commit();

        if ($input['type_withdraw'] == 2) {

            $message = 'พนักงานกำลังตรวจสอบการถอนเงิน จำนวน ' . number_format($input['amount'], 2) . ' ฿ ให้คุณ username: ' . $customer->username . ' กรุณารอ 2-3 นาที';
        } else {

            $message = 'ระบบกำลังถอนเงินจำนวน ' . number_format($input['amount'], 2) . ' ฿ ให้คุณ username: ' . $customer->username . '';
        }

        return response()->json([
            'code' => 200,
            'data' => $customer_withdraw,
            'msg' => $message,
        ]);
    }

    public function credit(Request $request)
    {

        $input = $request->all();

        $customer = Customer::find($input['customer_id']);

        $out_standing = 0;

        $brand = Brand::find($customer->brand_id);

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['agent_order'] = $customer->agent_order;

        $api_credit = $api->credit($data);

        $data['invite_url'] = 'https://casinoauto.io/'.$brand->subdomain.'/register/invite/'.$customer->id;

        $promotion_last = PromotionCost::with('promotion')->where('promotion_id','!=',0)->whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->first();

        if ($api_credit['status'] == true) {

            $credit['data']['credit'] = $api_credit['data']['credit'];

            $customer->update([
                'credit' => $api_credit['data']['credit']
            ]);
            
        } else {

            $api_credit['data']['credit'] = $customer->credit;
        }

        $customer->update([
            'invite_url' => $data['invite_url'],
        ]);

        if($promotion_last) {

            $credit = $api_credit['data']['credit'];

            if($brand->game_id == 8 && $api_credit['data']['outstanding'] > 0) {

                $credit = $api_credit['data']['outstanding'];

                $out_standing = $api_credit['data']['outstanding'];

                $api_credit['data']['credit'] = $out_standing;

            }

            if($brand->game_id == 12 && $api_credit['data']['outstanding'] > 0) {

                $out_standing = $api_credit['data']['outstanding'];

                $credit = $credit + $out_standing;

                $api_credit['data']['credit'] = $credit;

            }

            if($credit < $promotion_last->promotion->min_break_promotion) {

                $promotion_last->update([
                    'status' => 1,
                ]);

                $promotion_last = null;

            }

        }

        return response()->json([
            'code' => 200,
            'data' => [
                'credit' => $api_credit['data']['credit'],
                'out_standing' => $out_standing,
                'promotion_last' => $promotion_last,
            ],
            'msg' => '',
        ]);
    }

    public function promotionLast(Request $request) {

        $input = $request->all();

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $promotion_last = PromotionCost::with('promotion')->where('promotion_id','!=',0)->whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->first();

        return response()->json([
            'code' => 200,
            'data' => [
                'promotion_last' => $promotion_last,
            ],
            'msg' => '',
        ]);

    }

    public function history(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        $customer_deposits = $customer->deposits->sortByDesc('created_at')->take(5);

        $customer_withdraws = $customer->withdraws->sortByDesc('created_at')->take(5);

        $promotion_credit_frees = Promotion::whereBrandId($customer->brand_id)->whereTypePromotion(6)->get();

        $promotion_costs = PromotionCost::with('promotion')->whereCustomerId($customer->id)->whereIn('promotion_id', $promotion_credit_frees->pluck('id'))->orderBy('created_at','desc')->get()->take(5);

        return response()->json([
            'code' => 200,
            'data' => [
                'customer_deposits' => $customer_deposits,
                'customer_withdraws' => $customer_withdraws,
                'promotion_costs' => $promotion_costs,
            ],
            'msg' => '',
        ]);
    }

    public function profile(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        // 'id','game_id','logo','logo_url','name','line_id','agent_prefix','status_telephone','type_deposit','line_liff_connect'
        $customer = Customer::with('bank')->with('deposits')->with('withdraws')->with('promotion')->with('brand:id,game_id,logo,logo_url,name,line_id,agent_prefix,status_telephone,type_deposit,line_liff_connect')->find($input['customer_id']);

        DB::commit();

        return response()->json([
            'code' => 200,
            'data' => $customer,
            'msg' => '',
        ]);
    }

    public function invite(Request $request)
    {

        $input = $request->all();

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $promotion = Promotion::whereBrandId($brand->id)->whereTypePromotion(5)->first();

        $customer_invited = Customer::select('id','username','name','last_login')->whereInviteId($customer->id)->get();

        if($brand->game_id == 1) {

            $customer_invites = Customer::select('id','username','name','last_login','status_invite')
                ->whereStatusInvite(0)
                ->whereInviteId($customer->id)
                ->with('depositFirst')
                ->with('betDetailInvites')
                ->get();

        } else {
            

            $customer_invites = Customer::select('id','username','name','last_login','status_invite')
                ->whereStatusInvite(0)
                ->whereInviteId($customer->id)
                ->with('depositFirst')
                ->with('betInvites')
                ->get();

        }

        return response()->json([
            'status' => ($brand) ? true : false,
            'data' => [
                'promotion' => $promotion,
                'customer' => $customer,
                'customer_invites' => $customer_invites,
                'customer_invited' => $customer_invited,
            ],
        ]);

    }

    public function inviteStore(Request $request) {

        $input = $request->all();

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $promotion = Promotion::find($input['promotion_id']);

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['amount'] = str_replace(',','',$input['amount']);

        $data['agent_order'] = $customer->agent_order;

        $data['customer_id'] = $customer->id;

        $promotion_cost = PromotionCost::whereCustomerId($customer->id)->where('promotion_id','!=',0)->orderBy('created_at','desc')->first();

        if($promotion_cost) {
            
            $last = Carbon::parse($promotion_cost->updated_at);

            $now = Carbon::parse(date('Y-m-d H:i:s'));

            $duration = $now->diffInSeconds($last);

            if ($duration <= 600) {

                return response()->json([
                    'code' => 400,
                    'data' => '',
                    'msg' => 'คุณทำรายการเร็วเกินไป ทำรายการใหม่อีกครั้ง ภายในเวลา 10 นาที',
                ]);
            }
            
        }

        DB::beginTransaction();

        $api_deposit = $api->deposit($data);
        
        if($api_deposit['status'] === true) {

            $input['bonus'] = str_replace(',','',$input['amount']);

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

            $message = 'ระบบได้ให้โบนัสพิเศษกับลูกค้า ('.$promotion->name.') '.$customer->name.' เป็นจำนวน '.number_format($input['bonus'],2).' เครดิต ';

            $line_api = new LineApi();

            $line_api->token = $brand->line_token;

            $line_api->channel_secret = $brand->line_channel_secret;

            $push = $line_api->pushMessage($customer->line_user_id, $message);

            $customer_invites = Customer::whereInviteId($customer->id)->get();

            if($brand->game_id == 1) {

                if($promotion->type_promotion_invite == 1) {

                    foreach($customer_invites as $customer_invite) {

                        $customer_invite->update([
                            'status_invite' => 1,
                        ]);

                    }

                } else {

                    CustomerBetDetail::whereIn('username', $customer_invites->pluck('username'))->update([
                                'status_invite' => 1,
                            ]);
                    
                }

            } else {

                if($promotion->type_promotion_invite == 1) {

                    foreach($customer_invites as $customer_invite) {

                        $customer_invite->update([
                            'status_invite' => 1,
                        ]);

                    }

                } else {
                
                    $customer_bets = CustomerBet::whereIn('username', $customer_invites->pluck('username'))->get();
                    
                    foreach($customer_bets as $customer_bet) {

                        $customer_bet->update([
                            'turn_over_received' => $customer_bet->turn_over,
                            'status_invite' => 1,
                        ]);

                    }

                }

            }


        }

        DB::commit();

        return response()->json([
            'status' => $api_deposit['status'],
            'data' => [
                
            ]
        ]);

    }

    public function connectLine(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        $customer->update([
            'line_user_id' => $input['line_user_id'],
            'img_url' => $input['img_url']
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

        $message1 .= "Username: " . $customer->username . " \n";

        $message1 .= "Password: " . $customer->password_generate . " \n";

        $message1 .= "กดเติมเงินได้ที่เมนูเติมเงินเลยนะคะ \n";

        if ($brand->game_id == 5) {

            $message1 .= 'ทางเข้าเล่น: https://fastbet98.com/#/';
        }

        $push = $line_api->pushMessage($customer->line_user_id, $message1);

        DB::commit();
    }

    public function serviceCustomer(Request $request) {

        $input = $request->all();

        // DB::beginTransaction();

        $brand = Brand::whereAgentPrefix($input['agent_prefix'])->first();

        if($brand) {

            $customers = Customer::select('id','telephone','name','username')->whereBrandId($brand->id)->get();

            return response()->json([
                'code' => 200,
                'data' => $customers,
                'msg' => 'success',
            ]);

        } else {
        
            // DB::commit();

            return response()->json([
                'code' => 404,
                'data' => '',
                'msg' => 'agent prefix not found',
            ]);

        }

    }

    public function serviceTopUp(Request $request) {

        $input = $request->all();

        $brand = Brand::whereAgentPrefix($input['agent_prefix'])->first();

        if($brand) {

            $customer = Customer::whereUsername($input['username'])->first();

            $api = new Api($brand);
    
            $data['username'] = $customer->username;
    
            $data['amount'] = str_replace(',','',$input['amount']);

            $data['customer_id'] = $customer->id;
    
            if($brand->game_id == 1) {
    
                $data['agent_order'] = $customer->agent_order;
    
            }
    
            $api_deposit = $api->deposit($data);

            if($api_deposit['status'] == true) {

                return response()->json([
                    'code' => 200,
                    'data' => [
                        'credit_after' => $api_deposit['data']['credit']
                    ],
                    'msg' => 'success',
                ]);

            } else {

                return response()->json([
                    'code' => 500,
                    'data' => '',
                    'msg' => 'api server error',
                ]);

            }

        } else {

            return response()->json([
                'code' => 404,
                'data' => '',
                'msg' => 'agent prefix not found',
            ]);

        }

    }

    public function promotionUpdate(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $promotion = Promotion::find($input['promotion_id']);

        $customer = Customer::find($input['customer_id']);

        $customer->update([
            'promotion_id' => $input['promotion_id']
        ]);

        DB::commit();

        return response()->json([
            'code' => 200,
            'data' => $promotion,
            'msg' => '',
        ]);
    }

    public function promotionSelect(Request $request) {

        $input = $request->all();

        $status = 200;

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $promotion = Promotion::find($input['promotion_id']);

        $promotion_cost = PromotionCost::whereCustomerId($customer->id)->where('promotion_id','!=',0)->whereStatus(0)->first();

        $promotion_register = Promotion::whereTypePromotion(3)->whereBrandId($brand->id)->get();

        $promotion_cost_register = PromotionCost::whereCustomerId($customer->id)->where('promotion_id','!=',0)->whereIn('promotion_id', $promotion_register->pluck('id'))->get();

        if($promotion_cost_register->count() > 0 && $promotion->type_promotion == 3) {

            return response()->json([
                'status' => 'error',
                'msg' => 'ขออภัยค่ะ คุณรับโปรโมชั่นสมาชิกใหม่ไปแล้ว'
            ]);

        }

        if($promotion_cost) {

            return response()->json([
                'status' => 'error',
                'msg' => 'ขออภัยค่ะ คุณมีโปรโมชั่นที่กำลังใช้งานอยู่'
            ]);

        }

        DB::beginTransaction();

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['agent_order'] = $customer->agent_order;
        
        $data['customer_id'] = $customer->id;

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $customer_deposit_last = CustomerDeposit::whereCustomerId($customer->id)->whereStatus(1)->orderBy('created_at','desc')->first();

        $api_credit = $api->credit($data);

        if($api_credit['status'] === true) {

            //api success
            $customer_deposit_last = CustomerDeposit::whereCustomerId($customer->id)->whereStatus(1)->orderBy('created_at','desc')->first();

            if($customer_deposit_last) {

                $amount = $api_credit['data']['credit'];

                if($amount >= $promotion->min) {
        
                    $promotion_id = $promotion->id;
        
                    $bonus = Helper::bonusCalculator($amount, $promotion);
        
                    $data['amount'] = $bonus;

                    $api_deposit = $api->deposit($data);

                    if($api_deposit['status'] == true) {

                        $customer_deposit_last->update([
                            'promotion_id' => $promotion->id
                        ]);
        
                        PromotionCost::create([
                            'brand_id' => $brand->id,
                            'promotion_id' => $promotion->id,
                            'customer_id' => $customer->id,
                            'username' => $customer->username,
                            'amount' => $amount,
                            'bonus' => $bonus,
                            'status' => 0,
                        ]);    

                        CustomerCreditHistory::create([
                            'brand_id' => $brand->id,
                            'customer_id' => $customer->id,
                            'customer_deposit_id' => 0,
                            'promotion_id' => $promotion->id,
                            'amount_before' => $amount,
                            'amount' => $bonus,
                            'amount_after' => $amount + $bonus,
                            'type' => 1,
                        ]);

                        $status = 'success';

                        $message = 'คุณได้รับโปรโมชั่น '.$promotion->name.' เรียบร้อยแล้วขอบคุณค่ะ';

                    } else {

        
                        $status = 'error';

                        $message = 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ';

                    }
        
                } else {
        
                    $status = 'error';

                    $message = 'เครดิตขั้นต่ำต้องมากกว่า '.$promotion->min;
        
                }

            } else {
        
                $status = 'error';

                $message = 'กรุณาเติมเงินขั้นต่ำ '.$promotion->min;

            }

            

        } else {

            $status = 'warning';

            $message = 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ';

        }

        DB::commit();

        return response()->json([
            'status' => $status,
            'msg' => $message,
        ]);

    }

    public function creditFree(Request $request) {

        $input = $request->all();

        $credit_free = CreditFree::whereCode($input['code'])->first();

        if(!$credit_free) {

            return response()->json([
                'status' => 500,
                'message' => 'ขออภัยค่ะ ไม่พบโค้ดที่ลูกค้าระบุ'
            ]);

        }

        if($credit_free->status == 1) {

            return response()->json([
                'status' => 500,
                'message' => 'ขออภัยค่ะ โค้ดนีถูกใช้งานไปแล้ว'
            ]);

        }

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $promotion_credit_frees = Promotion::whereBrandId($brand->id)->whereTypePromotion(6)->get();

        $promotion_cost = PromotionCost::whereCustomerId($customer->id)->where('promotion_id','!=',0)->whereStatus(0)->first();

        $promotion = Promotion::find($credit_free->promotion_id);

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['agent_order'] = $customer->agent_order;
        
        $data['customer_id'] = $customer->id;

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $api_credit = $api->credit($data);

        if($promotion_cost && $api_credit['data']['credit'] > $promotion_cost->promotion->min_break_promotion) {

            return response()->json([
                'status' => 500,
                'message' => 'ลูกค้าติดโปรโมชั่น '.$promotion_cost->promotion->name.' หรือ เครดิตยังไม่น้อยกว่า '.$promotion_cost->promotion->min_break_promotion
            ]);

        }

        if($api_credit['status'] === true) {

            //api success
            $amount = $api_credit['data']['credit'];
    
            $promotion_id = $promotion->id;

            $bonus = Helper::bonusCalculator($amount, $promotion);

            $data['amount'] = $bonus;

            $api_deposit = $api->deposit($data);

            if($brand->game_id == 1) {

                if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                    //customer online
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {
                    //api error
                    // $bank_account_transaction->update([
                    //     'status' => 0,
                    // ])
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                }

                $refer_id = 0;
    
            } else {
    
                if($api_deposit['status'] === false) {
    
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                }
    
                if(isset($api_deposit['data']['ref'])) {
    
                    $refer_id = $api_deposit['data']['ref'];
    
                }
    
            }

            if($api_deposit['status'] == true) {
        
                $customer->update([
                    'promotion_id' => 0,
                    'refer_id' => $refer_id,
                ]);

                $promotion_cost = PromotionCost::create([
                    'brand_id' => $brand->id,
                    'promotion_id' => $promotion->id,
                    'customer_id' => $customer->id,
                    'username' => $customer->username,
                    'amount' => 0,
                    'bonus' => $bonus,
                    'status' => 0,
                ]);

                PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->where('id','!=',$promotion_cost->id)->update([
                    'status' => 1,
                ]);

                CustomerCreditHistory::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'promotion_id' => $promotion->id,
                    'customer_deposit_id' => 0,
                    'amount_before' => $amount,
                    'amount' => $bonus,
                    'amount_after' => $amount + $bonus,
                    'type' => 1,
                ]);

                $credit_free->update([
                    'customer_id' => $customer->id,
                    'status' => 1,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'คุณได้รับโปรโมชั่น "'.$promotion->name.'" เรียบร้อยแล้วขอบคุณค่ะ'
                ]);

            } else {

                return response()->json([
                    'status' => 500,
                    'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                ]);

            }

        } else {

            return response()->json([
                'status' => 500,
                'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
            ]);

        }

    }

    public function wheel(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        $customer_wheels = CustomerWheel::with('wheelSlotConfig')->with('promotion')->whereCustomerId($customer->id)->orderBy('created_at','desc')->take(10)->get();

        $brand = Brand::find($customer->brand_id);

        $wheel_config = WheelConfig::with('wheelSlotEights','wheelSlotEights.promotion')->with('wheelSlotTens','wheelSlotTens.promotion')->whereBrandId($brand->id)->first();

        $customer_wheel_s = CustomerWheel::whereCustomerId($customer->id)->orderBy('created_at','desc')->get();

        if($customer_wheel_s->count() > 0) {

            $now = Carbon::now();

            $time_hour = Carbon::createFromFormat('Y-m-d H:i:s',$customer_wheel_s->first()->created_at)->addHours($wheel_config->time_hour);
            // เช็คช่วงเวลาการหมุน
            if($time_hour > $now) {
                $time_hour_status = 0;
                $time_hour = 'รอบในการหมุนครั้งต่อไปคือเวลา '.$time_hour->format('d/m/Y H:i:s');
            } else {
                $time_hour_status = 1;
                $time_hour = '';
            }

        } else {

            $time_hour_status = 1;
            $time_hour = '';

        }

        $result = [];

        if($wheel_config) {

            if($wheel_config->slot_amount == 8) {

                $samples = [];

                $weights = [];

                foreach($wheel_config->wheelSlotEights as $wheel_slot) {

                    // echo $wheel_slot->chance.'</br>';

                    array_push($samples, $wheel_slot->id);

                    array_push($weights, $wheel_slot->chance);

                }

                $result = $this->w_rand($samples,$weights);

            } else if($wheel_config->slot_amount == 10) {

                $samples = [];

                $weights = [];

                foreach($wheel_config->wheelSlotTens as $wheel_slot) {

                    // echo $wheel_slot->chance.'</br>';

                    array_push($samples, $wheel_slot->id);

                    array_push($weights, $wheel_slot->chance);

                }

                $result = $this->w_rand($samples,$weights);

            }

        }

        DB::commit();

        return response()->json(['wheel' => $wheel_config,'result' => $result,'wheel_amount' => $customer->wheel_amount,'wheel_score' => $customer->wheel_score,'customer_wheels' => $customer_wheels,'time_hour_status' => $time_hour_status, 'time_hour' => $time_hour]);

    }

    public function wheelStore(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $wheel_config = WheelConfig::whereBrandId($brand->id)->first();

        $customer_wheels = CustomerWheel::whereCustomerId($customer->id)->orderBy('created_at','desc')->get();

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['agent_order'] = $customer->agent_order;
        
        $data['customer_id'] = $customer->id;

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $wheel_slot_config = WheelSlotConfig::find($input['wheel_slot_config_id']);

        if($wheel_slot_config->type == 0) {

            //โปรโมชั่นเครดิตฟรี
            $promotion = Promotion::find($wheel_slot_config->promotion_id);
    
            $promotion_id = $promotion->id;

            $bonus = Helper::bonusCalculator(0, $promotion);

            $data['amount'] = $bonus;

            $api_deposit = $api->deposit($data);

            if($brand->game_id == 1) {

                if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                    //customer online
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {
                    //api error
                    // $bank_account_transaction->update([
                    //     'status' => 0,
                    // ])
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                }

                $refer_id = 0;
    
            } else {
    
                if($api_deposit['status'] === false) {
    
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                }
    
                if(isset($api_deposit['data']['ref'])) {
    
                    $refer_id = $api_deposit['data']['ref'];
    
                }
    
            }

            if($api_deposit['status'] == true) {
        
                $customer->update([
                    'promotion_id' => 0,
                    'refer_id' => $refer_id,
                ]);

                $promotion_cost = PromotionCost::create([
                    'brand_id' => $brand->id,
                    'promotion_id' => $promotion->id,
                    'customer_id' => $customer->id,
                    'username' => $customer->username,
                    'amount' => 0,
                    'bonus' => $bonus,
                    'status' => 0,
                ]);

                PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->where('id','!=',$promotion_cost->id)->update([
                    'status' => 1,
                ]);

                CustomerCreditHistory::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'customer_deposit_id' => 0,
                    'amount_before' => $customer->amount,
                    'amount' => $bonus,
                    'amount_after' => $customer->amount + $bonus,
                    'type' => 1,
                ]);

                CustomerWheel::create([
                    'customer_id' => $customer->id,
                    'wheel_slot_config_id' => $wheel_slot_config->id,
                    'wheel_slot_config_type' => $wheel_slot_config->type,
                    'promotion_id' => $wheel_slot_config->promotion_id,
                    'other' => null,
                    'credit' => null,
                ]);

                DB::commit();

                $customer->update([
                    'wheel_amount' => $customer->wheel_amount - 1,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'คุณได้รับโปรโมชั่น "'.$promotion->name.'" เรียบร้อยแล้วขอบคุณค่ะ'
                ]);

            } else {

                return response()->json([
                    'status' => 500,
                    'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                ]);

            }


        } else if ($wheel_slot_config->type == 1) {

            //โบนัสเครดิต
            $data['amount'] = $wheel_slot_config->credit;

            $api_deposit = $api->deposit($data);

            if($brand->game_id == 1) {

                if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                    //customer online
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {

                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                }

                $refer_id = 0;
    
            } else {
    
                if($api_deposit['status'] === false) {
    
                    return response()->json([
                        'status' => 500,
                        'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                    ]);
    
                }
    
                if(isset($api_deposit['data']['ref'])) {
    
                    $refer_id = $api_deposit['data']['ref'];
    
                }
    
            }

            if($api_deposit['status'] == true) {

                CustomerWheel::create([
                    'customer_id' => $customer->id,
                    'wheel_slot_config_id' => $wheel_slot_config->id,
                    'wheel_slot_config_type' => $wheel_slot_config->type,
                    'promotion_id' => null,
                    'other' => null,
                    'credit' => $data['amount'],
                ]);

                PromotionCost::create([
                    'brand_id' => $brand->id,
                    'promotion_id' => 0,
                    'customer_id' => $customer->id,
                    'username' => $customer->username,
                    'amount' => 0,
                    'bonus' => $data['amount'],
                    'status' => 0,
                ]);

                $customer->update([
                    'wheel_amount' => $customer->wheel_amount - 1,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'คุณได้รับโบนัส "'.$data['amount'].'" เรียบร้อยแล้วขอบคุณค่ะ'
                ]);

            } else {

                return response()->json([
                    'status' => 500,
                    'message' => 'ระบบเครดิตมีปัญหากรุณาลองใหม่ภายหลังนะคะ'
                ]);

            }


        } else if ($wheel_slot_config->type == 2) {

            //ของรางวัลอื่นๆ
            CustomerWheel::create([
                'customer_id' => $customer->id,
                'wheel_slot_config_id' => $wheel_slot_config->id,
                'wheel_slot_config_type' => $wheel_slot_config->type,
                'promotion_id' => null,
                'other' => $wheel_slot_config->promotion_other,
                'credit' => null,
            ]);

            $customer->update([
                'wheel_amount' => $customer->wheel_amount - 1,
            ]);

            DB::commit();

        }

        DB::commit();

        return response()->json();

    }

    private function w_rand($samples, $weights)
    {
        if (count($samples) != count($weights)) {
            return null;
        }
        
        $sum  = array_sum($weights) * 100;
        $rand = mt_rand(1, $sum);
        
        foreach ($weights as $i => $w) {
            $weights[$i] = $w * 100 + ($i > 0 ? $weights[$i - 1] : 0);
            if ($rand <= $weights[$i]) {
                return $samples[$i];
            }
        }
    }

    public function gameList($customer_id,$type_game) {

        $customer = Customer::find($customer_id);

        $brand = Brand::find($customer->brand_id);

        $amb_king_api = new AmbKingApi();

        $amb_king_api->agent = $brand->agent_username;

        $amb_king_api->hash = $brand->hash;

        $amb_king_api->key = $brand->app_id;

        $data['game'] = ($type_game == 1) ? 'slot' : 'casino';

        $amb_king_api_game_list = $amb_king_api->gameList($data);

        if($amb_king_api_game_list['code'] == 0) {
            return response()->json($amb_king_api_game_list);
        } 

    }

    public function startGame(Request $request) {

        $input = $request->all();

        $customer = Customer::find($input['user_id']);

        $brand = Brand::find($customer->brand_id);

        $amb_king_api = new AmbKingApi();

        $amb_king_api->agent = $brand->agent_username;

        $amb_king_api->hash = $brand->hash;

        $amb_king_api->key = $brand->app_id;

        $data['game_type'] = 'slot';

        $data['username'] = $customer->username;

        $data['provider'] = $input['provider'];

        $data['gameId'] = $input['game_id'];

        $data['brand'] = $brand->subdomain;

        $amb_king_api_start_game = $amb_king_api->startGame($data);

        if($amb_king_api_start_game['code'] == 0) {
            return response()->json($amb_king_api_start_game);
        } 

    } 
}
