@extends('layout.app')
@section('title')
    Результат
@endsection
@section('content')
    <div class="mine" style="margin-top: 100px; padding: 30px 50px">
        <div>
            <div class="d-flex align-items-center" style="margin-bottom: 15px">
                <h4>Результат теста</h4>
                <div class="badge">
                    <i class="bi bi-check2-circle" style="font-size: 14px; margin-right: 5px"></i>
                    Завершен
                </div>
            </div>
            <h1>Название теста</h1>
            <div class="d-flex" style="margin-top: 10px">
                <div class="detail">
                    <i class="bi bi-person"></i>
                    Кандидат: Иван Иванович
                </div>
                <div class="detail">
                    <i class="bi bi-calendar-event"></i>
                    Дата завершения: 24 мая 2024, 14:35
                </div>
                <div class="detail">
                    <i class="bi bi-clock"></i>
                    Время прохождения: 48 минут
                </div>
            </div>
            <div class="content" style="margin-top: 20px">
                <div class="d-flex align-items-center">
                    <div style="text-align: center">
                        <p>Общий результат</p>
                        <div class="progress-circle">
                            <svg width="160" height="160" viewBox="0 0 160 160">
                                <circle class="bg" cx="80" cy="80" r="70"/>
                                <circle class="progress" cx="80" cy="80" r="70" id="progressCircle"/>
                            </svg>
                            <div class="percent" id="percentText">
                                85%
                            </div>
                        </div>
                        <h4>Хороший результат</h4>
                    </div>
                    <div style="margin-left: 50px">
                        <p>Вы продемонстрировали свои профессиональные способноси.
                        Если вы подходите компании, с вами свяжутся в ближайщее время для назначения офлайн собеседования</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setProgress(percent) {
            const circle = document.getElementById("progressCircle");
            const text = document.getElementById("percentText");
            const radius = 70;
            const circumference = 2 * Math.PI * radius;
            circle.style.strokeDasharray = circumference;
            const offset = circumference - (percent / 100) * circumference;
            circle.style.strokeDashoffset = offset;
            text.textContent = percent + "%";
        }
        setProgress(85);
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

        .mine h4{
            font-size: 16px;
            color: #1a202c;
            margin-bottom: 0;
        }
        .badge{
            margin-left: 20px;
            padding: 5px 10px;
            font-weight: 400;
            border-radius: 15px;
            background: #d9f5e7;
            color: #1fbf75;
            display: flex;
            align-items: center;
        }
        .detail {
            font-size: 14px;
            color: #8a94a6;
            margin-right: 20px;
        }
        .detail i{
            color: #2f32bc;
            margin-right: 2px;
        }
        .content{
            padding: 20px;
            background: #ECEFFD;
            border-radius: 20px;
            width: fit-content;
        }

        .progress-circle {
            position: relative;
            width: 160px;
            height: 160px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        svg {
            transform: rotate(-90deg);
        }
        .bg {
            fill: none;
            stroke: #e6e9f2;
            stroke-width: 10;
        }
        .progress {
            fill: none;
            stroke: #3EC29B;
            stroke-width: 10;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.6s ease;
        }
        .percent {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
            font-weight: 600;
            color: #1e2a3a;
        }
    </style>
@endsection
