<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Api;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    //
    public function customer(Request $request, $token) {

        $input = $request->all();

        // DB::beginTransaction();

        $brand = Brand::whereAgentPrefix($input['agent_prefix'])->first();

        // dd($brand,$input['agent_prefix']);

        if($brand->app_id != $token) {

            return response()->json([
                'code' => 500,
                'data' => '',
                'msg' => 'token invalid',
            ]);

        }

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

    public function promotions(Request $request,$token) {

        $input = $request->all();

        $brand = Brand::whereAgentPrefix($input['agent_prefix'])->first();

        if($brand->app_id != $token) {

            return response()->json([
                'code' => 500,
                'data' => '',
                'msg' => 'token invalid',
            ]);

        }

        if($brand) {

            $promotions = Promotion::whereBrandId($brand->id)->whereTypePromotion(6)->get();

            return response()->json([
                'code' => 200,
                'data' => $promotions,
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

    public function topUp(Request $request,$token) {

        $input = $request->all();

        $brand = Brand::whereAgentPrefix($input['agent_prefix'])->first();

        if($brand->app_id != $token) {

            return response()->json([
                'code' => 500,
                'data' => '',
                'msg' => 'token invalid',
            ]);

        }

        if($brand) {

            $api = new Api($brand);

            $customer = Customer::whereUsername($input['username'])->first();

            if(!$customer) {

                return response()->json([
                    'code' => 404,
                    'data' => '',
                    'msg' => 'Customer Not Found',
                ]);

            }

            $promotion_cost = PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->first();

            $data['username'] = $customer->username;
    
            $api_credit = $api->credit($data);

            if($promotion_cost && $api_credit['data']['credit'] > 20) {

                return response()->json([
                    'code' => 500,
                    'data' => '',
                    'msg' => 'ลูกค้าติดโปรโมชั่น '.$promotion_cost->promotion->name.' หรือ เครดิตยังไม่น้อยกว่า 20',
                ]);
    
            }

            $promotion = Promotion::find($input['promotion_id']);
    
            $input['bonus'] = Helper::bonusCalculator(0,$promotion);
    
            $data['amount'] = $input['bonus'];

            $data['customer_id'] = $customer->id;
    
            if($brand->game_id == 1) {
    
                $data['agent_order'] = $customer->agent_order;
    
            }
    
            $api_deposit = $api->deposit($data);

            if($api_deposit['status'] == true) {

                $customer->update([
                    'promotion_id' => 0,
                    'ref_id' => $api_deposit['data']['ref'],
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

}
