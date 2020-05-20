@extends('layouts.app')
@section('content')
<div class="panel-body">
<!-- バリデーションエラーの場合に表示 --> 
@include('common.errors')
  <!-- カード作成フォーム -->
  <form action="{{ url('question/store')}}" method="POST" class="form-horizontal" onsubmit="return false;">
  {{csrf_field()}} 
    <div class="form-group container-fluid"> 
      <div class="row">
        <label for="carding" class="col-sm-offset-2 col-sm-8 name">質問タイトル</label> 
        <div class="col-sm-offset-2 col-sm-8"> 
          <input type="text" name="name" class="form-control" value="{{ old('name') }}" autofocus>
        </div>
      </div>
      <div class="row">
        <select id="list1" name="tags[]" size="5" multiple>
         @foreach ($tags as $tag)
          <option value="{{$tag->id}}" >{{$tag->name}}</option>
         @endforeach
        </select>
        <label for="carding" class="col-sm-offset-2 col-sm-8 tag">タグ入力</label>
        
      </div>
      <div class="row">
        <label for="carding" class="col-sm-offset-2 col-sm-5 memo">質問内容</label> 
        <label for="carding" name="byte" class="col-sm-3 content" id="inputlength">（残り65535バイト）</label> 
        <div class="col-sm-offset-2 col-sm-8">
            <textarea id="inputform" name="content" class="form-control" 
              onInput="document.getElementById('inputlength').innerHTML = '（残り' + (65535-(value==null?0:encodeURI(value).replace(/%../g, '*').length)) + 'バイト）';"
              >{{ old('content') }}</textarea>
        </div>
      </div>
      <div class="col-sm-offset-5 col-sm-2 text-center button"> 
        <button type="submit" class="btn btn-success" onclick="submit();">
        <i class="glyphicon glyphicon-plus"></i> 追加する </button> 
      </div>
    </div>
  </form>
</div> 
@endsection