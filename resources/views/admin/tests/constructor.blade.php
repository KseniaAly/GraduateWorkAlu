@extends('layout.app')
@section('title')
    Конструктор тестов
@endsection
@section('content')
    <form action="{{route('testStore')}}" method="post">
        @csrf
        @method('post')
        <div class="d-flex justify-content-between align-items-center" style="margin-top: 100px">
            <div class="mine" style="padding-top: 30px; padding-bottom: 30px">
                <h1>
                    Создание теста
                </h1>
                <p>Создайте тест для оценки кандидатов</p>
            </div>
            <div>
                <div class="filter-container">
                    <div class="filter-form">
                        <button type="submit" class="filter-btn">
                            <i class="bi bi-floppy"></i> Сохранить
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%; margin-top: 30px">
            <div class="mine">
                <h3 style="margin-bottom: 5px">Основная информация</h3>
                <div class="d-flex justify-content-between" style="margin-top: 20px">
                    <div>
                        <div class="form-group" style="margin-bottom: 10px">
                            <label>Название теста</label>
                            <input placeholder="Введите название теста" class="form-control-custom" name="title">
                        </div>
                        <div class="form-group">
                            <label>Вакансии</label>
                            <select class="form-control-custom" name="vacancy_id">
                                <option disabled selected>Выберите вакансию</option>
                                @foreach($vacancies as $vacancy)
                                    <option value="{{$vacancy->id}}">{{$vacancy->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Описание</label>
                        <textarea class="form-control-custom" placeholder="Краткое описание теста и его цели"
                        name="description"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group" style="margin-bottom: 0">
                        <label><i class="bi bi-stopwatch"></i> Время прохождения (мин)</label>
                        <input placeholder="60 минут" class="form-control-custom" name="limit_time">
                    </div>
                    <div class="form-group" style="margin-bottom: 0">
                        <label><i class="bi bi-trophy"></i>Проходной балл (%)</label>
                        <input placeholder="70%" class="form-control-custom" name="passing_score">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .mine h1{
            font-family: Unbounded;
            font-size: 24px;
            margin-bottom: 5px;
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

        .form-group{
            width: 600px;
        }


        .type-list {
            margin-top: 16px;
        }
        .type {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 16px;
            background: #F5F8FB;
            margin-bottom: 10px;
        }
        .type .small {
            color: #777;
            font-weight: 400;
            font-size: 14px;
        }
        .type i{
            color: #2f32bc;
            padding: 5px;
            background: #D9DDFA;
            border-radius: 10px;
        }
        .type p{
            color: #2f32bc;
            font-weight: 500;
        }
        .tip {
            margin-top: 16px;
            padding: 12px;
            border-radius: 14px;
            background: #ECEFFD;
            font-size: 14px;
            border-left: 3px solid #5175ff;
        }

        .container-questions{
            padding: 20px;
            background: #ECEFFD;
            margin-top: 20px;
            border-radius: 20px;
        }
        .container-questions i{
            font-size: 20px;
            color: #2f32bc;
            padding: 5px 10px;
            border: 1px solid white;
            border-radius: 100%;
        }
    </style>
@endsection
