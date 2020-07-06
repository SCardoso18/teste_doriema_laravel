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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/geral_visitas', 'Admim\StatisticsController@GeneralVisits');

Route::get('/visitas_unicas', 'Admim\StatisticsController@OnlyVisitors');

Route::get('/usuarios_registrados', 'Admim\StatisticsController@UsersRegister');

Route::get('/geral_visitas_unicas', 'Admim\StatisticsController@GeneralOnlyVisitors');


