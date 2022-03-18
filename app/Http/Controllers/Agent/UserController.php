<?php

namespace App\Http\Controllers\Agent;

use App\User;
use App\UserRole;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //
    public function index() {

        $users = User::where('brand_id','=',Auth::user()->brand_id)->paginate(10);

        $brand = Brand::find(Auth::user()->brand_id);

        if(Auth::user()->user_role_id == 4) {
            $user_roles = UserRole::whereIn('id',[2,3,4])->get();
        } else {
            $user_roles = UserRole::where('id','>',Auth::user()->user_role_id)->get();    
        }

        return view('agent.users.index', compact('users','brand','user_roles'));

    }

    public function store(UserRequest $request) {

        $input = $request->all();

        DB::beginTransaction();

        if(isset($input['img'])) {
            
            //put new image 
            $storage  = Storage::disk('public')->put('users', $request->file('img'));

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

    public function changePassword(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        User::find($input['user_id'])->update([
            'password' => \bcrypt($input['password']),
        ]);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขรหัสผ่านเรียบร้อย');

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

    public function event(Request $request, $user_id) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $user = User::find($user_id);

        $user_events = UserEvent::whereUserId($user->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->paginate(30)->appends(request()->except('page'));

        return view('agent.users.event', compact('brand','dates','user','user_events'));

    }

    public function eventExcel(Request $request, $user_id) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $user = User::find($user_id);

        $user_events = UserEvent::whereUserId($user->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->get();

        return view('agent.users.event-excel', compact('brand','dates','user','user_events'));

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
