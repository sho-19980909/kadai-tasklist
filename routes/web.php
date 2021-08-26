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

Route::get('/', 'TasksController@index');
// Controller(TasksController@indexを経由して、'welcome'を表示する。)
// Route::get('/', 'TasksController@index');

// なぜいるかいらないか（理由をはっきりさせる）
// php artisan route:list　ルートの一覧がわかる
Route::resource('tasks', 'TasksController', ['only' => ['index', 'show', 'create', 'edit', 'destroy', 'store', 'update']]);

// ユーザ登録を追加
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');


// 認証     (認証はLoginControllerが担当します。)
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');


// 認証を必要とするグループ
Route::group(['middleware' => ['auth']], function() {
    
    // ['only' => ['create', 'edit', 'destroy', 'store'] : 認証済みのユーザだけがこれらのアクションにアクセスできる。
    Route::resource('tasks', 'TasksController', ['only' => ['create', 'edit', 'destroy', 'store', 'update']] );
});