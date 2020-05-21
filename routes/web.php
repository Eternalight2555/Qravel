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
    Route::get('newQuestion','QuestionsController@new');
    Route::post('/question/store','QuestionsController@store');
});


// ユーザ詳細画面表示用
Route::get('/users/show','QuestionsController@show_userpage');

// ページ番号をクリックしたとき、ページ遷移
Route::get('/ranking_page/{page_id}','QuestionsController@paging');


//質問投稿画面を表示
Route::get('/question/new','QuestionsController@question_new');

// 検索ボタンを押したとき検索結果をトップページに表示
Route::post('/search','QuestionsController@search');
// 検索結果ページからのページング
Route::get('/search/{key_word}/{page_id}','QuestionsController@search_paging');

Auth::routes();

Route::get('/question/show/{question_id}','QuestionsController@show');
Route::get('/question/crear/{question_id}','QuestionsController@crear');

Route::get('/', 'QuestionsController@index')->name('home');

Route::post('/answer/new/{question_id}', 'AnswersController@store');
Route::post('/reply/new/{answer_id}', 'AnswersController@replystore');

Route::get('/home', 'QuestionsController@index')->name('home');

