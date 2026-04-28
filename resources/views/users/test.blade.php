@extends('layout.app')
@section('title')
    Тест
@endsection
@section('content')
    <div class="mine d-flex justify-content-between align-items-center" style="margin-top: 100px; margin-bottom: 20px">
        <h1>"{{$test->title}}"</h1>
        <div class="d-flex align-items-center" style="width: 43%">
            <div class="d-flex align-items-center" style="width: 95%">
                <p style="margin-right: 10px">
                    Страница {{$test_questions->currentPage()}} из {{$test_questions->lastPage()}}
                </p>
                @php
                    $sessionAnswers = session('test_answers', []);
                    $total = $test_questions->total();
                    $answered = count($sessionAnswers);
                    $percent = $total ? round(($answered / $total) * 100) : 0;
                @endphp
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                     style="border-radius: 30px; height: 10px; width: 200px">
                    <div class="progress-bar" style="width:{{$percent}}%;; border-radius: 30px;
                background: linear-gradient(135deg, #E1E2FE 0%, #353896 100%);"></div>
                </div>
                <small style="margin-left: 5px; color: #777;">{{$percent}}%</small>
            </div>
            <div style="margin-left: 5px; text-align: right">
                <i class="bi bi-stopwatch"></i>
                Осталось времени:
                <span id="timer" style="color: #2f32bc; font-weight: 500">{{sprintf('%02d:%02d', floor($remainingTime / 60), $remainingTime % 60)}}</span>
            </div>
        </div>
    </div>
    <form action="{{route('test.save', ['test'=>$test])}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('post')
        @php
            $sessionAnswers = session('test_answers', []);
        @endphp
        <div class="container-questions">
            @foreach($test_questions as $test_question)
                <div class="question-item">
                    <div class="question-header">
                        <div class="question-number">{{$test_question->position}}</div>
                        <div class="question-title">
                            <h4>{{$test_question->question->title}}</h4>
                            <div class="d-flex justify-content-between">
                                <span class="question-type">
                                    {{$test_question->question->description}}
                                </span>
                                <div class="detail">
                                    <i class="bi bi-star-fill"></i>
                                    <span>Максимальный балл за вопрос: <span class="points-value">{{$test_question->question->points_max}}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="question-actions">
                        @if($test_question->question->category->id==2)
                            @foreach($question_options as $question_option)
                                @if($question_option->question_id==$test_question->question_id)
                                    <label class="answer-option">
                                        <input type="checkbox" name="answers[{{$test_question->question_id}}][]"
                                               value="{{$question_option->id}}"
                                            @if(isset($sessionAnswers[$test_question->question_id])
                                                && in_array($question_option->id,
                                                (array)($sessionAnswers[$test_question->question_id]['value'] ?? [])))
                                                checked
                                            @endif>
                                        <span class="custom-checkbox"></span>
                                            <span class="answer-text">{{$question_option->title}}</span>
                                            @if($question_option->description!=='-')
                                                <span class="question-type">{{$question_option->description}}</span>
                                            @endif
                                    </label>
                                @endif
                            @endforeach
                        @elseif($test_question->question->category->id==1)
                            @foreach($question_options as $question_option)
                                @if($question_option->question_id==$test_question->question_id)
                                    <label class="answer-option">
                                        <input type="radio" name="answers[{{$test_question->question_id}}]"
                                               value="{{$question_option->id}}"
                                        @if(isset($sessionAnswers[$test_question->question_id])
                                            && $sessionAnswers[$test_question->question_id]['value'] == $question_option->id)
                                            checked
                                        @endif>
                                        <span class="custom-checkbox"></span>
                                            <span class="answer-text">{{$question_option->title}}</span>
                                            @if($question_option->description!=='-')
                                                <span class="question-type">{{$question_option->description}}</span>
                                            @endif
                                    </label>
                                @endif
                            @endforeach
                        @elseif($test_question->question->category->id==3)
                            <input type="text" class="form-control-custom" placeholder="Напишите свой ответ"
                                   name="text_answers[{{$test_question->question_id}}]"
                                   value="{{$sessionAnswers[$test_question->question_id]['value'] ?? ''}}">
                        @elseif($test_question->question->category->id==4)
                            <div class="custom-file-upload">
                                <label for="file-input-{{$test_question->question_id}}" class="upload-button">
                                    <i class="bi bi-folder2-open"></i>
                                    Выберите файл
                                </label>
                                <input type="file" id="file-input-{{$test_question->question_id}}" class="file-input-hidden"
                                       name="file_answers[{{$test_question->question_id}}]" accept=".txt, .docx">
                                <span id="file-name-{{$test_question->question_id}}" class="file-name">
                                    {{ isset($sessionAnswers[$test_question->question_id])
                                        ? basename($sessionAnswers[$test_question->question_id]['value'])
                                        : 'Файл не выбран' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @if (!$test_questions->hasMorePages())
            <div style="position: absolute; right: 75px; bottom: -15px">
                <button type="submit"
                        class="filter-btn">
                    Завершить тест
                </button>
            </div>
        @endif
        <div class="w-100 d-flex justify-content-center">
            {{$test_questions->links('vendor.pagination.bootstrap-5')}}
        </div>
    </form>

    <script>
        document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', function () {
                let questionId = this.name.match(/\d+/)[0];
                let value;
                if (this.type === 'checkbox') {
                    value = Array.from(document.querySelectorAll(`input[name="${this.name}"]:checked`))
                        .map(element => element.value);
                } else {
                    value = this.value;
                }
                fetch("{{route('test.saveAnswer')}}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            "{{csrf_token()}}"
                    }, body: JSON.stringify({
                        question_id: questionId,
                        value: value,
                        type: this.type
                    })
                });
            });
        });
        document.querySelectorAll('input[type="text"]').forEach(input => {
            let timeout = null;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    let questionId = this.name.match(/\d+/)[0];
                    fetch("{{route('test.saveAnswer')}}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{csrf_token()}}"
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            value: this.value,
                            type: 'text'
                        })
                    });
                }, 500);
            });
        });
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function () {
                let questionId = this.name.match(/\d+/)[0];
                let fileNameSpan = document.getElementById('file-name-' + questionId);
                if (this.files.length > 0) {
                    fileNameSpan.textContent = this.files[0].name;
                } else {
                    fileNameSpan.textContent = 'Файл не выбран';
                }
                let formData = new FormData();
                formData.append('question_id', questionId);
                formData.append('file', this.files[0]);
                formData.append('_token', "{{csrf_token()}}");
                fetch("{{route('test.saveAnswer')}}", {
                    method: "POST",
                    body: formData
                });
            });
        });
    </script>
    <script>
        let timeLeft = {{$remainingTime}};
        let timerElement = document.getElementById('timer');
        let countdown = setInterval(() => {
            let hours = Math.floor(timeLeft / 3600);
            let minutes = Math.floor((timeLeft % 3600) / 60);
            let seconds = timeLeft % 60;
            seconds = seconds<10? '0'+seconds : seconds;
            if (hours===0){
                timerElement.textContent = minutes + ':' + seconds;
            } else {
                timerElement.textContent = hours + ':' + minutes + ':' + seconds;
            }
            timeLeft--;
            if (timeLeft < 0) {
                clearInterval(countdown);
                alert('Время теста истекло');
                document.querySelector('form').submit();
            }
        }, 1000);

        if (timeLeft <= 60) {
            timerElement.style.color = 'red';
        }
    </script>

