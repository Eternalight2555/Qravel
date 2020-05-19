@extends("layouts.app")

@section('content')

<div class="top_wrapper">
    <div class="content">
        <div class="ranking_wrapper">
            
            <!-- 質問を表示 -->
            @foreach($questions as $question)
                <a href="/question_show/{{ $question->id }}" class="question_link">
                    <div class="question">
                        <h3 class="question_title">{{ $question->title }}</h3>
                    </div>
                </a>
            @endforeach
            
            <!-- ページリンクの表示 -->
            @for($i = 1; $i <= $max_page; $i++)
                @if($i == $page_id)
                    {{ $page_id }}  
                @else
                    <a href="/ranking_page/{{ $i }}">{{ $i }}  </a>
                @endif
            @endfor
            
        </div>
    </div>
    <div class="content">
        <div class="result_wrapper">
            
        </div>
    </div>
</div>



@endsection