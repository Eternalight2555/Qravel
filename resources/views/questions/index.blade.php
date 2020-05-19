@extends("layouts.app")

@section('content')

<div class="top_wrapper">
    <div class="contain">
        <div class="ranking_wrapper">
            @foreach($questions as $question)
                <a href="#" class="question_link">
                    <div class="question">
                        <h3 class="question_title">{{ $question->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="contain">
        <div class="result_wrapper">
            
        </div>
    </div>
</div>



@endsection