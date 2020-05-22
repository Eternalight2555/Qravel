@extends('layouts.app')
@section('content')


<div id="modal-overlay" ></div>


<!-- 回答用モーダル -->
<div class="modal fade" id="modal1" tabindex="-1"
      role="dialog" aria-labelledby="label1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="label1">回答内容</h5>
      </div>
      <div class="modal-body">
        <form action= "{{ url('/answer/new', $question->id) }}" method="POST" class="form-horizontal">
          {{csrf_field()}} 
        <div class="form-group"> 
          <textarea name="content" class="form-control" value="{{ old('content') }}" placeholder="詳細">{{ old('content') }}</textarea>
        </div>
        <input type="hidden" name="q_id" value="{{ old('q_id', $question->id) }}">
        <div class="text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">作成する</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- モーダルウィンドウここまで -->
@include('common.errors')
<div class="questionShowPage">
    <div class="content col-sm-offset-2 col-sm-8">
        <div class="question_title">
            <h2 class="title">{{ $question->title }}</h2>
                <div class="bookmark">
                    <p>質問者：<a href="/users/show/{{ $question_user->id }}">{{ $question_user->name }}</a></p>
                    @if($show_user==null)
                        <a href={{ route('login') }}>
                            ログインしてブックマークする
                        </a> 
                    @elseif($target == null || $target->delete_trigger == 1)
                        <a onclick="return confirm('{{ $question->title }}をブックマークしますか？')" href="{{ url('/bookmark', $question->id)  }}">ブックマーク</a>
                    @else
                        <a onclick="return confirm('{{ $question->title }}をブックマークから外しますか？')" href="{{ url('/bookmark', $question->id)  }}">ブックマークを外す</a>
                    @endif
                </div>
        </div>
        <div class="question_main">
            <h4>質問内容</h4>
            <p>{{$question->content}}</p>
        </div>
        <div class="form-group">
            タグ名：
            
            @foreach ($tagnames as $tagname) 
                <a href="/tagsearch?{{ http_build_query(['name'=>$tagname]) }}&page=1" class="tag badge badge-pill badge-primary">{{ $tagname }}</a>
            @endforeach
            
        </div>
        
        <div class="text-center">
            <?php
                if($question->crear_flag==true){ ?>
                    <h4><span>この質問は解決しました</span></h4>
            <?php }else if($show_user==$question->user_id){ ?>
                    <!-- <a href="/" class="Btn">編集する</a> -->
                <a class="btn btn-primary" href="{{ url('/question/crear', $question->id) }}" role="button">回答済にする</a>
                <div class="question_edit_form">
                    <details>
                        <summary>編集する</summary>
                            <form action= "{{ url('/question/edit', $question->id) }}" method="POST" class="form-horizontal">
                                {{csrf_field()}} 
                                <div class="form-group"> 
                                    <label>タイトル</label> 
                                    <input name="title" class="form-control" value="{{ $question->title }}" placeholder="詳細"></textarea>
                                </div>
                                <div class="form-group"> 
                                    <label>質問内容</label> 
                                    <textarea name="content" class="form-control" value="{{ $question->content }}" placeholder="詳細">{{ $question->content }}</textarea>
                                </div>
                                <div class="text-center"> 
                                    <button type="submit" class="btn btn-primary"　class="createBtn"> 更新する </button> 
                                </div>
                            </form>
                    </details>
                </div>
            <?php }else if($show_user==null){?>
                    <a href={{ route('login') }}>
                        ログインして回答する
                    </a> 
            <?php }else{ ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal1">
                    回答する
                </button> 
            <?php }    ?>
        </div>
    </div>
    
    <div class="content">
        <?php 
            $i=0;
        ?>
        <div class="answer_form_top content col-sm-offset-2 col-sm-8">
        <?php if(count($answers)<=0): ?>
            <p>この質問に対する回答はまだありません</p>
        <?php else: ?>
            <h3 class="answer_count">回答 <span>{{count($answers)}}</span> 件</h3>
        <?php endif; ?>
        </div>
        
        @foreach ($answers as $answer) 
        <div class="answer_form_content col-sm-offset-2 col-sm-8">
            <div>
                <h4><a href="/users/show/{{ $answer->user_id }}">{{$answer_users[$i]}}</a>さんの回答</h4>
                <p>{{$answer->content}}</p>
            </div>
            <div>
                <span>役に立ったと思った人：{{$answer->good_count}}<br></span>
                <a onclick="return confirm('この回答を評価しますか？')" href="{{ url('/goodanswer', $answer->id)  }}">役に立った</a>
            </div>
            
            <?php if($show_user==$answer->user_id): ?>
                <!-- <a data-toggle="modal" data-target="#modal3">回答を編集する</a> -->
                <div class="answer_edit_form">
                    <details>
                        <summary>回答を編集する</summary>
                            <form action= "{{ url('/answer/edit', $answer->id) }}" method="POST" class="form-horizontal">
                                {{csrf_field()}} 
                                <div class="form-group"> 
                                    <label>回答内容</label> 
                                    <textarea name="content" class="form-control" value="{{ old('content') }}" placeholder="詳細">{{$answer->content}}</textarea>
                                </div>
                                <div class="text-center"> 
                                    <button type="submit" class="btn btn-primary"　class="createBtn"> 更新する </button> 
                                </div>
                                <input type="hidden" name="q_id" value="{{ old('q_id', $question->id) }}">
                            </form>
                    </details>
                </div>
            <?php endif; ?>
            <details class="reply_form_content">
                <summary>この回答に対する返信</summary>
                @foreach ($reply_list[$i] as $reply)
                    <div class="reply_form">
                        <h4>返信内容</h4>
                        <p>{{$reply->content}}</p>
                    </div>
                @endforeach

                    <div class="text-center" >
                    <?php if($question->crear_flag==true): ?>

                    <?php elseif($show_user==null): ?>
                        <a href={{ route('login') }} >
                            ログインして返信する
                        </a> 
                    <?php else:?>
                    
                    
                        <form action= "{{ url('/reply/new', $answer->id) }}" method="POST" class="form-horizontal col-sm-offset-2 col-sm-8">
                            {{csrf_field()}} 
                            <div class="form-group"> 
                                <label>返信内容</label> 
                                <textarea name="content" class="form-control" value="{{ old('content') }}" placeholder="詳細">{{ old('content') }}</textarea>
                            </div>
                            <div class="text-center"> 
                                <button type="submit" class="btn btn-primary"　class="createBtn"> 返信する </button> 
                            </div>
                            <input type="hidden" name="q_id" value="{{ old('q_id', $question->id) }}">
                            <input type="hidden" name="parent_id" value="{{ old('parent_id', $answer->id) }}">
                        </form>
                    
                    
                    <?php endif;?>
                    </div>
            </details>
            <?php $i++; ?>
        </div>
        @endforeach
    </div>
    
</div>
@endsection