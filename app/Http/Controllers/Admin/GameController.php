<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    //
    public function index() {

        $games = Game::paginate(6);

        return view('admin.games.index', \compact('games'));
        
    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if($input['logo'] !== 'null') {
            
            //put new image 
            $storage  = Storage::disk('public')->put('games', $request->file('logo'));

            // return response()->json($storage);

            if(env('APP_ENV') == 'local') {

                $input['logo_url'] = Storage::url($storage);

            } else {

                $input['logo_url'] = secure_url(Storage::url($storage));

            }

            $input['logo'] = $storage;

        } else {

            $input['logo_url'] = 'https://via.placeholder.com/150';

            $input['logo'] = '';

        }

        Game::create($input);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มเกมส์สำเร็จ');

        return redirect()->back();

    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $game = Game::find($input['game_id']);

        if(isset($input['logo'])) {

            $delete = Storage::disk('public')->delete($game->logo);

            $storage  = Storage::disk('public')->put('games', $request->file('logo'));

            if(env('APP_ENV') == 'local') {

                $input['logo_url'] = Storage::url($storage);

            } else {

                $input['logo_url'] = secure_url(Storage::url($storage));

            }

            $input['logo'] = $storage;

        } else {

            $input['logo'] = $game->logo;

            $input['logo_url'] = $game->logo_url;

        }

        $game->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขเกมส์สำเร็จ');

        return \redirect()->back();

    }

    public function delete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $game = Game::find($input['game_id']);

        Storage::disk('public')->delete($game->logo);

        $game->delete();

        DB::commit();

        \Session::flash('alert-warning', 'ลบเกมส์เรียบร้อย');

        return \redirect()->back();

    }
}
