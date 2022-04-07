<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'Api'], function() {

    Route::get('/book','BookController@index');
    Route::post('/book','BookController@store');
    Route::get('/book/{id}','BookController@show');
    Route::put('/book/{id}','BookController@update');
    Route::delete('/book/{id}','BookController@destroy');

    Route::get('/get/bank','HomeController@getBank');
    Route::post('/check/brand','HomeController@checkBrand');
    Route::post('/check/phone','HomeController@checkPhone');
    Route::post('/check/bank','HomeController@checkBank');
    Route::post('/otp/check', 'HomeController@checkOtp');

    //Service
    Route::post('/service/customer/{token}','ServiceController@customer');
    Route::post('/service/topup/{token}','ServiceController@topUp');
    Route::post('/service/promotions/{token}','ServiceController@promotions');

    Route::group([
        
        'middleware' => ['jwt','whitelist']

    ], function() {

        //check brand
        Route::post('/check','HomeController@check');

        // get url
        Route::post('/url','HomeController@url');
    
        //deposit
        Route::post('/deposit','HomeController@deposit');
        
        //Promotion
        Route::post('/get/promotion','HomeController@promotion');
    
        //withdraw
        Route::post('/withdraw','HomeController@withdraw');
    
        //history
        Route::post('/history','HomeController@history');
    
        //Connect Line
        Route::post('/connect/line','HomeController@connectLine');
    
        //update Promotion
        Route::post('/promotion/update','HomeController@promotionUpdate');

        //Promotion Last
        Route::post('/promotion/last','HomeController@promotionLast');

        //Promotion Select
        Route::post('/promotion/select','HomeController@promotionSelect');
    
        //Credit
        Route::post('/credit','HomeController@credit');
    
        //Profile
        Route::post('/profile','HomeController@profile');

        //Invite
        Route::post('/invite','HomeController@invite');

        //Invite store
        Route::post('/invite/store','HomeController@inviteStore');

        Route::post('/promotion/credit-free','HomeController@creditFree');

        //update
        Route::post('/wheel','HomeController@wheel');
        Route::post('/wheel/store','HomeController@wheelStore');
    
        //AmbKing
        Route::get('/game-list/{customer_id}/{type_game}','HomeController@gameList');
        Route::post('/start-game','HomeController@startGame');

    });
    
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'Api',

], function ($router) {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('/service/login', 'AuthController@serviceLogin');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});