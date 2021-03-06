<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware'=>['api']],function(){
    Route::any('get-zxtrans-swts-info','ApiController@zxTrans');
    Route::any('get-yy-swts-info','ApiController@getYySwts');
    Route::any('get-meida-swts-info','ApiController@getMediaSwts');
    Route::any('get-area-swts-info','ApiController@getAreaSwts');

});
