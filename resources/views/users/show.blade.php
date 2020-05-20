@extends("layouts.app")

@section('content')

<div class="top_wrapper">
    <div class="content">
        <div class="user_info_wrapper">
            
            <!-- ユーザ情報 -->
            <div class="user_name">
                <h3>ユーザ名</h3>
                <p>{{ $user->name }}</p>
            </div>
            <div class="user_address">
                <h3>メールアドレス</h3>
                <p>{{ $user->email }}</p>
            </div>
            <div class="user_profile">
                <h3>プロフィール</h3>
                @if($user->profile == null)
                    <p>{{ $user->profile }}</p>
                @else
                    <p>プロフィールなし</p>
                @endif
            </div>
            
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
                <a href="/question/show/{{ $question->id }}" class="question_link">
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
            @foreach($answers as $answer)
                <a href="/question/show/{{ $question->id }}" class="question_link">
                    <div class="question">
                        <h3 class="question_title">{{ $question->title }}</h3>
                    </div>
                </a>
            @endforeach
            
        </div>
    </div>
</div>



@endsection