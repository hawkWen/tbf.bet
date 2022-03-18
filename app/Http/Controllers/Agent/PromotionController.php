<?php

namespace App\Http\Controllers\Agent;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    //
    public function index() {

        $brand = Brand::find(Auth::user()->brand_id);

        $promotions = Promotion::whereBrandId(Auth::user()->brand_id)->get();

        return view('agent.promotions.index', \compact('promotions','brand'));

    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if(isset($input['img'])) {
            
            $storage  = Storage::disk('public')->put('promotions', $request->file('img'));

            if(env('APP_ENV') == 'local') {

                $input['img_url'] = Storage::url($storage);

            } else {

                $input['img_url'] = secure_url(Storage::url($storage));

            }

            $input['img'] = $storage;

        } else {

            $input['img_url'] = 'https://via.placeholder.com/150';

            $input['img'] = '';

        }

        $input['brand_id'] = Auth::user()->brand_id;

        $input['cost'] = str_replace(',','',$input['cost']);

        $input['min'] = str_replace(',','',$input['min']);

        $input['min_break_promotion'] = str_replace(',','',$input['min_break_promotion']);

        $input['max'] = str_replace(',','',$input['max']);

        $input['withdraw_max'] = str_replace(',','', $input['withdraw_max']);

        $input['turn_over'] = str_replace(',','',$input['turn_over']);

        $brand = Promotion::create($input);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มโปรโมชั่นสำเร็จ');

        return \redirect()->back();

    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $promotion = Promotion::find($input['promotion_id']);

        if(isset($input['img'])) {

            $delete = Storage::disk('public')->delete($promotion->img);

            $storage  = Storage::disk('public')->put('promotions', $request->file('img'));

            if(env('APP_ENV') == 'local') {

                $input['img_url'] = Storage::url($storage);

            } else {

                $input['img_url'] = secure_url(Storage::url($storage));

            }

            $input['img'] = $storage;

        } else {

            $input['img'] = $promotion->img;

            $input['img_url'] = $promotion->img_url;

        }

        $input['brand_id'] = Auth::user()->brand_id;

        $input['cost'] = str_replace(',','',$input['cost']);

        $input['min'] = str_replace(',','',$input['min']);

        $input['max'] = str_replace(',','',$input['max']);

        $input['withdraw_max'] = str_replace(',','', $input['withdraw_max']);

        $input['turn_over'] = str_replace(',','',$input['turn_over']);

        // dd($input);

        $promotion->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขโปรโมชั่นสำเร็จ');

        return \redirect()->back();

    }

    public function updateStatus(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if($input['status'] == 0) {

            $customers = Customer::wherePromotionId($input['promotion_id'])->update([
                'promotion_id' => 0, 
            ]);

        }

        Promotion::find($input['promotion_id'])->update([
            'status' => $input['status']
        ]);

        DB::commit();

    }

    public function clear(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        PromotionCost::find($input['promotion_cost_id'])->update([
            'status' => 1,
        ]);

        DB::commit();

    }

    public function delete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $promotion = Promotion::find($input['promotion_id']);

        Storage::disk('public')->delete($promotion->img);

        $customers = Customer::wherePromotionId($input['promotion_id'])->update([
            'promotion_id' => 0, 
        ]);

        $promotion->delete();

        DB::commit();

        \Session::flash('alert-warning', 'ลบโปรโมชั่นเรียบร้อย');

        return \redirect()->back();

    }
}
