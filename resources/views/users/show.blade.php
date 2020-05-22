@extends("layouts.app")

@section('content')

<div class="top_wrapper container">
    <div class="content">
        <div class="row">
            <div class="user_info_wrapper col-sm-offset-2 col-sm-8">
                <h2>ユーザ情報</h2>
                <!-- ユーザ情報 -->
                <div class="user_name">
                    <h3>ユーザ名</h3>
                    <p>{{ $user->name }}</p>
                </div>
                <div class="user_address">
                    <h3>メールアドレス</h3>
                    <p>{{ $user->email }}</p>
                </div>
                <div class="user_profile">
                    <h3>プロフィール</h3>
                    @if($user->profile != NULL)
                        <p>{{ $user->profile }}</p>
                    @else
                        <p>プロフィールなし</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if($user_id == Auth::user()->id)
        <div class="content">
            <div class="row">
                <div class="user_book_wrapper col-sm-offset-2 col-sm-8">
                
                    <!-- ブックマーク -->
                    <h2>ブックマークした質問</h2>
                    @foreach($bookmarked_questions as $bookmark)
                        <div class="user_question">
                            <div class="question">
                                <a href="/question/show/{{ $bookmark->id }}" class="question_link">
                                    <h3 class="question_title">{{ $bookmark->title }}</h3>
                                </a>
                            </div>
                            <div class="question-status">
                                <?php if($bookmark->crear_flag){ ?>
                                <p id="end">解決済</p>
                                <?php }else{ ?>
                                <p id="noend">受付中</p>
                                <?php } ?>
                            </div>
                            <div class="question-tag">ここにタグを表示</div>
                        </div>
                    @endforeach
                    
                </div>
            </div>
        </div>
    @endif
    <div class="content">
        <div class="row">
            <div class="user_question_wrapper col-sm-offset-2 col-sm-8">
                <h2>過去の質問</h2>
                <!-- 自分の質問 -->
                <div class="questions">
                @foreach($my_questions as $question)
                    <div class="user_question">
                        <div class="question">
                        <a href="/question/show/{{ $question->id }}" class="question_link">
                                <h3 class="question_title">{{ $question->title }}</h3>
                        </a>
                        </div>
                        <div class="question-status">
                            <?php if($question->crear_flag){ ?>
                            <p id="end">解決済</p>
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
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="user_answer_wrapper col-sm-offset-2 col-sm-8">
                <h2>回答した質問</h2>
                <!-- 自分の解答 -->
                <div class="questions">
                @foreach($answered_questions as $answered_question)
                <div class="user_answer">
                    <div class="question">
                        <a href="/question/show/{{ $answered_question->id }}" class="question_link">
                                <h3 class="question_title">{{ $answered_question->title }}</h3>
                        </a>
                    </div>
                    <div class="question-status">
                        <?php if($answered_question->crear_flag){ ?>
                        <p id="end">回答済</p>
                        <?php }else{ ?>
                        <p id="noend">受付中</p>
                        <?php } ?>
                    </div>
                    <div class="question-tag">
                        <p>
                        @foreach ($Atags[$answered_question->id] as $tagname) 
                            <span class="badge badge-secondary tag">{{ $tagname }}</span>
                        @endforeach
                        </p>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>



@endsection