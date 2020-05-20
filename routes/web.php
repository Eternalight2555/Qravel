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
//ログインしている場合のみ
Route::group(['middleware'=>['auth']],function(){
    //質問投稿画面を表示
    Route::get('/question/new','QuestionsController@new');
    Route::post('/question/store','QuestionsController@store');
});


Route::get('/','QuestionsController@index');

// ユーザ詳細画面表示用
// Route::get('/','QuestionsController@show_userpage');

// ページ番号をクリックしたとき、ページ遷移
Route::get('/ranking_page/{page_id}','QuestionsController@paging');

//ログイン画面を表示
Route::get('/login','QuestionsController@login');

//ユーザー登録画面を表示
Route::get('/register','QuestionsController@register');


//ユーザー詳細画面を表示
Route::get('/user/show','QuestionsController@user_show');

Auth::routes();

Route::get('/question/show/{question_id}','QuestionsController@show');

Route::get('/', 'QuestionsController@index')->name('home');

Route::get('/home', 'QuestionsController@index')->name('home');