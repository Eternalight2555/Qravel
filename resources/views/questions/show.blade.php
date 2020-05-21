@extends('layouts.app')
@section('content')

<script src="{{ asset('js/question_show_script.js') }}"></script>
<script>

function anser_onclick_close(){
document.getElementById("anser-content").style.display = "none";
document.getElementById("modal-overlay").style.display = "none";
}

function anser_onclick_open(){
document.getElementById("anser-content").style.display = "block";
document.getElementById("modal-overlay").style.display = "block";
}
</script>


<!-- モーダルウィンドウここから -->
<!-- 回答用　 -->
<div id="anser-content">
    <form action= "{{ url('/answer/new', $question->id) }}" method="POST" class="form-horizontal">
          {{csrf_field()}} 
    <div class="form-group"> 
      <label>回答内容</label> 
      <textarea name="content" class="form-control" value="{{ old('content') }}" placeholder="詳細">{{ old('memo') }}</textarea>
    </div>
    <div class="text-center"> 
        <button type="submit" class="createBtn"> 作成する </button> 
    </div>
    <input type="hidden" name="q_id" value="{{ old('q_id', $question->id) }}">
  </form>
	<p>「閉じる」をクリックすると、モーダルウィンドウを終了します。</p>
	<p><a id="modal-close" onclick="anser_onclick_close()" >閉じる</a></p>
</div>

<!-- 2番目に表示されるモーダル（オーバーウエィ)半透明な膜 -->
<div id="modal-overlay" ></div>

<!-- モーダルウィンドウここまで -->

<div class="questionShowPage">
    <div class="questionContent">
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
            @foreach ($tags as $tag) 
                <p>{{ $tag->name }}</p>
            @endforeach
        </div>
        
        <div class="text-center">
            <?php
                if($question->clear_flag==true){ ?>
                    
            <?php }else if($show_user==$question->user_id){ ?>
                    <a href="'.$url.'"class="Btn">編集する</a>
            <?php }else if($show_user==null){?>
                    <a href={{ route('login') }}>
                        ログインして回答する
                    </a> 
            <?php }else{ ?>
                <button type="button" onclick="anser_onclick_open();">
                    回答する
                </button> 
            <?php }    ?>
        </div>
    </div>
    
    <div class="answerContent">
        @foreach ($answers as $answer) 
            <div class="form-group">
                <p>回答内容<br>{{$answer->content}}</p>
            </div>
            <div class="form-group">
                <p>役に立ったと思った人<br>{{$answer->good_count}}</p>
                <a onclick="return confirm('この回答を評価しますか？')" href="{{ url('/good', $answer->id)  }}">役に立った</a>
            </div>
            
            </div>
            <details>
                <summary>この回答に対する返信</summary>
                @foreach ($answers as $answer)
                @endforeach
                    <?php if($question->clear_flag==true): ?>
                        <p>test</p>
                    <?php endif; ?>
                    <?php if($show_user==$answer->user_id): ?>
                        <a href="{{ url('/answeredit', $question->id) }}"class="Btn">回答を編集する</a>
                    <?php endif; ?>
                    <?php if($show_user==null): ?>
                        <a href={{ route('login') }}>
                            ログインして返信する
                        </a> 
                    <?php endif;?>
                    
                    <div id="reply-content">
                        <form action= "{{ url('/reply/new', $answer->id) }}" method="POST" class="form-horizontal">
                            {{csrf_field()}} 
                            <div class="form-group"> 
                                <label>回答内容</label> 
                                <textarea name="content" class="form-control" value="{{ old('content') }}" placeholder="詳細">{{ old('content') }}</textarea>
                            </div>
                            <div class="text-center"> 
                                <button type="submit" class="createBtn"> 返信する </button> 
                            </div>
                            <input type="hidden" name="q_id" value="{{ old('q_id', $question->id) }}">
                            <input type="hidden" name="parent_id" value="{{ old('parent_id', $answer->id) }}">
                        </form>
                    </div>
                    
            </details>
        @endforeach
    </div>
    
</div>
@endsection