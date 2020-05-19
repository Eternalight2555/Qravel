@extends("layouts.app")

@section('content')

<div class="top_wrapper">
    <div class="contain">
        <div class="ranking_wrapper">
            
            <!-- 質問を表示 -->
            @foreach($questions as $question)
                <a href="#" class="question_link">
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
    <div class="contain">
        <div class="result_wrapper">
            
        </div>
    </div>
</div>



@endsection