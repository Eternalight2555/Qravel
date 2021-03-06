<?php

namespace App\Http\Controllers;

use App\Question;
use App\Tag;
use App\TagsQuestion;
use App\UsersQuestion;
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
        
        $tags=[];
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
        
        
        if(!empty($request->tags)){
            foreach($request->tags as $tagid){
                $q = new TagsQuestion;
                $q->tags_id = $tagid;
                $q->questions_id = $question->id;
                $q->save();
            }
        }
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
        $start_id = $question_count;
        $end_id = $start_id - MAX;
        
        // 配列の初期化
        $questions = [];
        $questionstags=[];
        $questionsuser=[];
        // そのページの質問を取得
        for($i = $start_id; $i >= $end_id && Question::find($i) != null; $i--){
            
            $q = Question::find($i);
            array_push($questions,$q);
            
            $tags = TagsQuestion::where('questions_id', $i)
            ->get();
            $tagnames=[];
            foreach($tags as $tag){
                $t = Tag::where("id",$tag->tags_id)->first();
                //eval(\Psy\sh());
                array_push($tagnames,$t->name);
            }
            $questionstags[$q->id]=$tagnames;
            $questionsuser[$q->id]=User::find($q->user_id)->name;
        }
        //eval(\Psy\sh());
        // トップviewにデータを送る
        return view('questions/index',['tagnames'=>$questionstags,'usernames'=>$questionsuser,'questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page]);
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
        $questionstags=[];
        
        $questionsuser=[];
        // そのページの質問を取得
        for($i = $start_id; $i <= $end_id && Question::find($i) != null; $i++){
            $q = Question::find($i);
            array_push($questions,$q);
            
            $tags = TagsQuestion::where('questions_id', $i)
            ->get();
            $tagnames=[];
            foreach($tags as $tag){
                $t = Tag::where("id",$tag->tags_id)->first();
                //eval(\Psy\sh());
                array_push($tagnames,$t->name);
            }
            $questionstags[$q->id]=$tagnames;
            $questionsuser[$q->id]=User::find($q->user_id)->name;
        }
        
        // トップviewにデータを送る
        return view('questions/index',['tagnames'=>$questionstags,'usernames'=>$questionsuser,'questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page]);
    }
    
    
    public function show($question_id)
    {
        $question = Question::find($question_id);
        $show_user= Auth::id();
        $question_user = User::find($question->user_id);
        
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
        
        $tags = TagsQuestion::where('questions_id', $question_id)
            ->get();
        $tagnames=[];
        foreach($tags as $tag){
            $t = Tag::where("id",$tag->tags_id)->first();
            array_push($tagnames,$t->name);
        }
        
        // ブックマークしているかを判断する
        if($show_user == NULL) $target = NULL;
        else $target = UsersQuestion::where('user_id',Auth::user()->id)->where('questions_id',$question_id)->first();
        
        return view('questions/show',['question_user'=>$question_user,'tagnames'=>$tagnames,'question' => $question,'show_user'=>$show_user,'answers'=>$answers,'reply_list'=>$reply_list,'answer_users'=>$answer_users,'target' => $target]);
    }
    
    public function bookmark($question_id)
    {
        // 既にブックマークされているかを判断する
        $target = UsersQuestion::where('questions_id',$question_id)->where('user_id',Auth::user()->id)->first();
        if ($target == null){
            $bookmark = new UsersQuestion;
            $bookmark->user_id = Auth::user()->id;
            $bookmark->questions_id = $question_id;
            $bookmark->save();
        }else{
            if($target->delete_trigger == 0){
                $target->delete_trigger = 1;
                $target->save();
            }else{
                $target->delete_trigger = 0;
                $target->save();
            }
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
    
    public function edit(Request $request)
    {
        $messages = ['content.required' => '回答を入力してください。',];
        //Validatorを使って入力された値のチェック(バリデーション)処理　（今回は256以上と空欄の場合エラーになります）
        $validator = Validator::make($request->all() , ['content'=>['required', 
            function($attribute, $value, $fail){
                if(strlen($value)>65535){
                    $fail('65535バイト以内で入力してください。(現在'.strlen($value).'バイト)');
                }
            }
        ]],$messages);
        
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        
        // 入力に問題がなければCardモデルを介して、タイトルとかをqテーブルに保存
        //eval(\Psy\sh());
        $question = Question::find($request->question_id);
        $question->title = $request->title;
        $question->content = $request->content;
        $question->save();
        
        // 「/」 ルートにリダイレクト
        return redirect('/question/show/'.$request->question_id);
        
    }
    

    public function show_userpage($user_id)
    {
        
        // Questionモデルを介してデータを取得
        $my_questions = Question::where('user_id',$user_id)->get();
        
        // Answerモデルを介してデータを取得
        $answers = Answer::where('user_id',$user_id)->get();
        
        $answered_questions = [];
        $questionstags=[];
        $answerstags=[];
        
        foreach($my_questions as $q){
            $tags = TagsQuestion::where('questions_id', $q->id)->get();
            $tagnames=[];
            foreach($tags as $tag){
                $t = Tag::where("id",$tag->tags_id)->first();
                array_push($tagnames,$t->name);
            }
            $questionstags[$q->id]=$tagnames;
        }
        foreach($answers as $answer){
            if($answer->parent_id == NULL){
                array_push($answered_questions,Question::find($answer->Q_id));
                $tags = TagsQuestion::where('questions_id', $answer->Q_id)->get();
                $tagnames=[];
                foreach($tags as $tag){
                    $t = Tag::where("id",$tag->tags_id)->first();
                    array_push($tagnames,$t->name);
                }
                $answerstags[$answer->Q_id]=$tagnames;
            }
        }
        
        // ブックマークした質問を取得
        $bookmarked_questions = [];
        $bookmarked_tags = [];
        if($user_id == Auth::id()){
            
            $bookmarks = UsersQuestion::where('user_id',Auth::id())->get();
            foreach($bookmarks as $bookmark){
                if($bookmark->delete_trigger == 0){
                    array_push($bookmarked_questions,Question::find($bookmark->questions_id));
                    $tags = TagsQuestion::where('questions_id', $bookmark->questions_id)->get();
                    $tagnames=[];
                    foreach($tags as $tag){
                        $t = Tag::where("id",$tag->tags_id)->first();
                        array_push($tagnames,$t->name);
                    }
                    $bookmarked_tags[$bookmark->questions_id]=$tagnames;
                    
                }
            }
        }
        
        // Userモデルを介してデータを取得
        $user = User::find($user_id);
        
        // データをユーザ詳細画面に送る
        return view('users/show',['Btags'=>$bookmarked_tags,'Atags'=>$answerstags,'tags'=>$questionstags,'user_id' => $user_id, 'my_questions' => $my_questions, 'answers' => $answers, 'user' => $user, 'answered_questions' => $answered_questions, 'bookmarked_questions' => $bookmarked_questions]);
        
    }

    // ここから検索機能
    public function search(Request $request)
    {
        // 検索した文字列を取得する
        $word = strtolower($request->input('key_word'));
        
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
        
        $questionstags = [];
        // そのページの質問を取得
        for($i = $start_id; $i < $end_id && $i < $data_num; $i++){
            array_push($questions,$searched[$i]);
            
            $id=$searched[$i]->id;
            $tags = TagsQuestion::where('questions_id', $id)->get();
            $tagnames=[];
            foreach($tags as $tag){
                $t = Tag::where("id",$tag->tags_id)->first();
                //eval(\Psy\sh());
                array_push($tagnames,$t->name);
            }
            $questionstags[$id]=$tagnames;
        }
        
        return view('questions/search',['tags'=>$questionstags,'word' => $word, 'questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page, 'data_num' => $data_num]);
        
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
        
        $questionstags=[];
        // そのページの質問を取得
        // eval(\Psy\sh());
        for($i = $start_id; $i < $end_id && $i < $data_num; $i++){
            array_push($questions,$searched[$i]);
            $id=$searched[$i]->id;
            $tags = TagsQuestion::where('questions_id', $id)->get();
            $tagnames=[];
            foreach($tags as $tag){
                $t = Tag::where("id",$tag->tags_id)->first();
                //eval(\Psy\sh());
                array_push($tagnames,$t->name);
            }
            $questionstags[$id]=$tagnames;
        }
        
        return view('questions/search',['tags'=>$questionstags,'word' => $word, 'questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page, 'data_num' => $data_num]);
        
    }
    
    public function search_tag(Request $request){
        $name=$request->input('name');
        $page_id=$request->input('page');
        
        //eval(\Psy\sh());
        
        $tags = Tag::where("name",$name)->get();
        
        $searched=[];
        foreach($tags as $tag){
            $rel = TagsQuestion::where('tags_id', $tag->id)->get();
            foreach($rel as $r){
                $q = Question::where("id",$r->questions_id)->first();
                //eval(\Psy\sh());
                array_push($searched,$q);
            }
        }
        
        $max_page = ceil(count($searched)/MAX);
        
        // 開始地点と終了地点の質問idを取得
        $end_id = $page_id * MAX;
        $start_id = $end_id - MAX;
        
        // 該当データの個数
        $data_num = count($searched);
        $questions = [];
        $questionstags=[];
        // そのページの質問を取得
        // eval(\Psy\sh());
        for($i = $start_id; $i < $end_id && $i < $data_num; $i++){
            array_push($questions,$searched[$i]);
            $id=$searched[$i]->id;
            $tags = TagsQuestion::where('questions_id', $id)->get();
            $tagnames=[];
            foreach($tags as $tag){
                $t = Tag::where("id",$tag->tags_id)->first();
                //eval(\Psy\sh());
                array_push($tagnames,$t->name);
            }
            $questionstags[$id]=$tagnames;
        }
        
        $word=$name;
        return view('questions/tagsearch',['tags'=>$questionstags,'word' => $word, 'questions' => $questions, 'page_id' => $page_id, 'max_page' => $max_page, 'data_num' => $data_num]);

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


