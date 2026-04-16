@extends('layout.app')
@section('title')
    Каталог
@endsection
@section('content')
    <div class="d-flex flex-column align-items-start" style="margin-top: 100px">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <div class="mine" style="padding-top: 30px; padding-bottom: 30px">
                <h1>Каталог тестов</h1>
                <p class="p">Пройдите онлайн-собеседование на понравившуюся вакансию</p>
            </div>
            <form action="{{route('catalog')}}" method="get">
                @if(request('vacancies'))
                    <input type="hidden" name="vacancies" value="{{request('vacancies')}}">
                @endif
                <div class="search-block filter-container">
                    <div class="search-input">
                        <i class="bi bi-search" style="color: #687685"></i>
                        <input type="text" name="search" id="searchInput" value="{{request('search')}}"
                               placeholder="Поиск тестов по названию...">
                    </div>
                    <button class="filter-btn">
                        Найти
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mine" style="margin-top: 30px; padding: 0">
        <div class="d-flex">
            <div style="border-right: 1px solid rgba(47, 50, 188, 0.2); padding: 40px 20px 40px 50px; max-width: 350px">
                <h3>Вакансии</h3>
                <div>
                    <div class="vacancy-card" data-id="all">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-briefcase"></i>
                            <p>Все вакансии</p>
                        </div>
                        <div class="badge-vacancy">{{count($tests)}}</div>
                    </div>
                    @foreach($vacancies as $vacancy)
                        <div class="vacancy-card" data-id="{{$vacancy->id}}">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-briefcase"></i>
                                <p>{{$vacancy->title}}</p>
                            </div>
                            @if(array_key_exists($vacancy->id, $count_tests))
                                @foreach($count_tests as $countID => $count_test)
                                    @if($countID==$vacancy->id)
                                        <div class="badge-vacancy">{{$count_test}}</div>
                                    @endif
                                @endforeach
                            @else
                                <div class="badge-vacancy">0</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div style="padding: 40px 50px 40px 20px; width: auto">
                <div class="d-flex align-items-center justify-between">
                    <p class="p" style="font-size: 14px">Надено {{count($tests)}} теста</p>
                </div>
                <div class="d-flex flex-wrap" style="margin-top: 20px">
                    @foreach($tests as $test)
                        <div class="test-card">
                            <div class="card-top">
                                <div class="icon-box">
                                    <i class="bi bi-card-checklist"></i>
                                </div>
                                <div class="title-block">
                                    <div class="title-row">
                                        <div class="title">
                                            {{$test->title}}
                                        </div>
                                        <div class="percent">
                                            {{$test->passing_score}}%
                                        </div>
                                    </div>
                                    <div class="vacancy">
                                        {{$test->vacancy->title}}
                                    </div>
                                </div>
                            </div>
                            <div class="description">
                                {{$test->description}}
                            </div>
                            <div class="meta">
                                <div class="meta-item">
                                    <i class="bi bi-stopwatch"></i>
                                    {{$test->limit_time}} минут
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-list"></i>
                                    @foreach($counts as $countID => $count)
                                        @if($countID==$test->id)
                                            {{$count}} вопросов
                                        @endif
                                    @endforeach
                                </div>
                                <a href="{{route('testCatalog', ['test'=>$test])}}" class="meta-item published" style="text-decoration: none">
                                    Пройти тест
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll(".vacancy-card");
            const allCard = document.querySelector('[data-id="all"]');
            const vacancyCards = document.querySelectorAll('.vacancy-card:not([data-id="all"])');
            const totalVacancies = vacancyCards.length;
            let params = new URLSearchParams(window.location.search);
            let selectedVacancies = params.get("vacancies") ? params.get("vacancies").split(",") : [];
            if (selectedVacancies.length === 0) {
                allCard.classList.add("active");
            } else {
                vacancyCards.forEach(card => {
                    if (selectedVacancies.includes(card.dataset.id)) {
                        card.classList.add("active");
                    }
                });
                allCard.classList.remove("active");
            }
            cards.forEach(card => {
                card.addEventListener("click", function () {
                    let id = this.dataset.id;
                    if (id === "all") {
                        selectedVacancies = [];
                        updateUrl();
                        return;
                    }
                    this.classList.toggle("active");
                    if (selectedVacancies.includes(id)) {
                        selectedVacancies = selectedVacancies.filter(v => v !== id);
                    } else {
                        selectedVacancies.push(id);
                    }
                    if (selectedVacancies.length === totalVacancies) {
                        selectedVacancies = [];
                    }
                    updateUrl();
                });
            });
            function updateUrl() {
                let url = new URL(window.location);
                let searchValue = document.querySelector('input[name="search"]').value;
                if (selectedVacancies.length > 0) {
                    url.searchParams.set("vacancies", selectedVacancies.join(","));
                } else {
                    url.searchParams.delete("vacancies");
                }
                if (searchValue) {
                    url.searchParams.set("search", searchValue);
                }
                window.location.href = url;
            }
        });
    </script>

    <style>
        .mine h1{
            font-family: Unbounded;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .mine .p{
            margin: 0;
            font-size: 16px;
            color: #687685;
        }
        .mine h3{
            font-size: 16px;
            font-family: Unbounded;
            margin-bottom: 20px;
        }

        .vacancy-card{
            border: 1px solid rgba(47, 50, 188, 0.2);
            padding: 10px;
            border-radius: 15px;
            display: flex;
            font-size: 14px;
            align-items: center;
            margin-bottom: 5px;
            transition: all 0.3s ease;;
            background: none;
            justify-content: space-between;
            color: #1a202c;
            font-weight: 400;
            cursor: pointer;
        }
        .vacancy-card p{
            margin-left: 5px;
            margin-bottom: 0;
        }
        .vacancy-card:hover{
            border-color: #4A42E0;
            color: #4A42E0;
            background: #eef1ff;
        }
        .vacancy-card.active{
            background: #eef1ff;
            color: #4A42E0;
            font-weight: 600;
            border-color: #4A42E0;
        }
        .vacancy-card .badge-vacancy{
            padding: 5px 10px;
            font-size: 12px;
            background: #F5F5F9;
            border-radius: 30px;
        }
        .vacancy-card.active .badge-vacancy{
            background: white;
        }
        .vacancy-card:hover .badge-vacancy{
            background: white;
        }

        .search-block {
            display: flex;
            gap: 16px;
        }
        .search-input {
            display: flex;
            align-items: center;
            background: white;
            padding: 12px 16px;
            border-radius: 30px;
            width: 480px;
        }
        .search-input input {
            border: none;
            outline: none;
            margin-left: 10px;
            width: 100%;
        }



        .test-card {
            background: white;
            border-radius: 30px;
            padding: 20px;
            border: 1px solid #e6e8f0;
            position: relative;
            overflow: hidden;
            transition: 0.35s ease;
            max-width: 436px;
            margin-right: 20px;
        }
        .test-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 45px rgba(75,71,204,0.15);
        }
        .test-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: -120%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.45), transparent);
            transition: 0.7s;
        }
        .test-card:hover::before {
            left: 120%;
        }
        .card-top {
            display: flex;
            gap: 14px;
            margin-bottom: 14px;
        }
        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 15px;
            background: #eef1ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #4b47cc;
        }
        .title-block {
            flex: 1;
        }
        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .title {
            font-size: 18px;
            font-weight: 600;
            max-width: 260px;
        }
        .vacancy {
            font-size: 14px;
            color: #8a94a6;
            margin-top: 4px;
        }
        .percent {
            background: #d9f5e7;
            color: #1fbf75;
            padding: 6px 12px;
            border-radius: 14px;
            font-weight: 600;
        }
        .description {
            color: #5c667a;
            font-size: 15px;
            margin-bottom: 18px;
            line-height: 1.5;
        }
        .meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .meta-item {
            background: #eef1ff;
            color: #4b47cc;
            padding: 6px 12px;
            border-radius: 14px;
            font-size: 14px;
        }
        .meta-item.published {
            background: #d9f5e7;
            color: #17a45b;
            transition: all 0.2s;
            border: 1px solid #17a45b;
        }
        .meta-item.published:hover{
            background: #17a45b;
            color: white;
            transform: translateY(-2px);
        }
    </style>
@endsection