<style>
    .mine h1{
        font-family: Unbounded;
        font-size: 20px;
        margin-bottom: 0;
    }
    .mine p{
        margin: 0;
        font-size: 16px;
        color: #687685;
    }
    .mine h3{
        font-size: 16px;
        font-family: Unbounded;
    }

    .question-item {
        background: white;
        border-radius: 50px;
        padding: 30px 50px;
        border: 1px solid #eef2ff;
        position: relative;
        margin-bottom: 1rem;
    }
    .question-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 0.75rem;
        padding-right: 120px;
    }
    .question-number {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2f32bc, #4f46e5);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(47, 50, 188, 0.2);
    }
    .question-title {
        flex: 1;
    }
    .question-title h4 {
        font-size: 1.1rem;
        font-family: Unbounded;
        font-weight: 400;
        color: #0f172a;
        margin-bottom: 0.25rem;
        line-height: 1.4;
    }
    .detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #64748b;
    }
    .detail i {
        color: #2f32bc;
        font-size: 1rem;
    }
    .points-value {
        font-weight: 400;
        color: #2f32bc;
        font-size: 1rem;
    }
    .question-type {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        background: #f8fafc;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #475569;
    }
    .question-actions {
        gap: 0.5rem;
        margin-top: 0.5rem;
        padding-left: 3rem;
    }

    .answer-option {
        position: relative;
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 24px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        margin-bottom: 10px;
    }
    .answer-option:hover {
        border-color: #2f32bc;
        background: linear-gradient(135deg, rgba(225, 226, 254, 0.05) 0%, rgba(53, 56, 150, 0.05) 100%);
        transform: translateX(8px);
        box-shadow: 0 4px 12px rgba(47, 50, 188, 0.15);
    }
    .answer-option:active {
        transform: translateX(4px) scale(0.99);
    }
    .answer-option input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .custom-checkbox {
        width: 24px;
        height: 24px;
        min-width: 24px;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        background: white;
        margin-right: 1rem;
        position: relative;
        transition: all 0.2s ease;
        display: inline-block;
    }
    .answer-option input[type="checkbox"]:checked + .custom-checkbox {
        background: #10b981;
        border-color: #10b981;
        animation: checkboxPop 0.3s ease;
    }
    .answer-option input[type="checkbox"]:checked + .custom-checkbox::after {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
        animation: checkmarkFade 0.2s ease;
    }
    .answer-option input[type="checkbox"]:checked + .custom-checkbox::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        animation: ripple 0.4s ease-out;
    }
    .answer-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .answer-option input[type="radio"] + .custom-checkbox {
        border-radius: 50%;
    }
    .answer-option input[type="radio"]:checked + .custom-checkbox {
        background: #10b981;
        border-color: #10b981;
        animation: checkboxPop 0.3s ease;
    }
    .answer-option input[type="radio"]:checked + .custom-checkbox::after {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
        animation: checkmarkFade 0.2s ease;
    }
    .answer-option input[type="radio"]:checked + .custom-checkbox::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        animation: ripple 0.4s ease-out;
    }
    .answer-text {
        flex: 1;
        font-size: 1rem;
        color: #1e293b;
        font-weight: 500;
        transition: all 0.2s ease;
        line-height: 1.4;
    }
    @keyframes checkboxPop {
        0% {
            transform: scale(0.9);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    @keyframes ripple {
        0% {
            width: 0;
            height: 0;
            opacity: 0.5;
        }
        100% {
            width: 40px;
            height: 40px;
            opacity: 0;
        }
    }
    @keyframes checkmarkFade {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.5);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    .file-input-hidden {
        display: none;
    }
    .upload-button {
        display: inline-block;
        padding: 10px 20px;
        background: linear-gradient(135deg, #2f32bc 0%, #675fe8 50%, #2f32bc 100%);
        background-size: 200% 100%;
        color: white;
        border-radius: 15px;
        cursor: pointer;
        background-position: left center;
        transition: background-position 0.5s ease;
    }
    .upload-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(47, 50, 188, 0.3);
        background-position: right center;
    }
    .file-name {
        margin-left: 10px;
        color: #666;
    }
</style>
@endsection
