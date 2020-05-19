@extends('layouts.app')
@section('content')
<div class="panel-body">
<!-- バリデーションエラーの場合に表示 --> 
@include('common.errors')
  <!-- カード作成フォーム -->
  <form action="{{ url('carding')}}" method="POST" class="form-horizontal">
  {{csrf_field()}} 
    <div class="form-group container-fluid"> 
      <div class="row">
        <label for="carding" class="col-sm-offset-3 col-sm-3 name">カード名</label> 
        <div class="col-sm-offset-3 col-sm-6"> 
          <input type="text" name="name" class="form-control" value="{{ old('name') }}" autofocus>
        </div>
      </div>
      <label for="carding" class="col-sm-offset-3 col-sm-3 memo">メモ</label> 
        <label for="carding" name="byte" class="col-sm-3 memo" id="inputlength">（残り65535バイト）</label> 
        <div class="row">
          <!-- memo --> 
          <div class="col-sm-offset-3 col-sm-6">
            <textarea id="inputform" name="memo" class="form-control" 
            onInput="document.getElementById('inputlength').innerHTML = '（残り' + (65535-(value==null?0:encodeURI(value).replace(/%../g, '*').length)) + 'バイト）';"
            >{{ old('memo') }}</textarea>
          </div>
        </div>
      <input type="hidden" name="id" value="{{ $list_id }}"> 

      <div class="col-sm-offset-5 col-sm-2 text-center button"> 
        <button type="submit" class="btn btn-success">
        <i class="glyphicon glyphicon-plus"></i> 追加する </button> 
      </div>
    </div>
  </form>
</div> 
@endsection