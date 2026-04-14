@extends('layout.app')
@section('title')
    Регистрация
@endsection
@section('content')
    <div class="d-flex justify-content-between" style="margin-top: 100px">
        <div>
            <div class="mine" style="padding-bottom: 0; border-bottom-left-radius: 0; border-bottom-right-radius: 0">
                <h1 style="margin-bottom: 0">Регистрация</h1>
            </div>
            <form action="{{route('registrationUser')}}" method="post" style="position: relative; border-top-left-radius: 0; width: 165%;
                border-bottom-left-radius: 0" class="mine">
                @csrf
                @method('post')
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fio" placeholder="fio" name="fio">
                        <label for="fio">ФИО</label>
                        <div class="error" id="errorMessage"></div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="phone" placeholder="+7 (___) ___-__-__"
                               value="+7" name="phone">
                        <label for="phone">Номер телефона</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email">
                        <label for="email">Почта</label>
                    </div>
                    <div class="form-floating" style="position: relative">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                        <label for="password">Пароль</label>
                        <button class="toggle-password" type="button" id="togglePassword">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                <div class="mine" style="position: absolute; left: 0; padding-top: 0; margin-top: 20px;
                border-top-right-radius: 0; border-top-left-radius: 0">
                    <button type="submit" class="btn btn-mine">зарегистрироваться</button>
                </div>
            </form>
        </div>
        <div style="width: 45%" class="d-flex flex-column align-items-end">
            <div class="mine" style="padding-bottom: 0; border-bottom-right-radius: 0">
                <h1 style="text-align: center">Присоединяйтесь к нам</h1>
            </div>
            <div class="mine" style="text-align: right; width: 73%; padding-top: 20px;
            padding-bottom: 0; border-radius: 0">
                <p class="text" style="font-size: 14px; margin-bottom: 0">Создавай яркие, интерактивные тесты за 5 минут без единой строчки кода.</p>
            </div>
            <div class="mine" style="border-top-right-radius: 0; text-align: right; width: 93%">
                <p class="text">Если у вас уже есть акаунт, <a href="{{route('authorization')}}">войдите</a> в учетную запись</p>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('fio');
        const errorDiv = document.getElementById('errorMessage');

        input.addEventListener('input', function(e) {
            let value = this.value;
            let filtered = value.replace(/[^a-zA-Zа-яА-ЯёЁ\s-]/g, '');
            if (value !== filtered) {
                errorDiv.textContent = 'Можно вводить только буквы, пробелы и дефис';
                this.value = filtered;
            } else {
                errorDiv.textContent = '';
            }
        });

        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const icon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            if (type === 'text') {
                icon.className = 'bi bi-eye';
            } else {
                icon.className = 'bi bi-eye-slash';
            }
        });
        function checkPassword() {
            const password = passwordInput.value;
            if (!password) {
                passwordInput.classList.add('error');
                return;
            }
        }


    </script>

    <style>
        .mine h1{
            font-size: 40px;
            font-family: Unbounded;
        }

        .form-control{
            border: 1px solid black;
            border-radius: 20px;
            font-family: Unbounded;
            font-size: 14px;
        }
        .form-floating label{
            font-family: Unbounded;
        }

        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            margin-left: 10px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #94a3b8;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .toggle-password:hover {
            color: #667eea;
            background: #f1f5f9;
        }
        .toggle-password:active {
            transform: translateY(-50%) scale(0.9);
        }
    </style>
@endsection
