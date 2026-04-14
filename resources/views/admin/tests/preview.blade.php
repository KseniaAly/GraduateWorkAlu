@extends('layout.app')
@section('title')
    Предпросмотр теста
@endsection
@section('content')
    <div class="mine d-flex justify-content-between align-items-center" style="margin-top: 100px">
        <h1>Предпросмотр теста "{{$test->title}}"</h1>
        <div class="d-flex align-items-center" style="width: 40%">
            <p style="margin-right: 10px">Вопрос 3 из 8</p>
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
            style="border-radius: 30px; height: 10px; width: 70%">
                <div class="progress-bar" style="width: 45%; border-radius: 30px;
                background: linear-gradient(135deg, #E1E2FE 0%, #353896 100%);"></div>
            </div>
            <small style="margin-left: 5px; color: #777;">10%</small>
        </div>
    </div>
    <div class="mine" style="margin-top: 30px; padding: 30px">
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
                                    <div class="answer-option">
                                        <input type="checkbox" @if($question_option->is_correct) checked @endif>
                                        <span class="custom-checkbox"></span>
                                        <div>
                                            <span class="answer-text">{{$question_option->title}}</span>
                                            <span class="question-type">{{$question_option->description}}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @elseif($test_question->question->category->id==1)
                            @foreach($question_options as $question_option)
                                @if($question_option->question_id==$test_question->question_id)
                                    <div class="answer-option">
                                        <input type="radio" @if($question_option->is_correct) checked @endif>
                                        <span class="custom-checkbox"></span>
                                        <div>
                                            <span class="answer-text">{{$question_option->title}}</span>
                                            <span class="question-type">{{$question_option->description}}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @elseif($test_question->question->category->id==3)
                            <input type="text" class="form-control-custom" placeholder="Напишите свой ответ">
                        @elseif($test_question->question->category->id==4)
                            <div class="custom-file-upload">
                                <label for="file-input" class="upload-button">
                                    <i class="bi bi-folder2-open"></i>
                                    Выберите файл
                                </label>
                                <input type="file" id="file-input" class="file-input-hidden" accept="image/*">
                                <span id="file-name" class="file-name">Файл не выбран</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
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

        .container-questions{
            padding: 20px;
            background: #ECEFFD;
            border-radius: 20px;
        }
        .question-item {
            background: white;
            border-radius: 20px;
            padding: 1.2rem 1.5rem;
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
            overflow: hidden;
            margin-bottom: 10px;
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
            display: inline-block;
        }
        .answer-option input[type="checkbox"]:checked + .custom-checkbox {
            background: #10b981;
            border-color: #10b981;
        }
        .answer-option input[type="checkbox"]:checked + .custom-checkbox::after {
            content: "✓";
            position: absolute;
            top: 50%;
            left: 50%;
            color: white;
            font-size: 14px;
            font-weight: bold;
            transform: translate(-50%, -50%);
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
        }
        .answer-option input[type="radio"]:checked + .custom-checkbox::after {
            content: "✓";
            position: absolute;
            top: 50%;
            left: 50%;
            color: white;
            font-size: 14px;
            font-weight: bold;
            transform: translate(-50%, -50%);
        }
        .answer-text {
            flex: 1;
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
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
