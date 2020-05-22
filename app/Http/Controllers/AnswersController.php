<?php

namespace App\Http\Controllers;

use App\Question;
use App\User;
use App\Answer;
use Auth;
use Validator;

use Illuminate\Http\Request;

class AnswersController extends Controller
{
    public function store(Request $request)
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
        $answer = new Answer;
        $answer->user_id = Auth::id();
        $answer->Q_id=$request->q_id;
        $answer->content = $request->content;
        $answer->good_count = 0;
        $answer->save();
        
        // 「/」 ルートにリダイレクト
        return redirect('/question/show/'.$request->q_id);
        
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
        $answer = Answer::find($request->answer_id);
        $answer->content = $request->content;
        $answer->save();
        
        // 「/」 ルートにリダイレクト
        return redirect('/question/show/'.$request->q_id);
        
    }
    
    public function replystore(Request $request)
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
        $answer = new Answer;
        $answer->user_id = Auth::id();
        $answer->Q_id=$request->q_id;
        $answer->parent_id=$request->parent_id;
        $answer->content = $request->content;
        $answer->good_count = 0;
        $answer->save();
        
        // 「/」 ルートにリダイレクト
        return redirect('/question/show/'.$request->q_id);
        
    }
}
