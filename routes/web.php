<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::prefix('sys')->group(function () {
        Route::resource('user','UserController');
        Route::get('user-search','UserController@search')->name('user.search');
        Route::resource('role','RoleController');
        Route::resource('permission','PermController');
    });
    Route::prefix('config')->group(function () {
        Route::resource('hospital','HospitalController');
        Route::post('hospital-change','HospitalController@change')->name('hospital.change');
        Route::resource('channel','ChannelController');
        Route::resource('consult','ConsultController');
        Route::resource('doctor','DoctorController');
        Route::resource('disease','DiseaseController');
        Route::resource('condition','ConditionController');
    });
    Route::resource('member','MemberController');
    Route::get('member-search','MemberController@search')->name('member.search');
    Route::post('member-condition','MemberController@condition')->name('member.condition');
    Route::resource('track','TrackController');
    Route::get('track-search','TrackController@search')->name('track.search');
    Route::prefix('help')->group(function () {
        Route::get('update-member', 'AidenController@updateMember')->name('help.memberupdate');
        Route::get('update-track', 'AidenController@updateTrack')->name('help.trackupdate');
        Route::get('update-gh', 'AidenController@updateGh')->name('help.ghupdate');
    });

    Route::post('get-tracks-from-member','MemberController@getTracks');
    Route::post('get-info-from-member','MemberController@getInfos');
    Route::post('get-tracks-number','TrackController@getTrackNumber');
    Route::any('ranking','UserController@ranking')->name('user.ranking');

    Route::resource('gh','GhController');
});
