<?php

namespace App\Http\Controllers\Agent;

use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\Promotion;
use App\Models\CreditFree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CreditFreeController extends Controller
{
    //
    public function index(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $credit_frees = CreditFree::whereBrandId($brand->id)->orderBy('created_at','desc')->paginate(20);

        $credit_free_useds = CreditFree::whereBrandId($brand->id)->orderBy('created_at','desc')->whereStatus(1)->paginate(20);

        $promotions = Promotion::whereBrandId($brand->id)->whereTypePromotion(6)->get();

        return view('agent.credit-frees.index', compact('credit_frees','credit_free_useds','promotions','dates','brand'));

    }

    public function generate(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if(empty($input['promotion_id'])) {

            return \redirect()->back()->withErrors('ไม่มีโปรโมชั่นเครดิตฟรี กรุณาสร้างที่จัดการเมนูโปรโมชั่น');

        }

        $input['brand_id'] = Auth::user()->brand_id;

        $input['user_id'] = Auth::user()->id;

        $codes = collect([]);

        for($i=0;$i<=$input['number'];$i++) {

            $code = Helper::generateCode();

            CreditFree::create([
                'brand_id' => Auth::user()->brand_id,
                'user_id' => Auth::user()->id,
                'promotion_id' => $input['promotion_id'], 
                'code' => $code,
            ]);

            $codes->push($code);

        }

        $promotion = Promotion::find($input['promotion_id']);

        DB::commit();

        return view('agent.credit-frees.excel', \compact('codes','promotion'));

    }    
}
