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
        <label for="carding" class="col-sm-offset-2 col-sm-5 memo">質問内容</label> 
        <label for="carding" name="byte" class="col-sm-3 content" id="inputlength">（残り65535バイト）</label> 
        <div class="col-sm-offset-2 col-sm-8">
            <textarea id="inputform" name="content" class="form-control" 
              onInput="document.getElementById('inputlength').innerHTML = '（残り' + (65535-(value==null?0:encodeURI(value).replace(/%../g, '*').length)) + 'バイト）';"
              >{{ old('content') }}</textarea>
        </div>
      </div>
      <div class="row">
        <label for="carding" class="col-sm-offset-2 col-sm-8 tag">タグ選択</label>
        <div class="col-sm-offset-2 col-sm-8 taglist">
          <select id="taglist" name="tags[]" size="5" class="" multiple>
           @foreach ($tags as $tag)
            <option value="{{$tag->id}}" >{{$tag->name}}</option>
           @endforeach
          </select>
        </div>
        <label for="carding" class="col-sm-offset-2 col-sm-8 tag">上にないタグは追加してどうぞ（カンマとかスペース区切りでタグ名を入力）</label>
        <div class="col-sm-offset-2 col-sm-8"> 
          <input type="text" name="ninitags" class="form-control" value="{{ old('ninitag') }}">
        </div>
      </div>
      <div class="col-sm-offset-5 col-sm-2 text-center button"> 
        <button type="submit" class="btn btn-success" onclick="submit();">
        <i class="glyphicon glyphicon-plus"></i> 追加する </button> 
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
    $('#taglist').multiSelect({
      keepOrder: true,
      cssClass:"",
      selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search'>",
      selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search'>",
      selectableFooter: "<div class='custom-header text-center'>タグ一覧</div>",
      selectionFooter: "<div class='custom-header text-center'>選択したタグ</div>",
      afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
    
        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });
    
        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
      },
      afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
      },
      afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
      }
    });
</script>
@endsection