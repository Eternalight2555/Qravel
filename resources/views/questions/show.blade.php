@extends('layouts.app')
@section('content')

<script src="{{ asset('/js/question_show_script.js') }}"></script>

<div class="cardshowPage">
    <div class="form-group">
        タイトル<br>
        <p class="title">{{ $question->title }}</p>
        <a onclick="return confirm('{{ $question->title }}をブックマークしますか？')" href="{{ url('/bookmark', $question->id)  }}">ブックマーク</a>
    </div>
    <div class="form-group">
        <p>質問内容<br>{{$question->content}}</p>
    </div>
    <div class="form-group">
        タグ名：
    </div>
    
    <div class="text-center" id='edit'>
        <?php
            if($question->clear_flag==true){
                
            }else if($create_user==$question->user_id){
                $url = url('/questionedit', $question->id);
                $html = '<a href="'.$url.'"class="Btn">編集する</a>';
            }else{
                $url = url('/answer/new', $question->id);
                $html = '<a href="'.$url.'"class="Btn">回答する</a>';
            }
            print $html;
        ?>
    </div>
    
</div>
@endsection