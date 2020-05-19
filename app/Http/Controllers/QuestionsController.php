<?php

namespace App\Http\Controllers;

use App\Question;
use Auth;
use Validator;

use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index()
    {
        // 質問をすべて取得
        $questions = Question::get();
        
        return view('questions/index',['questions' => $questions]);
    }
}
