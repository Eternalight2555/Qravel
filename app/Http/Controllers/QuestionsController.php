<?php

namespace App\Http\Controllers;

use App\Question;
use App\User;
use App\Answer;
use Auth;
use Validator;

use Illuminate\Http\Request;

// 定義
define('MAX','5');

class QuestionsController extends Controller
{
    
    public function index(){

        // ページの初期値
        $page_id = 1;
        // ページ数を取得
        $questions = Question::get();
        $max_page = ceil(count($questions)/MAX);
        
        // 開始地点と終了地点の質問idを取得
        $end_id = $page_id * MAX;
        $start_id = $end_id - MAX + 1;
        
        // 配列の初期化
        $questions = [];
        
        // そのページの質問を取得
        for($i = $start_id; $i <= $end_id; $i++){
            array_push($questions,Question::find($i));
        }
        
        // トップviewにデータを送る
        return view('questions/index',['questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page]);
    }
    
    public function paging($page_id){
        
        // ページ数を取得
        $questions = Question::get();
        $max_page = ceil(count($questions)/MAX);
        
        // 開始地点と終了地点の質問idを取得
        $end_id = $page_id * MAX;
        $start_id = $end_id - MAX + 1;
        
        // 配列の初期化
        $questions = [];
        
        // そのページの質問を取得
        for($i = $start_id; $i <= $end_id && Question::find($i) != null; $i++){
            array_push($questions,Question::find($i));
        }
        
        // トップviewにデータを送る
        return view('questions/index',['questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page]);
    
    }
    
    public function show_userpage(){
        
        // ユーザ番号を取得
        $user_id = Auth::user()->id;
        
        // Questionモデルを介してデータを取得
        $questions = Question::where('user_id',$user_id)->get();
        
        // Answerモデルを介してデータを取得
        $answers = Answer::where('user_id',$user_id)->get();
        
        // Userモデルを介してデータを取得
        $user = User::find($user_id);
        
        // データをユーザ詳細画面に送る
        return view('users/show',['user_id' => $user_id, 'questions' => $questions, 'answers' => $answers, 'user' => $user]);
        
    }
}
