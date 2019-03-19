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


Route::prefix('v1')->group(function(){
    Route::post('/user_login_api', 'api\LoginAPIController@user_login_api')->name('login_api');
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
});
