<?php

namespace App\Http\Controllers\Support;

use App\User;
use App\UserRole;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index() {
        
        $users = new User();

        if (Auth::user()->user_role_id == 6) {
            # code...
            $array_roles = [4,6];
        } else {
            $array_roles = [1,4,6];
        }

        if(isset($_GET['brand_id'])) {

            $users = $users->whereBrandId($_GET['brand_id']);

            $brand_select = $_GET['brand_id'];

        } else {

            $brand_select = '';

        }

        if(isset($_GET['user_role_id'])) {

            $users = $users->whereUserRoleId($_GET['user_role_id']);

            $user_role_select = $_GET['user_role_id'];

        } else {

            $user_role_select = '';

        }

        $users = $users->whereIn('user_role_id',$array_roles);

        $users = $users->orderBy('user_role_id')->paginate(10);

        $brands = Brand::whereIn('type_api',['1','2'])->get();

        $user_roles = UserRole::whereIn('id',$array_roles)->get();

        return view('support.users.index', compact('users','brands','user_roles','brand_select','user_role_select'));

    }

    public function store(UserRequest $request) {

        $input = $request->all();

        DB::beginTransaction();

        if(isset($input['img'])) {
            
            //put new image 
            $api_upload = Helper::uploadApiService('img', 'casinoauto.img');

            $input['img_url'] = $api_upload['data']['url'];

            $input['img'] = $api_upload['data']['name'];

        } else {

            $input['img_url'] = 'https://via.placeholder.com/150';

            $input['img'] = '';

        }

        $input['password'] = \bcrypt($input['password']);

        User::create($input);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มผู้ใช้งานสำเร็จ');

        return redirect()->back();

    }

    public function resetPassword(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        User::find($input['user_id'])->update([
            'password' => \bcrypt('Aa123123'),
        ]);

        DB::commit();

        \Session::flash('alert-success', 'รีเซ็ตรหัสผ่านเรียบร้อย');

        return \redirect()->back();
        
    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $user = User::find($input['user_id']);

        if(isset($input['img'])) {

            //put new image 
            $api_upload = Helper::uploadApiService('img', 'casinoauto.img');

            $input['img_url'] = $api_upload['data']['url'];

            $input['img'] = $api_upload['data']['name'];

        } else {

            $input['img'] = $user->img;

            $input['img_url'] = $user->img_url;

        }

        $user->update($input);

        DB::commit();

        \Session::flash('alert-success', 'รีเซ็ตรหัสผ่านเรียบร้อย');

        return \redirect()->back();

    }

    public function updateStatus(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        User::find($input['user_id'])->update([
            'status' => $input['status']
        ]);

        DB::commit();

        \Session::flash('alert-success', 'รีเซ็ตรหัสผ่านเรียบร้อย');

        return \redirect()->back();

    }

    public function delete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $user = User::find($input['user_id']);

        Storage::disk('public')->delete($user->logo);

        $user->delete();

        DB::commit();

        \Session::flash('alert-warning', 'ลบเกมส์เรียบร้อย');

        return \redirect()->back();

    }
}
