<?php

namespace App\Http\Controllers\Super;

use App\User;
use App\Models\Brand;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    //
    public function index(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::whereIn('type_api',[1,2])->get();

        return view('super.home',\compact('dates','brands'));

    }

    public function changePassword(Request $request) {
        
        $input = $request->all();

        DB::beginTransaction();

        $admin = User::find(auth()->user()->id);

        if (!Hash::check($input['password_old'], $admin->password)) {
            // The passwords match...
            return \redirect()->back()->withErrors('รหัสผ่านเดิมไม่ถูกต้อง');
        }

        $admin->update([
            'password' => \bcrypt($input['password'])
        ]);

        DB::commit();

        \Session::flash('alert-success', 'เปลี่ยนรหัสผ่านสำเร็จ');

        return \redirect()->back();

    }
}
