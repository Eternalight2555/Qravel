@extends('layouts.app')
@section('content')

<script src="{{ asset('js/question_show_script.js') }}"></script>
<script>

function modal_onclick_close(){
document.getElementById("modal-content").style.display = "none";
document.getElementById("modal-overlay").style.display = "none";
}

function modal_onclick_open(){
document.getElementById("modal-content").style.display = "block";
document.getElementById("modal-overlay").style.display = "block";
}
</script>


<!-- モーダルウィンドウここから -->
<!-- 回答用　 -->
<div id="modal-content">
    <form action= "{{ url('/answer/new', $question->id) }}" method="POST" class="form-horizontal">
          {{csrf_field()}} 
    <div class="form-group"> 
      <label for="card">タイトル</label> 
      <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="カード名">
    </div>
    <div class="form-group"> 
      <label for="card">メモ</label> 
      <textarea name="memo" class="form-control" value="{{ old('memo') }}" placeholder="詳細">{{ old('memo') }}</textarea>
    </div>
    <div class="text-center"> 
        <button type="submit" class="createBtn"> 作成する </button> 
    </div>
  </form>
	<p>「閉じる」をクリックすると、モーダルウィンドウを終了します。</p>
	<p><a id="modal-close" onclick="modal_onclick_close()" >閉じる</a></p>
</div>
<!-- 2番目に表示されるモーダル（オーバーウエィ)半透明な膜 -->
<div id="modal-overlay" ></div>

<!-- モーダルウィンドウここまで -->

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
                
            }else if($show_user==$question->user_id){
                $url = url('/questionedit', $question->id);
                $html = '<a href="'.$url.'"class="Btn">編集する</a>';
            }else if($show_user==null){
                $html = '<a href="/">
                            ログインして回答する
                        </a> ';
            }else{
                $url = url('/answer/new', $question->id);
                $html = '<button type="button" onclick="modal_onclick_open();">
                            回答する
                        </button> ';
            }
            print $html;
        ?>
    </div>
    
</div>
@endsection