@extends('layout.app')
@section('title')
    Главная
@endsection
@section('content')
    <div class="d-flex justify-content-between" style="margin-top: 100px">
        <div></div>
        <div class="mine"
             style="padding-bottom: 0; border-bottom-right-radius: 0; border-bottom-left-radius: 0">
            <p>Конструктор разрабатывался для копмании <a href="https://proton-group.ru"
                style="color: #2f32bc">ООО “Протон”</a></p>
        </div>
    </div>

    <div class="mine"
    style="border-top-right-radius: 0; position: relative; border-bottom-left-radius: 0">
        <p style="font-size: 18px">Создавай яркие, интерактивные тесты за 5 минут без единой строчки кода.</p>
        <h1 style="margin-top: 40px; margin-bottom: 40px">Добро пожаловать в конструктор тестов</h1>
        <img id="image_questions" src="{{asset('/images/image_questions.png')}}">
        @guest()
        <a class="btn btn-mine" href="{{route('authorization')}}">войти</a>
        @endguest
        @auth()
            @if(\Illuminate\Support\Facades\Auth::user()->role=='admin')
                <a class="btn btn-mine" href="{{route('tests')}}">создать тест</a>
            @else
                <a class="btn btn-mine" href="">посмотреть тесты</a>
            @endif
        @endauth
    </div>
    <div style="height: 50px; width: 70%; background: white; border-bottom-right-radius: 50px;
    border-bottom-left-radius: 50px; margin-bottom: 200px"></div>
    <div class="d-flex flex-column align-items-center" style="position: relative; margin-bottom: 60px">
        <div class="mine" style="text-align: center;">
            <h1 style="margin-bottom: 0; position: relative; z-index: 2">Как работает данный конструктор?</h1>
        </div>
        <div class="mine w-100 d-flex justify-content-between" style="position: absolute; top: 130px;">
            <p class="text">Шаг 1. Конструктор вопросов</p>
            <p class="text">Шаг 4. Умный отчет</p>
        </div>
        <div class="mine d-flex justify-content-between" style="width: 75%">
            <p class="text">Шаг 2. Проверка на спам</p>
            <p class="text">Шаг 3. Прохождение</p>
        </div>
    </div>

    <div class="mine d-flex">
        <div class="d-flex align-items-start">
            <i class="bi bi-shield-shaded"></i>
            <div class="mine-card">
                <h4>Защита от накруток</h4>
                <p>Ограничение по времени на вопрос, запрет на копирование текста, привязка к номеру телефона или email — отсеиваем случайных людей и ботов.</p>
            </div>
        </div>
        <div class="d-flex align-items-start">
            <i class="bi bi-images"></i>
            <div class="mine-card">
                <h4>Мультимедийные ответы</h4>
                <p>Кандидат может прикрепить портфолио, загрузить файл с резюме прямо в процессе ответа или записать короткое видео.</p>
            </div>
        </div>
        <div class="d-flex align-items-start">
            <i class="bi bi-lightning-fill"></i>
            <div class="mine-card">
                <h4>Автоматическое оценивание</h4>
                <p>Кандидаты с высоким рейтингом автоматически попадают в нашу базу данных с пометкой «Готов к собеседованию».</p>
            </div>
        </div>
    </div>

    <style>
        .mine p{
            margin-bottom: 0;
            font-size: 16px;
        }
        .mine h1{
            font-size: 50px;
            font-family: Unbounded;
            width: 700px;
        }
        #image_questions{
            position: absolute;
            bottom: 20px;
            right: 50px;
            width: 230px;
        }

        .mine i{
            font-size: 25px;
        }
        .mine .mine-card h4{
            font-size: 16px;
            font-family: Unbounded;
            margin-bottom: 5px;
        }
        .mine .mine-card{
            margin-left: 10px;
            margin-top: 10px;
            width: 90%;
        }
        .mine .mine-card p{
            font-size: 12px;
            font-family: Unbounded;
            margin-bottom: 0;
        }
    </style>
@endsection
