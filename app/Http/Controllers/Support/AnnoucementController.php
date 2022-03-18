<?php

namespace App\Http\Controllers\Support;

use App\Models\Annoucement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnnoucementController extends Controller
{
    //
    public function index() {

        $annoucements = Annoucement::all();

        return view('support.annoucements.index',compact('annoucements'));
    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $input['user_id'] = Auth::user()->id;

        Annoucement::create($input);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มสำเร็จ');

        return redirect()->back();
    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $annoucement = Annoucement::find($input['annoucement_id']);

        $annoucement->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขสำเร็จ');

        return redirect()->back();

    }

    public function delete($annoucement_id) {

        $input = $request->all();

        DB::beginTransaction();

        $annoucement = Annoucement::find($input['annoucement_id']);

        $annoucement->delete();

        DB::commit();

        \Session::flash('alert-success', 'ลบสำเร็จ');

        return redirect()->back();

    }
}
