@extends('layout.app')
@section('title')
    Проверка ответов
@endsection
@section('content')
    <div class="mine d-flex justify-content-between align-items-center" style="margin-top: 100px; margin-bottom: 20px;
    padding-top: 30px; padding-bottom: 30px">
        <div>
            <h1>Ошибки в тесте</h1>
            <span class="question-type" style="margin-top: 5px">Просмотрите вопросы, на которые вы ответили неверно</span>
        </div>
        <div class="d-flex align-items-center" style="width: 32%">
            <div class="d-flex align-items-center">
                <p style="margin-right: 10px">
                    Правильно: {{$correct_count}} из {{$questions_count}}
                </p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                     style="border-radius: 30px; height: 10px; width: 200px">
                    <div class="progress-bar" style="width:{{$percentage}}%;; border-radius: 30px;
                background: linear-gradient(135deg, #E1E2FE 0%, #353896 100%);"></div>
                </div>
                <small style="margin-left: 5px; color: #777;">{{$percentage}}%</small>
            </div>
        </div>
    </div>
    <div class="container-questions">
        @foreach($test_questions as $test_question)
            @php
                $user_answer = $user_answers->firstWhere('question_id', $test_question->question_id);
                $saved_user_answers = [];
                $text_answer = '';
                $is_correct = false;
                $user_points = 0;
                $ai_feedback = '';
                if($user_answer){
                    $is_correct = $user_answer->is_correct ?? false;
                    $user_points = $user_answer->points ?? 0;
                    $ai_feedback = $user_answer->ai_feedback ?? '';
                    $category_id = $test_question->question->category_id ?? $test_question->question->category->id;
                    if(in_array($category_id, [1, 2])){
                        if(!empty($user_answer->answers)){
                            if(is_array($user_answer->answers)){
                                $saved_user_answers = $user_answer->answers;
                            } else {
                                $decoded = json_decode($user_answer->answers, true);
                                $saved_user_answers = is_array($decoded) ? $decoded : [];
                            }
                        }
                    } else {
                        $text_answer = $user_answer->answer ?? '';
                    }
                }
            @endphp
            <div class="question-item @if($user_answer && $user_answer->is_correct) right-answer @else false-answer @endif">
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
                                <span>Ваш балл за вопрос:
                                    <span class="points-value">{{$user_points}}/{{$test_question->question->points_max}}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="question-actions">
{{--Для нескольких вариантов--}}
                    @if($test_question->question->category->id==2)
                        @foreach($question_options as $question_option)
                            @if($question_option->question_id==$test_question->question_id)
                                @php
                                    $is_checked = is_array($saved_user_answers) && in_array((string)$question_option->id, $saved_user_answers);
                                @endphp
                                <div class="answer-option
                                @if($question_option->is_correct) right-option
                                @elseif($is_checked && !$question_option->is_correct) false-option
                                @endif">
                                    <input type="checkbox" @if($is_checked) checked @endif>
                                    <span class="custom-checkbox"></span>
                                    <div>
                                        <span class="answer-text">{{$question_option->title}}</span>
                                        <span class="question-type">{{$question_option->description}}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
{{--Для одного вариантов--}}
                    @elseif($test_question->question->category->id==1)
                        @foreach($question_options as $question_option)
                            @if($question_option->question_id==$test_question->question_id)
                                @php
                                    $is_checked = is_array($saved_user_answers) && in_array((string)$question_option->id, $saved_user_answers);
                                @endphp
                                <div class="answer-option
                                @if($question_option->is_correct) right-option
                                @elseif($is_checked && !$question_option->is_correct) false-option
                                @endif">
                                    <input type="radio" name="question_{{$test_question->question_id}}" @if($is_checked) checked @endif>
                                    <span class="custom-checkbox"></span>
                                    <div>
                                        <span class="answer-text">{{$question_option->title}}</span>
                                        <span class="question-type">{{$question_option->description}}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
{{--Для текстового ответа--}}
                    @elseif($test_question->question->category->id==3)
                        <input type="text" class="form-control-custom" placeholder="Напишите свой ответ"
                               value="{{$text_answer}}">
                        @if($ai_feedback)
                            <div class="comment-badge">
                                <div class="d-flex i">
                                    <i class="bi bi-lightbulb"></i>
                                    <p style="margin-bottom: 0; margin-left: 5px">Комментарий:</p>
                                </div>
                                <p style="margin-bottom: 0">
                                    {{$ai_feedback}}
                                </p>
                            </div>
                        @endif
{{--Загрузка файла--}}
                    @elseif($test_question->question->category->id==4)
                        <div class="custom-file-upload">
                            <label for="file-input-{{$test_question->id}}" class="upload-button">
                                <i class="bi bi-folder2-open"></i>
                                Выберите файл
                            </label>
                            <input type="file" id="file-input-{{$test_question->id}}" class="file-input-hidden" accept=".txt, .docx, .doc">
                            <span id="file-name-{{$test_question->id}}" class="file-name">
                                @if($text_answer)
                                    {{basename($text_answer)}}
                                @else
                                    Файл не выбран
                                @endif
                            </span>
                        </div>
                        @if($ai_feedback)
                            <div class="comment-badge">
                                <div class="d-flex i">
                                    <i class="bi bi-lightbulb"></i>
                                    <p style="margin-bottom: 0; margin-left: 5px">Комментарий:</p>
                                </div>
                                <p style="margin-bottom: 0">
                                    {{$ai_feedback}}
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="w-100 d-flex justify-content-center">
        {{$test_questions->links('vendor.pagination.bootstrap-5')}}
    </div>

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
        .question-item.right-answer{
            border-left: 5px solid #10b981;
        }
        .question-item.right-answer .question-number{
            background: #10b981;
        }
        .question-item.false-answer{
            border-left: 5px solid #d30018;
        }
        .question-item.false-answer .question-number{
            background: #d30018;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 10px;
        }
        .answer-option:active {
            transform: translateX(4px) scale(0.99);
        }
        .answer-option.right-option{
            border-color: #10b981;
        }
        .answer-option.false-option{
            border-color: #d30018;
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
        .answer-option.right-option input[type="checkbox"]:checked + .custom-checkbox {
            background: #10b981;
            border-color: #10b981;
        }
        .answer-option.right-option input[type="checkbox"]:checked + .custom-checkbox::after {
            content: "✓";
        }
        .answer-option.false-option input[type="checkbox"]:checked + .custom-checkbox {
            background: #d30018;
            border-color: #d30018;
        }
        .answer-option.false-option input[type="checkbox"]:checked + .custom-checkbox::after {
            content: "✕";
        }
        .answer-option input[type="checkbox"]:checked + .custom-checkbox::after {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            font-weight: bold;
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
        .answer-option.right-option input[type="radio"]:checked + .custom-checkbox {
            background: #10b981;
            border-color: #10b981;
        }
        .answer-option.right-option input[type="radio"]:checked + .custom-checkbox::after {
            content: "✓";
        }
        .answer-option.false-option input[type="radio"]:checked + .custom-checkbox {
            background: #d30018;
            border-color: #d30018;
        }
        .answer-option.false-option input[type="radio"]:checked + .custom-checkbox::after {
            content: "✕";
        }
        .answer-option input[type="radio"]:checked + .custom-checkbox::after {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            font-weight: bold;
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
        }
        .answer-text {
            flex: 1;
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
            transition: all 0.2s ease;
            line-height: 1.4;
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
            background-position: left center;
            transition: background-position 0.5s ease;
        }
        .file-name {
            margin-left: 10px;
            color: #666;
        }

        .comment-badge{
            padding: 10px 15px;
            background: #f1f5f9;
            margin-top: 10px;
            border-radius: 15px;
            font-size: 14px;
            color: #666;
            font-weight: 400;
        }
        .right-answer .comment-badge{
            border-left: 3px solid #10b981;
        }
        .right-answer .comment-badge .i{
            color: #10b981;
        }
        .false-answer .comment-badge{
            border-left: 3px solid #d30018;
        }
        .false-answer .comment-badge .i{
            color: #d30018;
        }
    </style>
@endsection
