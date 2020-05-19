@extends("layouts.app")

@section('content')

<div class="top_wrapper">
    <div class="content">
        <div class="user_info_wrapper">
            
            <!-- ユーザ情報 -->
            
        </div>
    </div>
    <div class="content">
        <div class="user_book_wrapper">
            
            <!-- ブックマーク -->
            
        </div>
    </div>
    <div class="content">
        <div class="user_question_wrapper">
            
            <!-- 自分の質問 -->
            @foreach($questions as $question)
                <a href="/question_show/{{ $question->id }}" class="question_link">
                    <div class="question">
                        <h3 class="question_title">{{ $question->title }}</h3>
                    </div>
                </a>
            @endforeach
            
        </div>
    </div>
    <div class="content">
        <div class="user_answer_wrapper">
            
            <!-- 自分の解答 -->
            <!--@foreach($answers as $answer)-->
                
            <!--@endforeach-->
            
        </div>
    </div>
</div>



@endsection