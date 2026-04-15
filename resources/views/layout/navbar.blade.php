<div class="d-flex justify-content-between" style="position: fixed; width: 90%; z-index: 2">
    <div class="nav-mine">
        <img src="{{asset('/images/logo.png')}}" style="width: 35px">
        <a href="{{route('welcome')}}" style="text-decoration: none"><h3 style="margin-left: 10px">Наймикс</h3></a>
    </div>
    <div class="nav-mine">
        @guest()
            <div class="link-div">
                <a class="link" href="{{route('authorization')}}">Вход</a>
                <div class="line"></div>
            </div>
            <div class="link-div" style="margin-left: 30px">
                <a class="link" href="{{route('registration')}}">Регистрация</a>
                <div class="line"></div>
            </div>
        @endguest
        @auth()
            <div class="link-div" style="margin-right: 20px">
                <a class="link" href="{{route('catalog')}}">Каталог тестов</a>
                <div class="line"></div>
            </div>
                <div class="link-div" style="margin-right: 10px">
                    <a class="link" href="{{route('profile')}}">Профиль</a>
                    <div class="line"></div>
                </div>
            @if(\Illuminate\Support\Facades\Auth::user()->role=='admin')
                <li class="nav-item dropdown" style="list-style-type: none">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    style="color: #2f32bc">
                        <i class="bi bi-grid"></i> Админ
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{route('tests')}}">
                                <i class="bi bi-file-code"></i> Конструктор тестов
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('vacancies')}}">
                                <i class="bi bi-briefcase"></i> Управление вакансиями
                            </a>
                        </li>
{{--                        <li>--}}
{{--                            <a class="dropdown-item" href="#">--}}
{{--                                <i class="bi bi-chat-dots"></i> Чат-боты--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li><hr class="dropdown-divider"></li>
{{--                        <li>--}}
{{--                            <a class="dropdown-item" href="#">--}}
{{--                                <i class="bi bi-rocket-takeoff"></i> API--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li>
                            <a class="dropdown-item" href="{{route('analytics')}}">
                                <i class="bi bi-pie-chart"></i> Аналитика
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <div class="link-div">
                <a class="link button" href="{{route('logout')}}">Выйти</a>
            </div>
        @endauth
    </div>
</div>

<style>
    .button{
        padding: 5px 20px;
        position: relative;
        background: none;
        border: 1px solid black;
        border-radius: 50px;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.3s ease;
        z-index: 1;
    }
    .button::before{
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: #2f32bc;
        transition: left 0.4s cubic-bezier(0.65, 0, 0.35, 1);
        z-index: -1;
        border-radius: inherit;
    }
    .button:hover::before{
        left: 0;
    }
    .button:hover{
        color: white !important;
    }

    .dropdown-menu {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(200, 201, 255, 0.5);
        border-radius: 24px;
        padding: 0.75rem;
        margin-top: 0.75rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(200, 201, 255, 0.2);
        opacity: 0;
        transform: scale(0.9);
        transition: all 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
        display: block;
        visibility: hidden;
        transform-origin: top center;
    }
    .dropdown-menu.show {
        opacity: 1;
        transform: scale(1);
        visibility: visible;
    }
    .dropdown-item {
        color: black;
        padding: 0.85rem 1.2rem;
        border-radius: 16px;
        margin-bottom: 0.25rem;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .dropdown-item:hover {
        color: white;
        background: rgba(47, 50, 188, 0.3);
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(47, 50, 188, 0.2);
    }

    .dropdown-item i {
        font-size: 1.2rem;
        transition: all 0.25s ease;
    }

    .dropdown-item:hover i {
        transform: scale(1.15);
        color: #2f32bc;
    }
    .dropdown-divider {
        background: rgba(47, 50, 188, 0.4);
        margin: 0.5rem 0;
    }

    /* Стрелка анимация */
    .dropdown-toggle::after {
        transition: transform 0.3s ease;
    }

    .dropdown.show .dropdown-toggle::after {
        transform: rotate(180deg);
    }


    /*темная тема*/
    /*.dropdown-menu {*/
    /*    background: rgba(15, 23, 42, 0.98);*/
    /*    backdrop-filter: blur(12px);*/
    /*    border: 1px solid rgba(47, 50, 188, 0.5);*/
    /*    border-radius: 24px;*/
    /*    padding: 0.75rem;*/
    /*    margin-top: 0.75rem;*/
    /*    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(47, 50, 188, 0.2);*/
    /*    opacity: 0;*/
    /*    transform: scale(0.9);*/
    /*    transition: all 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);*/
    /*    display: block;*/
    /*    visibility: hidden;*/
    /*    transform-origin: top center;*/
    /*}*/
    /*.dropdown-item {*/
    /*    color: #e2e8f0;*/
    /*    padding: 0.85rem 1.2rem;*/
    /*    border-radius: 16px;*/
    /*    margin-bottom: 0.25rem;*/
    /*    transition: all 0.25s ease;*/
    /*    display: flex;*/
    /*    align-items: center;*/
    /*    gap: 0.75rem;*/
    /*}*/
</style>
