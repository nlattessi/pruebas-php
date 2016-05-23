<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/books', 'BooksController@index');
$app->get('/books/{id: [\d]+}', [
    'as' => 'books.show',
    'uses' => 'BooksController@show'
]);
$app->post('/books', 'BooksController@store');
$app->put('/books/{id: [\d]+}', 'BooksController@update');
$app->delete('/books/{id: [\d]+}', 'BooksController@destroy');

$app->group([
    'prefix' => '/authors',
    'namespace' => 'App\Http\Controllers'
], function(\Laravel\Lumen\Application $app) {
    $app->get('/', 'AuthorsController@index');
    $app->get('/{id: [\d]+}', 'AuthorsController@show');
    $app->post('/', 'AuthorsController@store');
    $app->put('/{id: [\d]+}', 'AuthorsController@update');
    $app->delete('/{id: [\d]+}', 'AuthorsController@destroy');
});