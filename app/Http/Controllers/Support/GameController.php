<?php

namespace App\Http\Controllers\Support;

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

        return view('support.games.index', \compact('games'));
        
    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if($input['logo'] !== 'null') {
            
            //put new image 
            $api_upload = Helper::uploadApiService('logo', 'casinoauto.logo');

            $input['logo_url'] = $api_upload['data']['url'];

            $input['logo'] = $api_upload['data']['name'];

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

            //put new image 
            $api_upload = Helper::uploadApiService('logo', 'casinoauto.game');

            $input['logo_url'] = $api_upload['data']['url'];

            $input['logo'] = $api_upload['data']['name'];

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

        // Storage::disk('public')->delete($game->logo);

        $game->delete();

        DB::commit();

        \Session::flash('alert-warning', 'ลบเกมส์เรียบร้อย');

        return \redirect()->back();

    }
}
