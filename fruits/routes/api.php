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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    //$api->get('/', function() {
    //    return ['Fruits' => 'Delicious and healthy!'];
    //});
    
    $api->get('fruits', 'App\Http\Controllers\FruitsController@index');

    $api->get('fruits/{id}', 'App\Http\Controllers\FruitsController@show');

    $api->post('authenticate', 'App\Http\Controllers\AuthenticateController@authenticate');
});

$api->version('v1', ['middleware' => 'api.auth'], function ($api) {

    $api->get('authenticated_user', 'App\Http\Controllers\AuthenticateController@authenticatedUser');

    $api->get('token', 'App\Http\Controllers\AuthenticateController@getToken');

    $api->post('logout', 'App\Http\Controllers\AuthenticateController@logout');
    
    $api->post('fruits', 'App\Http\Controllers\FruitsController@store');

    $api->delete('fruits/{id}', 'App\Http\Controllers\FruitsController@destroy');

});