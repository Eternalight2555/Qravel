@extends("layouts.app")

@section('content')

<div class="top_wrapper">
    <div class="content">
        <div class="ranking_wrapper container">
            <!-- 質問を表示 -->
              @foreach($questions as $question)
                <div class="row">
                    <div class="question col-sm-offset-2 col-sm-8">
                        <a class="stretched-link" href="/question/show/{{ optional($question)->id }}" class="question_link">
                            <h3 class="question_title">{{ optional($question)->title }}</h3>
                        </a>
                        
                        <div class="question-status">
                            <?php if($question->crear_flag){ ?>
                            <p id="end">回答済</p>
                            <?php }else{ ?>
                            <p id="noend">受付中</p>
                            <?php } ?>
                        </div>
                        <div class="question-tag">
                            @foreach ($tagnames[$question->id] as $tagname) 
                                <span class="badge badge-pill badge-primary">{{ $tagname }}</span>
                            @endforeach
                        </div>
                        <div class="questioner"><a href="/users/show/{{ $question->user_id }}">{{$usernames[$question->id]}}</a></div>
                    </div>
                </div>
              @endforeach
        </div>  
        <div class="row text-center">
            <!-- ページリンクの表示 -->
          @for($i = 1; $i <= $max_page; $i++)
                @if($i == $page_id)
                    <span class="btn btn-primary" role="button">{{ $page_id }}  </span>
                @else
                    <a class="btn" href="/ranking_page/{{ $i }}" role="button">{{ $i }}  </a>
                @endif
          @endfor
        </div>
    </div>
</div>



@endsection