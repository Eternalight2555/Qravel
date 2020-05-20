@extends("layouts.app")

@section('content')

<div class="top_wrapper">
    <div class="content">
        <div class="search_wrapper">
            
            <h2>「{{ $word }}」で検索した結果</h2>
            <p>{{ $data_num }}件見つかりました</p>
            
            @if($data_num > 0)
                <!-- 質問を表示 -->
                @foreach($questions as $question)
                    <a href="/question/show/{{ $question->id }}" class="question_link">
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
                        <a href="/search/{{ $word }}/{{ $i }}">{{ $i }}  </a>
                    @endif
                @endfor
            @endif
            
        </div>
    </div>
</div>



@endsection