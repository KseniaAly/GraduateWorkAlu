@extends('layout.app')
@section('title')
    Управление тестами
@endsection
@section('content')
    <div class="d-flex flex-column align-items-start" style="margin-top: 100px">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <div class="mine" style="padding-bottom: 20px; border-bottom-left-radius: 0">
                <h1>Управление тестами</h1>
            </div>
            <div>
                <div class="filter-container">
                    <form action="{{route('testsFilter')}}" class="filter-form" method="get">
                        <div class="filter-select">
                            <select name="status" id="status">
                                <option value="all" selected>Все статусы</option>
                                <option value="redact">В черновиках</option>
                                <option value="active">Опубликован</option>
                            </select>
                        </div>
                        <div class="filter-select">
                            <select name="vacancies" id="type">
                                <option value="all" selected>Все вакансии</option>
                                @foreach($vacancies as $vacancy)
                                    <option value="{{$vacancy->id}}">{{$vacancy->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="filter-btn">
                            <i class="bi bi-funnel"></i> Применить
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="mine" style="padding-bottom: 0; padding-top: 0; border-radius: 0">
            <a href="{{route('testCreate')}}" class="btn btn-mine">Создать тест</a>
        </div>
        <div class="cards-container mine" style="border-top-left-radius: 0;">
            @foreach($tests as $test)
                <div class="vacancy-card">
                    <div class="card-header">
                        <div class="vacancy-info">
                            <h3 class="vacancy-title"><i class="bi bi-chat-right-text-fill"></i> {{$test->title}}</h3>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="salary">
                                    {{$test->limit_time}} минут
                                </div>
{{--                                <div class="salary">--}}
{{--                                    {{$test->passing_score}}%--}}
{{--                                </div>--}}
                                <div class="status-badge
                                @if($test->status=='active') status-found
                                @elseif($test->status=='redact') status-pending @endif">
                                    @if($test->status=='active')
                                        <i class="bi bi-check-circle"></i>
                                        Тест опубликован
                                    @elseif($test->status=='redact')
                                        <i class="bi bi-pencil-square"></i>
                                        Тест еще редактируется
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-details">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="detail-item employment-type">
                                <i class="bi bi-chat-right-text"></i>
                                {{$test->vacancy->title}}
                            </div>
                            <div class="salary">
                                {{$test->passing_score}}%
                            </div>
                        </div>
                        <div class="description">
                            <i class="bi bi-chat-quote" style="color: #2f32bc; margin-right: 0.1rem;"></i>
                            {{ Str::limit($test->description, 120) }}
                        </div>
                        @if($test->status=='redact')
                            <form action="{{route('testUpdateStatusActive', ['test'=>$test])}}" method="post">
                                @csrf
                                @method('post')
                                <button class="action-btn btn-publish" type="submit">
                                    <i class="bi bi-send"></i> Опубликовать
                                </button>
                            </form>
                        @elseif($test->status=='active')
                            <form action="{{route('testUpdateStatusRedact', ['test'=>$test])}}" method="post">
                                @csrf
                                @method('post')
                                <button class="action-btn btn-redact" type="submit">
                                    <i class="bi bi-pencil-square"></i> Вернуть в черновики
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="card-actions">
                        <a href="{{route('testEdit', ['test'=>$test])}}" class="action-btn btn-primary-custom" style="text-decoration: none">
                            <i class="bi bi-pencil-square"></i> Редактировать
                        </a>
                        <form action="{{route('testDelete', ['test'=>$test])}}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="action-btn btn-destroy-custom">
                                <i class="bi bi-trash"></i> Удалить
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-100 d-flex justify-content-center">
            {{$tests->links('vendor.pagination.bootstrap-5')}}
        </div>
    </div>
    <style>
        .mine h1{
            font-family: Unbounded;
            font-size: 24px;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            max-width: 1400px;
            width: 100%;
        }
        .vacancy-card {
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(47, 50, 188, 0.2);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            width: 388px;
        }
        .vacancy-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 40px rgba(0, 0, 0, 0.15);
        }
        .status-badge {
            font-family: Unbounded;
            padding: 0.4rem 0.7rem;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 400;
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            gap: 0.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }
        .status-interview {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }
        .status-found {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        .card-header {
            padding: 1.8rem 1.8rem 0 1.8rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }
        .vacancy-info {
            flex: 1;
        }
        .vacancy-title {
            font-family: Unbounded;
            font-size: 16px;
            font-weight: 700;
            color: black;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        .salary {
            font-family: Unbounded;
            background: linear-gradient(135deg, #E1E2FE, #353896);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 400;
        }
        .card-details {
            padding: 1.5rem 1.8rem;
        }
        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            font-size: 0.9rem;
            background: #f8fafc;
            padding: 0.4rem 0.8rem;
            border-radius: 40px;
        }
        .detail-item i {
            color: #2f32bc;
            font-size: 1rem;
        }
        .employment-type {
            background: #eef2ff;
            color: #2f32bc;
            font-weight: 500;
        }
        .description {
            margin: 1rem 0;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 16px;
            font-size: 0.9rem;
            color: #334155;
            line-height: 1.6;
            position: relative;
            border-left: 3px solid #2f32bc;
        }
        .card-actions {
            padding: 1rem 1.8rem 1rem 1.8rem;
            display: flex;
            gap: 0.8rem;
            border-top: 1px solid #e2e8f0;
        }
        .action-btn {
            font-family: Unbounded;
            flex: 1;
            padding: 0.7rem;
            border-radius: 40px;
            font-weight: 400;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .vacancy-card {
            animation: slideIn 0.5s ease forwards;
        }
        .card-actions a:hover{
            color: white;
        }

        .action-btn {
            padding: 0.4rem 0.8rem;
            border-radius: 40px;
            border: none;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-publish {
            background: #efffee;
            color: #059669;
        }
        .btn-publish:hover {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            transform: translateY(-2px);
        }
        .btn-redact{
            background: #fff5ee;
            color: #d97706;
        }
        .btn-redact:hover{
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            transform: translateY(-2px);
        }
    </style>
@endsection
