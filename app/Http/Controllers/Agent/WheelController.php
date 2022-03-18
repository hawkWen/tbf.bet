<?php

namespace App\Http\Controllers\Agent;

use App\Models\Brand;
use App\Models\Promotion;
use App\Models\WheelConfig;
use Illuminate\Http\Request;
use App\Models\WheelSlotConfig;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WheelController extends Controller
{
    //
    public function index() {

        $brand = Brand::find(Auth::user()->brand_id);

        $wheel_config = WheelConfig::whereBrandId($brand->id)->first();

        $promotions = Promotion::whereBrandId($brand->id)->whereTypePromotion(6)->get();

        return view('agent.wheels.index', compact('brand','wheel_config','promotions'));

    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $input['slot_amount'] = 8;

        $wheel_config = WheelConfig::create($input);

        $input['slot_amounts'][0] = 8;
        $input['slot_amounts'][1] = 10;

        foreach($input['slot_amounts'] as $slot_amount) {
            
            $input['slot_amount'] = $slot_amount;

            // echo $slot_amount;

            for($i=1;$i<=$slot_amount; $i++) {

                WheelSlotConfig::create([
                    'wheel_config_id' => $wheel_config->id,
                    'slot_amount' => $slot_amount,
                    'promotion_id' => 0,
                    'chance' => ($i == 1) ? 100 : 0,
                    'type' => 1,
                ]);

            }

        }

        // dd($input);

        DB::commit();

        \Session::flash('alert-success', 'สร้างวงล้อเสร็จเรียบร้อย');

        return redirect()->back();

    }

    public function updateSlot(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $wheel_config = WheelConfig::find($input['wheel_config_id']);

        $wheel_config->update([
            'slot_amount' => $input['amount']
        ]);

        DB::commit();

        return response()->json([
            'status' => true,
        ]);
    
    }

    public function updateStatus(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        WheelConfig::find($input['wheel_config_id'])->update([
            'status' => $input['status']
        ]);

        DB::commit();

        return redirect()->back();

    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $wheel_config = WheelConfig::find($input['wheel_config_id']);

        $input['amount_condition'] = str_replace(',','', $input['amount_condition']);

        $wheel_config->update($input);

        foreach($input['wheel_slot_configs'] as $key=>$wheel_slot) {


            if($wheel_slot['type'] == 0) {

                WheelSlotConfig::find($wheel_slot['id'])->update([
                    'type' => $wheel_slot['type'],
                    'promotion_id' => $wheel_slot['promotion'],
                    'chance' => $wheel_slot['chance']
                ]);
                    
            } else if ($wheel_slot['type'] == 1) {
                
                WheelSlotConfig::find($wheel_slot['id'])->update([
                    'type' => $wheel_slot['type'],
                    'credit' => str_replace(',','',$wheel_slot['credit']),
                    'chance' => $wheel_slot['chance']
                ]);

            } else if ($wheel_slot['type'] == 2) {

                WheelSlotConfig::find($wheel_slot['id'])->update([
                    'type' => $wheel_slot['type'],
                    'promotion_other' => $wheel_slot['promotion_other'],
                    'chance' => $wheel_slot['chance']
                ]);

            }

        }

        DB::commit();

        return response()->json([
            'status' => true,
        ]);
        
    }

}   
