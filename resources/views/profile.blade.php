@extends('layout.app')
@section('title')
    Профиль
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-start" style="margin-top: 100px">
        <div>
            <div class="mine d-flex" style="padding: 30px; position: relative">
                <div class="image">
                    <img src="{{asset($user->avatar)}}">
                    <button class="profile-btn"><i class="bi bi-camera"></i></button>
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
                <button class="edit"><i class="bi bi-pencil-square"></i></button>
            </div>
        </div>
    </div>

    <style>
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
            background: #f6f6f6;
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
            background: #f6f6f6;
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
            background: #f6f6f6;
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
    </style>
@endsection
