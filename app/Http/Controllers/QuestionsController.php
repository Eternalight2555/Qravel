<?php

namespace App\Http\Controllers;

use App\Question;
use App\Tag;
use App\TagsQuestion;
use App\User;
use App\Answer;
use Auth;
use Validator;

use Illuminate\Http\Request;

// 定義
define('MAX','10');

class QuestionsController extends Controller
{
    public function new()
    {
        $tags = Tag::get();
        //eval(\Psy\sh());
        //array_unshift()
        return view('questions/new',['tags'=>$tags]);
        // テンプレート「listing/new.blade.php」を表示します。
    }

    public function store(Request $request)
    {
        $messages = [
                'name.required' => 'タイトルを入力してください。',
                'name.max'=>'タイトルは255文字以内で入力してください。',
                'content.required'=>'内容を入力してください。'
        ];
        //Validatorを使って入力された値のチェック(バリデーション)処理　（今回は256以上と空欄の場合エラーになります）
        $validator = Validator::make($request->all() , ['name' => 'required|max:256','content'=>['required', 
            function($attribute, $value, $fail){
                if(strlen($value)>65535){
                    $fail('メモは65535バイト以内で入力してください。(現在'.strlen($value).'バイト)');
                }
            }
        ]],$messages);
        
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $tags = $request->tags;
        
        if(!empty($request->ninitags)){
            //
            $tagnames = preg_split("/[\s,]+/", $request->ninitags);
            foreach($tagnames as $tagname){
                $t=new Tag;
                $t->name = $tagname;
                $t->save();
                //eval(\Psy\sh());
                array_push($tags,$t->id);
            }
        }
        //eval(\Psy\sh());
        // 入力に問題がなければCardモデルを介して、タイトルとかをqテーブルに保存
        //eval(\Psy\sh());
        $question = new Question;
        $question->title = $request->name;
        $question->user_id = Auth::id();
        $question->crear_flag = false;
        $question->content = $request->content;
        $question->want_know_count=0;
        $question->save();
        
        
        if(!empty($tags)){
            foreach($tags as $tagid){
                $q = new TagsQuestion;
                $q->tags_id = $tagid;
                $q->questions_id = $question->id;
                $q->save();
            }
        }
        
        // 「/」 ルートにリダイレクト
        return redirect('/');
    }
    
