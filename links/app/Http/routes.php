<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $links = \App\Link::all();
    return view('welcome', compact('links'));
});

Route::get('/submit', function () {
    return view('submit');
});

Route::post('/submit', function(\Illuminate\Http\Request $request) {
    $validator = Validator::make($request->all(), [
        'title' => 'required|max:255',
        'url' => 'required|max:255',
        'description' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return back()
            ->withInput()
            ->withErrors($validator);
    }

    $link = new \App\Link;
    $link->title = $request->title;
    $link->url = $request->url;
    $link->description = $request->description;
    $link->save();

    return redirect('/');
});

Route::auth();

Route::get('/home', 'HomeController@index');
