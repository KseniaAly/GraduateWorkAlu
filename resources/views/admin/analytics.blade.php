@extends('layout.app')
@section('title')
    Кандидаты
@endsection
@section('content')
    <div class="d-flex flex-column align-items-start" style="margin-top: 100px">
        <div class="mine" style="padding-bottom: 30px; padding-top: 30px">
            <h1>Кандидаты для офлайн собеседования</h1>
            <p>Кандидаты, успешно прошедшие тесты и рекомендованные для офлайн собеседования</p>
        </div>
        <div class="mine w-100" style="padding-bottom: 30px; padding-top: 30px; margin-top: 30px">
            @foreach($results as $result)
                <div class="card-candidate">
                    <img src="{{asset('/storage/'.$result->user->avatar)}}" alt="">
                    <div>
                        <h3 style="margin-bottom: 2px">{{$result->user->fio}}</h3>
                        <p style="margin-bottom: 0">{{$result->user->email}}</p>
                        <p style="color: #0f172a">{{$result->user->phone}}</p>
                    </div>
                    <div>
                        <p style="margin-bottom: 0">Вакансия:</p>
                        <h5>{{$result->test->vacancy->title}}</h5>
                    </div>
                    <div>
                        <p style="margin-bottom: 0">Пройденный тест:</p>
                        <h3>{{$result->test->title}}</h3>
                    </div>
                    <div>
                        <p style="margin-bottom: 0">Затраченное время:</p>
                        <p style="color: #0f172a">{{$result->completed_time}}</p>
                    </div>
                    <div>
                        @php
                            $max_points = DB::table('test_questions')
                            ->join('questions', 'test_questions.question_id', '=', 'questions.id')
                            ->where('test_questions.test_id', $result->test->id)
                            ->sum('questions.points_max');
                            $percent = $result->total_points/$max_points * 100;
                            $radius = 40;
                            $circumference = 2 * M_PI * $radius;
                            $offset = $circumference-($percent/100)*$circumference;
                        @endphp
                        <div class="progress-circle">
                            <svg width="100" height="100" viewBox="0 0 100 100">
                                <circle class="bg" cx="50" cy="50" r="40"/>
                                <circle class="progress" cx="50" cy="50" r="40" id="progressCircle"
                                style="stroke-dasharray: {{$circumference}};
                                stroke-dashoffset: {{$offset}}"/>
                            </svg>
                            <div class="percent" id="percentText">
                                {{$percent}}%
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn-custom disabled" disabled
                        style="width: 150px; padding: 5px; background: #ececec;">Назначить собеседование</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .mine h1{
            font-family: Unbounded;
            font-size: 24px;
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

        .card-candidate{
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .card-candidate img{
            border-radius: 100%;
            width: 70px;
            height: 70px;
        }
        .card-candidate h5{
            font-size: 14px;
            font-family: Unbounded;
            padding: 5px 10px;
            background: #E2E3F8;
            color: #5C5C92;
            border-radius: 10px;
        }

        .progress-circle {
            position: relative;
            width: 100px;
            height: 100px;
            margin-top: 0;
            margin-bottom: 0;
        }
        svg {
            transform: rotate(-90deg);
        }
        .bg {
            fill: none;
            stroke: #E2E3F8;
            stroke-width: 7;
        }
        .progress {
            fill: none;
            stroke: #7578bf;
            stroke-width: 7;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.6s ease;
        }
        .percent {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 21px;
            font-weight: 600;
            color: #1e2a3a;
        }
    </style>
@endsection
