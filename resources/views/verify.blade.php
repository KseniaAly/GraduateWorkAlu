<form method="POST" action="{{route('verify.code')}}">
    @csrf
    <h2>Введите код подтверждения</h2>
    <input type="text"
           name="code"
           placeholder="Введите код">
    <input type="hidden"
           name="email"
           value="{{session('email')}}">
    <button type="submit">
        Подтвердить
    </button>
</form>
