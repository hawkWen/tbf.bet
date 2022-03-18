<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Helpers\LineApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RichMenuController extends Controller
{
    //
    public function index() {

        $brands = Brand::whereIn('type_api',['1','2'])->get();

        return view('admin.rich-menu.index', \compact('brands'));

    }

    public function show($brand_id) {

        $brand = Brand::find($brand_id);

        $line_api = new LineApi();

        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $rich_menus = json_decode($line_api->getRichMenu(),true);
            
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.line.me/v2/bot/user/all/richmenu",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $brand->line_token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $default_rich_menu = \json_decode($response,true);

        // dd($brand,$line_api,$rich_menus);

        return view('admin.rich-menu.show', compact('brand','rich_menus','default_rich_menu'));

    }

    public function create(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id']);

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.line.me/v2/bot/richmenu",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $input['rich_menu_data'],
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $brand->line_token"
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        return redirect()->back();

    }

    public function default($brand_id, $rich_menu_id) {

        $brand = Brand::find($brand_id);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.line.me/v2/bot/user/all/richmenu/".$rich_menu_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $brand->line_token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        dd($response);

        return redirect()->back();

    }

    public function upload(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id']);

        $storage  = Storage::disk('public')->put('rich-menua', $request->file('rich_menu_image'));

        $imagePath = 'http://admin.casino-auto.localhost/storage/rich-menua/4Jhu4kLVQgSyPgjDOsPxxKMuB0g2EcJzPAI8AuYX.jpeg';

        $contentType = 'image/jpeg';

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($brand->line_token);

        dd($httpClient);
        
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $brand->line_channel_secret]);

        $response = $bot->uploadRichMenuImage($input['rich_menu_id'], $imagePath, $contentType);

        dd($response);

    }

    public function delete($brand_id, $rich_menu_id) {

        $brand = Brand::find($brand_id);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.line.me/v2/bot/richmenu/".$rich_menu_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $brand->line_token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return redirect()->back();

    }
    
}
