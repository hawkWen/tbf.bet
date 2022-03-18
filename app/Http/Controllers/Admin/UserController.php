<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UserRole;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //
    public function index() {

        $users = User::paginate(10);

        $brands = Brand::whereIn('type_api',['1','2'])->get();

        $user_roles = UserRole::all();

        return view('admin.users.index', compact('users','brands','user_roles'));

    }

    public function store(UserRequest $request) {

        $input = $request->all();

        DB::beginTransaction();

        if(isset($input['img'])) {
            
            //put new image 
            $storage  = Storage::disk('public')->put('users', $request->file('img'));

            // return response()->json($storage);

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
            'password' => \bcrypt('Aa123123++'),
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

            $delete = Storage::disk('public')->delete($user->img);

            $storage  = Storage::disk('public')->put('users', $request->file('img'));

            if(env('APP_ENV') == 'local') {

                $input['img_url'] = Storage::url($storage);

            } else {

                $input['img_url'] = secure_url(Storage::url($storage));

            }

            $input['img'] = $storage;

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