    public function index()
    {

        // ページの初期値
        $page_id = 1;
        // ページ数を取得
        $questions = Question::get();
        
        $question_count = count($questions);
        $max_page = ceil($question_count/MAX);
        
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
    
    public function paging($page_id)
    {
        
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
    
    
    public function show($question_id)
    {
        // 質問をすべて取得
        $question = Question::find($question_id);
        $show_user= Auth::id();
        
        $answers = Answer::where('Q_id',$question_id)
                    ->where('parent_id',null)
                    ->orderBy('created_at', 'asc')
                    ->get();
        
        $reply_list=[];
        $answer_users=[];
        
        foreach($answers as $answer){
            $temp=Answer::where('parent_id',$answer->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
            array_push($reply_list,$temp);
            $ans_user=User::find($answer->user_id);
            array_push($answer_users,$ans_user->name);
        }
        //$tagids = TagsQuestion::where('questions_id',$question_id)->get();
        $tags = TagsQuestion::where('questions_id', $question_id)
            ->get();
        $tagnames=[];
        foreach($tags as $tag){
            $t = Tag::where("id",$tag->tags_id)->first();
            //eval(\Psy\sh());
            array_push($tagnames,$t->name);
        }
        //eval(\Psy\sh());
        
        return view('questions/show',['tagnames'=>$tagnames,'question' => $question,'show_user'=>$show_user,'answers'=>$answers,'reply_list'=>$reply_list,'answer_users'=>$answer_users]);
    }
    
    public function bookmark($question_id)
    {
        // 既にブックマークされているかを判断する
        $target = User_Question::where('user_id',Auth::user()->id)->where('questions_id',$question_id)->first();
        if ($target !== undefined){
            $target->delete();
        }else{
            $bookmark = new User_Question;
            $bookmark->user_id = Auth::user()->id;
            $bookmark->questions_id = $question_id;
        }
        
        return redirect('/question/show/'.$question_id);
    }
    
    public function crear($question_id)
    {
        $question = Question::find($question_id);
        $question->crear_flag=true;
        $question->save();
        
        return redirect('/question/show/'.$question_id);
    }
    

    public function show_userpage()
    {

        // ユーザ番号を取得
        $user_id = Auth::user()->id;
        
        // Questionモデルを介してデータを取得
        $questions = Question::where('user_id',$user_id)->get();
        
        // Answerモデルを介してデータを取得
        $answers = Answer::where('user_id',$user_id)->get();
        
        $answered_questions = [];
        
        foreach($answers as $answer){
            if($answer->parent_id == NULL) array_push($answered_questions,Question::find($answer->Q_id));
        }
        
        // Userモデルを介してデータを取得
        $user = User::find($user_id);
        
        // データをユーザ詳細画面に送る
        return view('users/show',['user_id' => $user_id, 'questions' => $questions, 'answers' => $answers, 'user' => $user, 'answered_questions' => $answered_questions]);
        
    }

    // ここから検索機能
    public function search(Request $request)
    {
        
        // 検索した文字列を取得する
        $word = strtolower($request->key_word);
        
        // 全角スペースを半角スペースに変換
        $check = mb_convert_kana($word, 's');
        // スペースを含むときエラーとする
        if(strpos($check," ") !== false){
            return redirect()->back();
            
        }
        
        // 質問全てを取得する
        $questions = Question::get();
        
        // 条件にあうものを格納する配列
        $searched = [];
        
        if($word != ''){
            foreach($questions as $question){
                
                // タイトルか内容にワードが含まれていたら格納
                if(strpos(strtolower($question->title),$word) === false){
                    if(strpos(strtolower($question->content),$word) !== false){
                        array_push($searched,$question);
                    }
                }else{
                    array_push($searched,$question);
                }
                
            }
        }
        
        
        // ページの初期値
        $page_id = 1;
        // ページ数を取得
        $max_page = ceil(count($searched)/MAX);
        
        // 開始地点と終了地点の質問idを取得
        $end_id = $page_id * MAX;
        $start_id = $end_id - MAX;
        
        // 配列の初期化
        $questions = [];
        
        // 該当データの個数
        $data_num = count($searched);
        
        // そのページの質問を取得
        for($i = $start_id; $i < $end_id && $i < $data_num; $i++){
            array_push($questions,$searched[$i]);
        }
        
        return view('questions/search',['word' => $word, 'questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page, 'data_num' => $data_num]);
        
    }
    // ここまで検索機能
    
    // 検索画面のページング
    public function search_paging($word,$page_id)
    {
        
        // 質問全てを取得する
        $questions = Question::get();
        
        // 条件にあうものを格納する配列
        $searched = [];
        
        if($word != ''){
            foreach($questions as $question){
                
                 // タイトルか内容にワードが含まれていたら格納
                if(strpos($question->title,$word) === false){
                    if(strpos($question->content,$word) !== false){
                        array_push($searched,$question);
                    }
                }else{
                    array_push($searched,$question);
                }
                
            }
        }
        
        // ページ数を取得
        $max_page = ceil(count($searched)/MAX);
        
        // 開始地点と終了地点の質問idを取得
        $end_id = $page_id * MAX;
        $start_id = $end_id - MAX;
        
        // 配列の初期化
        $questions = [];
        
        // 該当データの個数
        $data_num = count($searched);
        
        // そのページの質問を取得
        for($i = $start_id; $i < $end_id && $i < $data_num; $i++){
            array_push($questions,$searched[$i]);
        }
        
        return view('questions/search',['word' => $word, 'questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page, 'data_num' => $data_num]);
        
    }
    
    //ログイン画面に遷移
    public function login()
    {
        return view('login');
    }
    //登録画面に遷移
    public function register()
    {
        return view('register');
    }
    //質問投稿画面に遷移

    public function question_new()
    {
        return view('questions/new');
    }
}
