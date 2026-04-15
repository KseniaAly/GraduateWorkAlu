@extends('layout.app')
@section('title')
    Профиль
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-start" style="margin-top: 100px">
        <div>
            <div class="mine d-flex" style="padding: 30px; position: relative; width: 630px">
                <div class="image">
                    <img src="{{asset('/storage/'.$user->avatar)}}">
                    <form method="post" action="{{route('profileRedactAvatar', ['user'=>$user])}}" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        @method('put')
                        <label for="avatarInput" class="profile-btn">
                            <i class="bi bi-camera"></i>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                    </form>
                </div>
                <div class="info">
                    <h1>{{$user->fio}}</h1>
                    <div class="d-flex align-items-baseline">
                        <p class="email">{{$user->email}}</p>
                        <div class="email-badge @if($user->email_verified_at) checked @else not-checked @endif">
                            @if($user->email_verified_at)
                                <i class="bi bi-check-circle"></i> Подтверждена
                            @else
                                <i class="bi bi-x-circle"></i> Не подтверждена
                            @endif
                        </div>
                    </div>
                    <div class="d-flex" style="margin-top: 15px">
                        <div class="profile-card" style="margin-right: 10px">
                            <p class="title">Номер телефона</p>
                            <p>{{$user->phone}}</p>
                        </div>
                        <div class="profile-card">
                            <p class="title">Роль</p>
                            <p>@if($user->role=='admin') Администратор
                                @else Пользователь @endif</p>
                        </div>
                    </div>
                </div>
                <button class="edit" type="button" data-bs-target="#redact_profile" data-bs-toggle="modal"><i class="bi bi-pencil-square"></i></button>
            </div>
            <div class="mine" style="margin-top: 20px; padding: 30px">
                <div class="d-flex align-items-center">
                    <h2>Статистика</h2>
                    <div class="select">

                    </div>
                </div>
                <div class="d-flex">
                    <div class="card-static">
                        <div class="icon"><i class="bi bi-activity"></i></div>
                        <div>
                            <p>5</p>
                            <p class="title">Пройдено тестов</p>
                        </div>
                    </div>
                    <div class="card-static" style="margin-left: 20px">
                        <div class="icon" style="background: #FEE9EA; color: #F42836"><i class="bi bi-graph-up"></i></div>
                        <div>
                            <p>90%</p>
                            <p class="title">Средний результат</p>
                        </div>
                    </div>
                    <div class="card-static" style="margin-left: 20px">
                        <div class="icon"><i class="bi bi-clock"></i></div>
                        <div>
                            <p>3ч</p>
                            <p class="title">Общее время</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="mine" style="padding: 30px; width: 690px">
                <h2>Недавняя активность</h2>
                <div>
                    <div class="card-active">
                        <div class="d-flex align-items-center w-100">
                            <div class="icon"><i class="bi bi-file-earmark-text"></i></div>
                            <div>
                                <div class="title">Тест на аналитические способности</div>
                                <div class="description">Тест покажет ваши аналитические способности.</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="result">92%</div>
                            <i class="bi bi-caret-right" style="color: gray"></i>
                        </div>
                    </div>
                    <div class="card-active">
                        <div class="d-flex align-items-center w-100">
                            <div class="icon"><i class="bi bi-file-earmark-text"></i></div>
                            <div>
                                <div class="title">Проверка знаний laravel</div>
                                <div class="description">Содержит тестовые вопросы и задания на написание кода.</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="result not-enough">75%</div>
                            <i class="bi bi-caret-right" style="color: gray"></i>
                        </div>
                    </div>
                    <div class="card-active">
                        <div class="d-flex align-items-center w-100">
                            <div class="icon"><i class="bi bi-file-earmark-text"></i></div>
                            <div>
                                <div class="title">Проверка знаний для frontend-разработчика</div>
                                <div class="description">Тест содержит 10 вопросов для проверки знаний frontend-разработчика...</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="result">76%</div>
                            <i class="bi bi-caret-right" style="color: gray"></i>
                        </div>
                    </div>
                </div>
                <h5>Посмотреть всю активность</h5>
            </div>
        </div>
    </div>

    <div class="modal fade" id="redact_profile" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 50px">
                <div class="modal-header-custom">
                    <h2 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="bi bi-person-vcard-fill"></i>
                        Редактирование данных
                    </h2>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body-custom">
                    <form method="post" action="{{route('profileRedact', ['user'=>$user])}}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label>
                                <i class="bi bi-person"></i>
                                ФИО
                            </label>
                            <input type="text" name="fio" class="form-control-custom" placeholder="Например: Иванов иван" required
                            value="{{$user->fio}}">
                            @error('fio')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-envelope-at"></i>
                                    Почта
                                </label>
                                <input type="email" name="email" class="form-control-custom" placeholder="Например: ivanov@gmail.com"
                                value="{{$user->email}}">
                                @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="bi bi-telephone"></i>
                                    Номер телефона
                                </label>
                                <input type="tel" name="phone" class="form-control-custom" placeholder="Например: +71234567890"
                                value="{{$user->phone}}">
                                @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
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

    <script>
        document.getElementById('avatarInput')
            .addEventListener('change', function() {
                if (this.files.length > 0) {
                    document
                        .getElementById('uploadForm')
                        .submit();
                }
            });
    </script>

    <style>
        .mine h2{
            font-size: 18px;
            font-family: Unbounded;
        }

        .image{
            position: relative;
            margin-right: 20px;
        }
        .image img{
            width: 150px;
            height: 150px;
            border-radius: 100%;
        }
        .image .profile-btn{
            position: absolute;
            bottom: 10px;
            right: 0;
            background: #F8F9FC;
            padding: 6px 10px;
            border-radius: 100%;
            border: none;
            color: #2f32bc;
            font-size: 18px;
            transition: all 0.4s ease;
        }
        .image .profile-btn:hover{
            background: #2f32bc;
            color: white;
        }
        .info h1{
            font-family: Unbounded;
            font-size: 24px;
        }
        .info .email{
            color: gray;
            margin-right: 20px;
            margin-bottom: 0;
        }
        .info .email-badge{
            font-size: 14px;
            padding: 2px 7px;
            border-radius: 15px;
        }
        .info .email-badge.checked{
            color: #17a45b;
            background: #d9f5e7;
        }
        .info .email-badge.not-checked{
            color: #a41717;
            background: #f5d9d9;
        }
        .profile-card{
            background: #F8F9FC;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .profile-card p{
            margin-bottom: 0;
            font-weight: 500;
            font-size: 14px;
        }
        .profile-card .title{
            color: gray;
            font-weight: 400;
        }
        .edit{
            position: absolute;
            top: 20px;
            right: 30px;
            border: none;
            background: #F8F9FC;
            padding: 3px 7px;
            border-radius: 10px;
            color: #2f32bc;
            font-size: 18px;
            transition: all 0.4s ease;
        }
        .edit:hover{
            background: #2f32bc;
            color: white;
        }
        .card-static{
            padding: 10px;
            border-radius: 15px;
            background-color: #F5F6FE;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .icon{
            padding: 5px 10px;
            border-radius: 10px;
            background: #E9EBFE;
            color: #2f32bc;
            margin-right: 10px;
        }
        .card-static p{
            margin-bottom: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .card-static .title{
            color: gray;
            font-weight: 400;
            font-size: 12px;
        }
        .card-active{
            padding: 5px 10px;
            display: flex;
            border-bottom: 1px solid #e3e3e3;
            align-items: center;
        }
        .card-active .title{
            font-size: 16px;
            font-weight: 500;
        }
        .card-active .description{
            font-size: 12px;
            color: gray;
        }
        .card-active .result{
            padding: 2px 7px;
            color: #17a45b;
            background: #d9f5e7;
            border-radius: 10px;
            font-size: 14px;
            margin-right: 10px;
        }
        .card-active .result.not-enough{
            color: #a41717;
            background: #f5d9d9;
        }
        .mine h5{
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 0;
            color: #2f32bc;
            text-decoration: underline;
        }
    </style>
@endsection
