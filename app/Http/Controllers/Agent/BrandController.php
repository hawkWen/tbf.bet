<?php

namespace App\Http\Controllers\Agent;

use App\Models\Game;
use App\Models\Brand;
use App\Models\BrandRank;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    //
    public function index() {

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        return view('agent.brands.index', \compact('brand','bank_accounts'));

    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        if(isset($input['logo'])) {

            $delete = Storage::disk('public')->delete($brand->logo);

            $storage  = Storage::disk('public')->put('brands', $request->file('logo'));

            if(env('APP_ENV') == 'local') {

                $input['logo_url'] = Storage::url($storage);

            } else {

                $input['logo_url'] = secure_url(Storage::url($storage));

            }

            $input['logo'] = $storage;

        } else {

            $input['logo'] = $brand->logo;

            $input['logo_url'] = $brand->logo_url;

        }

        $input['withdraw_auto_max'] = (isset($input['withdraw_auto_max'])) ? str_replace(',','',$input['withdraw_auto_max']) : $brand->withdraw_auto_max;

        $input['withdraw_min'] = (isset($input['withdraw_min'])) ? str_replace(',','',$input['withdraw_min']) : $brand->withdraw_min;

        $brand->update($input);

        DB::commit();

        \Session::flash('alert-success', 'ตั้งค่าแบรนด์สำเร็จ');

        return \redirect()->back();

    }

    public function updateStatusRank(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find(Auth::user()->brand_id);

        $brand->update([
            'status_rank' => $input['status'],
        ]);

        // if($brand->ranks->count() == 0 && $input['status'] == 1) {

            // $ranks = [
            //     'bronze',
            //     'silver',
            //     'gold',
            //     'platinum',
            //     'diamond',
            //     'conqueror'
            // ];

            // foreach($ranks as $rank) {

            //     BrandRank::create([
            //         'brand_id' => $brand->id,
            //         'rank' => $rank,
            //         'min' => 0,
            //         'reward' => 0,
            //         'description' => '',
            //     ]);

            // }

        // }

        DB::commit();

    }

    public function updateRank(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        foreach($input['rank_ids'] as $rank) {

            $brand_rank = BrandRank::find($rank);

            $brand_rank->update([
                'min' => str_replace(',','',$input['min'][$rank]),
                'reward' => str_replace(',','',$input['reward'][$rank]),
                'description' => $input['description'][$rank],
            ]);

        }

        DB::commit();

        \Session::flash('alert-success', 'ตั้งค่า Rank สำเร็จ');

        return \redirect()->back();

    }
}
