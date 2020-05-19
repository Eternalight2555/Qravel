<?php

namespace App\Http\Controllers;

use App\Question;
use Auth;
use Validator;

use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function new()
    {
        return view('questions/new');
        // テンプレート「listing/new.blade.php」を表示します。
    }
    // ===ここまでカードを新規作成する処理の追加（フォームへの遷移）===


    // ===ここからカードを新規作成する処理の追加（データベースへの保存）===
    public function store(Request $request)
    {
        $messages = [
                'name.required' => 'カード名を入力してください。',
                'name.max'=>'カード名は255文字以内で入力してください。',
                'memo.required'=>'メモを入力してください。',
        ];
        //Validatorを使って入力された値のチェック(バリデーション)処理　（今回は256以上と空欄の場合エラーになります）
        $validator = Validator::make($request->all() , ['name' => 'required|max:256','content'=>['required', 
            function($attribute, $value, $fail){
                if(strlen($value)>65535){
                    $fail('メモは65535バイト以内で入力してください。(現在'.strlen($value).'バイト)');
                }
            }
        ]],$messages);

        //バリデーションの結果がエラーの場合
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
            // 上記では、入力画面に戻りエラーメッセージと、入力した内容をフォーム表示させる処理を記述しています
        }
        
        // 対象listがそのユーザーのものであるかチェック
        if(!$this->validateList($request->id)){
            return redirect()->back()->withErrors("それはあなたのリストではありません。")->withInput();
        }

        // 入力に問題がなければCardモデルを介して、タイトルとかをcardテーブルに保存
        //eval(\Psy\sh());
        $card = new Card;
        $card->title = $request->name;
        $card->memo = $request->memo;
        $card->listing_id = $request->id;
        $card->save();
        
        // 「/」 ルートにリダイレクト
        return redirect('/');
    }
    public function index()
    {
        // 質問をすべて取得
        $questions = Question::get();
        
        return view('questions/index',['questions' => $questions]);
    }
}
