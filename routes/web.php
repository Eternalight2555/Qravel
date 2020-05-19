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
Route::group(['middleware'=>['auth']],function(){
    Route::get('newQuestion/','QuestionsController@new');
    Route::post('storeQuestion/','QuestionsController@store');
});


Route::get('/','QuestionsController@index');

// ユーザ詳細画面表示用
// Route::get('/','QuestionsController@show_userpage');

// ページ番号をクリックしたとき、ページ遷移
Route::get('/ranking_page/{page_id}','QuestionsController@paging');

Auth::routes();

Route::get('/question/show/{question_id}','QuestionsController@show');

Route::get('/', 'QuestionsController@index')->name('home');

