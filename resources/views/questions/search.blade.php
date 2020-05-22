@extends("layouts.app")

@section('content')

<div class="top_wrappe container">
    <div class="content">
        <div class="row">
            <div class="search_wrapper col-sm-offset-2 col-sm-8">
                
                <h2>「{{ $word }}」で検索した結果</h2>
                <p>{{ $data_num }}件見つかりました</p>
                
                @if($data_num > 0)
                    <!-- 質問を表示 -->
                    <div class="questions">
                    @foreach($questions as $question)
                    <div class="serch_question">
                        <div class="question">
                            <a href="/question/show/{{ $question->id }}" class="question_link">
                                    <h3 class="question_title">{{ $question->title }}</h3>
                            </a>
                        </div>
                        <div class="question-status">
                            <?php if($question->crear_flag){ ?>
                            <p id="end">回答済</p>
                            <?php }else{ ?>
                            <p id="noend">受付中</p>
                            <?php } ?>
                        </div>
                        <div class="question-tag">
                            <p>
                            @foreach ($tags[$question->id] as $tagname) 
                                <span class="badge badge-secondary tag">{{ $tagname }}</span>
                            @endforeach
                            </p>
                        </div>
                        <div class="questioner">ユーザー</div>
                    </div>
                    @endforeach
                    </div>
                    <div class=" page_btn row text-center">
                    <!-- ページリンクの表示 -->
                        @for($i = 1; $i <= $max_page; $i++)
                            @if($i == $page_id)
                                <span class="btn btn-primary" role="button">{{ $page_id }}</span>
                            @else
                                <a class="btn" href="/search/{{ $word }}/{{ $i }}" role="button">{{ $i }}  </a>
                            @endif
                        @endfor
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>



@endsection