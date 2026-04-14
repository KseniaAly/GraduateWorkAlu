@extends('layout.app')
@section('title')
    Управление вакансиями
@endsection
@section('content')
    <div class="d-flex flex-column align-items-start" style="margin-top: 100px">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <div class="mine" style="padding-bottom: 20px; border-bottom-left-radius: 0">
                <h1>Управление вакансиями</h1>
            </div>
            <div>
                <div class="filter-container">
                    <form action="{{route('vacanciesFilter')}}" class="filter-form" method="get">
                        <div class="filter-select">
                            <select name="status" id="status">
                                <option value="all" selected>Все статусы</option>
                                <option value="pending">В поиске</option>
                                <option value="interview">Собеседование</option>
                                <option value="found">Нашли</option>
                            </select>
                        </div>
                        <div class="filter-select">
                            <select name="type" id="type">
                                <option value="all" selected>Все типы занятости</option>
                                <option value="full_time">Полная занятость</option>
                                <option value="part_time">Частичная занятость</option>
                                <option value="internship">Стажировка</option>
                                <option value="remote">Удаленная работа</option>
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
            <button class="btn btn-mine" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">добавить</button>
        </div>
    </div>
        <div class="cards-container mine" style="border-top-left-radius: 0;">
            @foreach($vacancies as $vacancy)
                <div class="vacancy-card">
                    <div class="card-header">
                        <div class="vacancy-info">
                            <h3 class="vacancy-title"><i class="bi bi-briefcase-fill"></i> {{$vacancy->title}}</h3>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="salary">
                                    от {{number_format($vacancy->salary, 0, ',', ' ')}} ₽
                                </div>
                                <div class="status-badge
                                @if($vacancy->status=='pending') status-pending
                                @elseif($vacancy->status=='interview') status-interview
                                @elseif($vacancy->status=='found') status-found @endif">
                                    @if($vacancy->status=='pending')
                                        <i class="bi bi-search"></i>
                                        В поиске кандидатов
                                    @elseif($vacancy->status=='interview')
                                        <i class="bi bi-calendar-check"></i>
                                        Есть кандидат на собеседовании
                                    @elseif($vacancy->status=='found')
                                        <i class="bi bi-trophy"></i>
                                        Кандидат найден
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-details">
                        <div class="detail-item employment-type">
                            <i class="bi bi-briefcase"></i>
                            @if($vacancy->type=='full_time')
                                Полная занятость
                            @elseif($vacancy->type=='part_time')
                                Частичная занятость
                            @elseif($vacancy->type=='internship')
                                Стажировка
                            @elseif($vacancy->type=='remote')
                                Удаленная работа
                            @endif
                        </div>
                        <div class="description">
                            <i class="bi bi-chat-quote" style="color: #2f32bc; margin-right: 0.1rem;"></i>
                            {{ Str::limit($vacancy->description, 120) }}
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="action-btn btn-primary-custom" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal{{$vacancy->id}}">
                            <i class="bi bi-pencil-square"></i> Редактировать
                        </button>
                        <form action="{{route('vacanciesDelete', ['vacancy'=>$vacancy])}}" method="post">
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
            {{$vacancies->links('vendor.pagination.bootstrap-5')}}
        </div>

    <div class="modal fade" id="exampleModal" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 50px">
                <div class="modal-header-custom">
                    <h2 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="bi bi-briefcase-fill"></i>
                        Создание вакансии
                    </h2>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body-custom">
                    <form method="post" action="{{route('vacanciesStore')}}">
                        @csrf
                        @method('post')
                        <div class="form-group">
                            <label>
                                <i class="bi bi-briefcase"></i>
                                Название вакансии
                            </label>
                            <input type="text" name="title" class="form-control-custom" placeholder="Например: Senior PHP Developer" required>
                            @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-currency-dollar"></i>
                                    Зарплата
                                </label>
                                <input type="text" name="salary" class="form-control-custom" placeholder="от 150 000 ₽">
                                @error('salary')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="bi bi-calendar"></i>
                                    Тип занятости
                                </label>
                                <select name="employment_type" class="form-control-custom">
                                    <option value="full_time">Полная занятость</option>
                                    <option value="part_time">Частичная занятость</option>
                                    <option value="internship">Стажировка</option>
                                    <option value="remote">Удаленная работа</option>
                                </select>
                                @error('employment_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="bi bi-file-text"></i>
                                Описание вакансии
                            </label>
                            <textarea name="description" class="form-control-custom" placeholder="Опишите обязанности, требования и условия работы..."></textarea>
                            @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-custom btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Отмена
                            </button>
                            <button type="submit" class="btn-custom btn-primary-custom">
                                <i class="bi bi-check-circle"></i> Опубликовать
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach($vacancies as $vacancy)
        <div class="modal fade" id="exampleModal{{$vacancy->id}}" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 50px">
                    <div class="modal-header-custom">
                        <h2 class="modal-title fs-5" id="exampleModalLabel">Редактирование: {{$vacancy->title}}</h2>
                        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body-custom">
                        <form method="post" action="{{route('vacanciesUpdate', ['vacancy'=>$vacancy])}}">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-briefcase"></i>
                                    Название вакансии
                                </label>
                                <input type="text" name="title" class="form-control-custom" value="{{$vacancy->title}}" required>
                                @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-currency-dollar"></i>
                                        Зарплата
                                    </label>
                                    <input type="text" name="salary" class="form-control-custom" value="{{$vacancy->salary}}">
                                    @error('salary')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-calendar"></i>
                                        Тип занятости
                                    </label>
                                    <select name="employment_type" class="form-control-custom">
                                        <option value="full_time"
                                            {{$vacancy->type == 'full_time' ? 'selected' : ''}}>
                                            Полная занятость</option>
                                        <option value="part_time"
                                            {{$vacancy->type == 'part_time' ? 'selected' : ''}}>
                                            Частичная занятость</option>
                                        <option value="internship"
                                            {{$vacancy->type == 'remote' ? 'selected' : ''}}>
                                            Стажировка</option>
                                        <option value="remote"
                                            {{$vacancy->type == 'internship' ? 'selected' : ''}}>
                                            Удаленная работа</option>
                                    </select>
                                    @error('employment_type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="bi bi-file-text"></i>
                                    Описание вакансии
                                </label>
                                <textarea name="description" class="form-control-custom">{{$vacancy->description}}</textarea>
                                @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-custom btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Отмена
                                </button>
                                <button type="submit" class="btn-custom btn-primary-custom">
                                    <i class="bi bi-check-circle"></i> Редактировать
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


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

    </style>
@endsection
