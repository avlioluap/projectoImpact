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

Route::get ('/', 'WelcomeController@index');

Route::get ('home', ['as' => 'home', 'tag' => 'home', 'uses' => 'HomeController@index']);

Route::controllers ([
  'auth'     => 'Auth\AuthController',
  'password' => 'Auth\PasswordController',
]);

Route::group (['tag' => 'users'], function () {

  Route::controller ('admin', 'AdminController', [
    'getUsers' => 'users',
    'getUser'  => 'user',
  ]);

});

// rotes para a gestao de albums
Route::group(['prefix' => 'albums'], function () {
  //listagem de album do user
  Route::get ('list', ['as' => 'albums.list', 'tag' => 'albums', 'uses' => 'AlbumsController@albums']);
  //search de album no catologo do user
  Route::get('search', ['as' => 'albums.search', 'tag' => 'albums', 'uses' => 'AlbumsController@search']);
  //form de criação de uma nova entrada de albums
  Route::get('create', ['as' => 'albums.create', 'tag' => 'albums', 'uses' => 'AlbumsController@create']);
  //ajax request para a criação de um novo albums devolve json
  Route::get('insert', ['as' => 'albums.insert', 'tag' => 'albums', 'uses' => 'AlbumsController@store']);
  //view de um album para ver dados
  Route::get('show/{id}', ['as' => 'albums.show', 'tag' => 'albums', 'uses' => 'AlbumsController@show']);
});

//Route::get ('albums.list', ['as' => 'albums', 'tag' => 'albums', 'uses' => 'AlbumsController@albums']);

// URL-controlled localization support:

//Route::group (Language::getRouteGroup (), function () {
//});

//Route::get('locale', ['as' => 'locale', 'uses' => 'HomeController@locale']);
