@extends('layout.app')
@section('title')
Конструктор тестов
@endsection
@section('content')
    <form action="{{route('testUpdate', ['test'=>$test])}}" method="post">
        @csrf
        @method('put')
<div class="d-flex justify-content-between align-items-center" style="margin-top: 100px">
    <div class="mine" style="padding-top: 30px; padding-bottom: 30px">
        <h1>
            Редактирование теста
        </h1>
        <p>Создайте тест для оценки кандидатов</p>
    </div>
    <div>
        <div class="filter-container">
            <div class="filter-form">
                <a href="{{route('testPreview', ['test'=>$test])}}" class="btn-custom btn-secondary" style="text-decoration: none">
                    <i class="bi bi-eye"></i>
                    Предпросмотр
                </a>
                <button type="submit" class="filter-btn">
                    <i class="bi bi-floppy"></i>
                    Сохранить
                </button>
{{--                @if($test->status=='redact')--}}
{{--                    <button type="button" class="filter-btn">--}}
{{--                        <i class="bi bi-send"></i> Опубликовать--}}
{{--                    </button>--}}
{{--                @elseif($test->status=='active')--}}
{{--                    <button type="button" class="filter-btn">--}}
{{--                        <i class="bi bi-pencil-square"></i> В редактирование--}}
{{--                    </button>--}}
{{--                @endif--}}
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-between align-items-start" style="margin-top: 25px;">
    <div style="width: 65%">
        <div class="mine main">
            <h3 style="margin-bottom: 5px">Основная информация</h3>
            <div class="d-flex justify-content-between" style="margin-top: 20px">
                <div>
                    <div class="form-group" style="margin-bottom: 10px">
                        <label>Название теста</label>
                        <input placeholder="Введите название теста" class="form-control-custom"
                        name="title" value="{{$test->title}}">
                    </div>
                    <div class="form-group">
                        <label>Вакансии</label>
                        <select class="form-control-custom" name="vacancy_id">
                            <option value="{{$test->vacancy->id}}" selected>{{$test->vacancy->title}}</option>
                            @foreach($vacancies as $vacancy)
                                @if($test->vacancy->id !== $vacancy->id)
                                    <option value="{{$vacancy->id}}">{{$vacancy->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" class="form-control-custom" placeholder="Краткое описание теста и его цели">{{$test->description}}</textarea>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-group" style="margin-bottom: 0">
                    <label><i class="bi bi-stopwatch"></i> Время прохождения (мин)</label>
                    <input placeholder="60 минут" class="form-control-custom" name="limit_time" value="{{$test->limit_time}}">
                </div>
                <div class="form-group" style="margin-bottom: 0">
                    <label><i class="bi bi-trophy"></i>Проходной балл (%)</label>
                    <input placeholder="70%" class="form-control-custom" name="passing_score" value="{{$test->passing_score}}">
                </div>
            </div>
        </div>
        <div class="mine" style="margin-top: 30px">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 style="margin-bottom: 5px">Вопросы теста</h3>
                    <p>Добавьте вопросы разных типов или одного типа по желанию</p>
                </div>
                <div class="d-flex">
                    <button class="filter-btn" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-plus-lg"></i>
                        Создать вопрос
                    </button>
                    @if(count($questions)!==0)
                        <button class="filter-btn" type="button" data-bs-toggle="modal"
                                data-bs-target="#addExistingQuestions"
                                style="padding: 10px 15px; margin-left: 5px; font-size: 20px">
                            <i class="bi bi-box-arrow-in-down"></i>
                        </button>
                    @endif
                </div>
            </div>
            <div class="container-questions d-flex flex-column align-items-center">
                @if(count($test_questions)==0)
                    <i class="bi bi-folder2-open"></i>
                    <h6 style="font-weight: 400; margin-top: 15px">Пока нет вопросов</h6>
                    <p>Добавьте свой первый вопрос</p>
                @else
                    <div id="sortable-questions">
                        @foreach($test_questions as $test_question)
                            <div class="question-item" data-id="{{$test_question->id}}">
                                <div class="question-header">
                                    <div class="drag-handle">
                                        <i class="bi bi-list"></i>
                                    </div>
                                    <div class="question-number">{{$test_question->position}}</div>
                                    <div class="question-title">
                                        <h4>{{$test_question->question->title}}</h4>
                                        <span class="question-type">
                                            <i class="bi bi-pencil"></i>
                                            {{$test_question->question->category?->title}}
                                        </span>
                                    </div>
                                </div>
                                <div class="question-description">
                                    {{$test_question->question->description}}
                                </div>
                                <div class="question-details">
                                    <div class="detail">
                                        <i class="bi bi-star-fill"></i>
                                        <span>Максимальный балл за вопрос: <span class="points-value">{{$test_question->question->points_max}}</span></span>
                                    </div>
                                </div>
                                <div class="question-actions">
                                    <button class="action-btn btn-edit" type="button" data-bs-toggle="modal" data-bs-target="#editQuestion{{$test_question->id}}">
                                        <i class="bi bi-pencil"></i> Редактировать
                                    </button>
                                    <button class="action-btn btn-delete" form="delete-form">
                                        <i class="bi bi-trash"></i> Удалить
                                    </button>
                                    @if($test_question->question->category?->id==1 || $test_question->question->category?->id==2)
                                        <button class="action-btn btn-primary-custom" type="button" data-bs-toggle="modal"
                                                data-bs-target="#optionsModal{{$test_question->question->id}}">
                                            <i class="bi bi-folder-plus"></i> Варинаты ответов
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-start" style="width: 33%">
        <div class="mine types">
            <h3 style="margin-bottom: 5px">Типы вопросов</h3>
            <p style="font-size: 14px">Выберите тип вопроса, чтобы добавить его в тест</p>
            <div class="type-list">
                <div class="type">
                    <i class="bi bi-check2-circle"></i>
                    <div>
                        <p>Один вариант</p>
                        <p class="small">Выбор одного правильного ответа</p>
                    </div>
                </div>
                <div class="type">
                    <i class="bi bi-check2-square"></i>
                    <div>
                        <p>Несколько вариантов</p>
                        <p class="small">Выбор нескольких правильных ответов</p>
                    </div>
                </div>
                <div class="type">
                    <i class="bi bi-card-text"></i>
                    <div>
                        <p>Текстовый ответ</p>
                        <p class="small">Свободный текстовый ответ</p>
                    </div>
                </div>
                <div class="type">
                    <i class="bi bi-code-slash"></i>
                    <div>
                        <p>Код</p>
                        <p class="small">Написание кода</p>
                    </div>
                </div>
            </div>
            <div class="tip d-flex align-items-center">
                <i class="bi bi-lightbulb" style="color: #2f32bc; margin-right: 10px"></i>
                <p style="font-size: 14px; font-weight: 500; color: #2f32bc">Совет: Комбинируйте разные типы вопросов
                    для более точной оценки кандидатов</p>
            </div>
        </div>
    </div>
</div>
    </form>

    <div class="modal fade" id="exampleModal" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 50px">
                <div class="modal-header-custom">
                    <h2 class="modal-title fs-5" id="exampleModalLabel">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        Создание вопроса
                    </h2>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body-custom">
                    <form method="post" action="{{route('questionCreate', ['test'=>$test])}}">
                        @csrf
                        @method('post')
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-card-list"></i>
                                    Тип вопроса
                                </label>
                                <select name="question_category" class="form-control-custom">
                                    @foreach($question_categories as $question_category)
                                        <option value="{{$question_category->id}}">{{$question_category->title}}</option>
                                    @endforeach
                                </select>
                                @error('question_category')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-award"></i>
                                    Максимальное количество балов
                                </label>
                                <input type="text" name="points_max" class="form-control-custom" placeholder="Например: 10" required>
                                @error('points_max')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <i class="bi bi-question-circle"></i>
                                Вопрос
                            </label>
                            <input type="text" name="title" class="form-control-custom" placeholder="Например: Для чего используется язык PHP?" required>
                            @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>
                                <i class="bi bi-file-text"></i>
                                Описание вопроса
                            </label>
                            <textarea name="description" class="form-control-custom" placeholder="Опишите что необходимо сделать кандидату..."></textarea>
                            @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-custom btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Отмена
                            </button>
                            <button type="submit" class="btn-custom btn-primary-custom">
                                <i class="bi bi-check-circle"></i> Добавить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(count($test_questions)!==0)
        <form action="{{route('testQuestionDelete', ['test_question'=>$test_question])}}" method="post"
              id="delete-form">
            @csrf
            @method('delete')
        </form>
    @endif

    @foreach($test_questions as $test_question)
{{--        Редактирование--}}
        <div class="modal fade" id="editQuestion{{$test_question->id}}" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 50px">
                    <div class="modal-header-custom">
                        <h2 class="modal-title fs-5" id="exampleModalLabel">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            Редактирование вопроса
                        </h2>
                        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body-custom">
                        <form method="post" action="{{route('questionEdit', ['question'=>$test_question->question])}}">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-card-list"></i>
                                        Тип вопроса
                                    </label>
                                    <select name="question_category" class="form-control-custom">
                                        <option selected value="{{$test_question->question->category->id}}">{{$test_question->question->category->title}}</option>
                                        @foreach($question_categories as $question_category)
                                            @if($test_question->question->category->id !== $question_category->id)
                                                <option value="{{$question_category->id}}">{{$question_category->title}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('question_category')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-award"></i>
                                        Максимальное количество балов
                                    </label>
                                    <input type="text" name="points_max" class="form-control-custom" placeholder="Например: 10" value="{{$test_question->question->points_max}}" required>
                                    @error('points_max')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-question-circle"></i>
                                    Вопрос
                                </label>
                                <input type="text" name="title" class="form-control-custom" placeholder="Например: Для чего используется язык PHP?" required
                                       value="{{$test_question->question->title}}">
                                @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-file-text"></i>
                                    Описание вопроса
                                </label>
                                <textarea name="description" class="form-control-custom" placeholder="Опишите что необходимо сделать кандидату...">{{$test_question->question->description}}</textarea>
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


{{--        Создание варинатов ответа--}}
        <div class="modal fade" id="optionsModal{{$test_question->question->id}}" tabindex="-2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius: 50px">
                    <div class="modal-header-custom">
                        <h2 class="modal-title fs-5" id="optionsModal{{$test_question->question->id}}">
                            <i class="bi bi-ui-checks"></i>
                            Варианты ответов
                        </h2>
                        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <form method="post" action="{{route('question.options.update', $test_question->question->id)}}">
                        @csrf
                        <div class="modal-body-custom">
                            <div id="optionsList{{$test_question->question->id}}" class="options-list">
                                @foreach($test_question->question->options?->sortBy('position') as $option)
                                    <div class="option-item" data-id="{{$option->id}}">
                                        <div class="drag-handle">
                                            <i class="bi bi-list"></i>
                                        </div>
                                        <div class="option-body">
                                            <input type="text" name="options[{{$option->id}}][title]" placeholder="Текст ответа"
                                            value="{{$option->title}}" class="form-control-custom mb-2">
                                            <textarea name="options[{{$option->id}}][description]" class="form-control-custom"
                                            placeholder="Описание">{{$option->description}}</textarea>
                                        </div>
                                        <input type="checkbox" name="options[{{$option->id}}][is_correct]"
                                        {{$option->is_correct?'checked':''}} class="correct-checkbox">
                                        <button type="button" class="btn btn-destroy-custom remove-option" style="border: none; color: white">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="filter-btn add-option" data-question="{{$test_question->question->id}}">
                                <i class="bi bi-plus-lg"></i>
                                Добавить вариант
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn-custom btn-primary-custom">
                                <i class="bi bi-check-circle"></i>
                                Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="addExistingQuestions" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 50px">
                <form method="post" action="{{route('questions.attach', $test)}}">
                    @csrf
                    @method('post')
                    <div class="modal-header-custom">
                        <h2 class="modal-title fs-5" id="exampleModalLabel">
                            <i class="bi bi-folder-fill"></i>
                            Добавить существующие вопросы
                        </h2>
                        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body-custom modal-new">
                        <div class="form-group">
                            <input type="text" class="form-control-custom" id="searchQuestions"
                                   placeholder="Поиск по названию или описанию..." autocomplete="off">
                        </div>
                        @foreach($questions as $question)
                            <div class="form-group group">
                                <label class="question-select">
                                    <input type="checkbox" name="questions[]" value="{{$question->id}}">
                                    <strong class="question-title-text">{{$question->title}}</strong>
                                    <small class="question-desc-text">{{$question->description}}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-custom btn-primary-custom">
                            <i class="bi bi-check-circle"></i>
                            Добавить выбранные
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        let sortable = new Sortable(
            document.getElementById('sortable-questions'),
            {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function(){
                    updateNumbers();
                    let order = [];
                    document.querySelectorAll('.question-item')
                        .forEach((element, index) => {
                            order.push({
                                id: element.dataset.id,
                                position: index+1
                            });
                        });
                    fetch('/tests/questions/reorder',{
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order: order
                        })
                    });
                }
            }
        );
        function updateNumbers(){
            document.querySelectorAll('.question-number').forEach((element, index) =>{
                element.innerText = index+1;
            });
        }

        //Поиск
        document.getElementById('searchQuestions').addEventListener('input', function () {
            let value = this.value.toLowerCase().trim();
            document.querySelectorAll('#addExistingQuestions .group')
                .forEach(element => {
                    let title = element.querySelector('.question-title-text')?.innerText.toLowerCase() || '';
                    let desc = element.querySelector('.question-desc-text')?.innerText.toLowerCase() || '';
                    if (title.includes(value) || desc.includes(value)) {
                        element.style.display = 'block';
                    } else {
                        element.style.display = 'none';
                    }
                });
        });

        //Добавление варианта
        let optionIndex = 0;
        document.querySelectorAll('.add-option').forEach(btn=>{
                btn.addEventListener('click',function(){
                    let list=document.getElementById(
                        'optionsList'+this.dataset.question
                    );
                    let html=`
<div class="option-item">
    <span  class="drag-handle">
        <i class="bi bi-list"></i>
    </span>
    <div class="option-body">
        <input type="text" name="new_options[${optionIndex}][title]" class="form-control-custom mb-2" placeholder="Текст ответа">
        <textarea name="new_options[${optionIndex}][description]" class="form-control-custom" placeholder="Описание"></textarea>
    </div>
    <input type="checkbox" name="new_options[${optionIndex}][is_correct]" class="correct-checkbox">
    <button type="button" class="btn btn-delete remove-option">
        <i class="bi bi-x"></i>
    </button>
</div>
`;
                    list.insertAdjacentHTML('beforeend',html);
                    optionIndex++;
                });
        });
        //позиции обновление
        function updatePositions(list){
            let data = [];
            list.querySelectorAll('.option-item')
                .forEach((element,index)=>{
                    data.push({
                        id: element.dataset.id,
                        position: index + 1
                    });
                });
            fetch('/tests/options/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
                },
                body: JSON.stringify(data)
            });
        }
        //удаление варианта
        document.addEventListener('click', function(element){
            let btn = element.target.closest('.remove-option');
            if (!btn) return;
            let option = btn.closest('.option-item');
            let optionId = option.dataset.id;
            if (optionId) {
                fetch(`/question-options/${optionId}`,{
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        'Accept': 'application/json'
                    }
                }).then(response => response.json())
                    .then(data=>{
                        option.remove();
                        let list = document.querySelector('.options-list');
                        updatePositions(list);
                    })
            }
        });
        //drag and drop для варинатов
        document.querySelectorAll('.options-list')
            .forEach(list=>{
                new Sortable(list,{
                    animation:150,
                    handle:'.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd:function() {
                        let data = [];
                        list.querySelectorAll('.option-item')
                            .forEach((element, index) => {
                                data.push({
                                    id: element.dataset.id,
                                    position: index + 1
                                });
                            });
                        fetch('/tests/options/reorder', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN':
                                document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        });
                    }
                });
        });
    </script>

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

    .main .form-group{
        width: 370px;
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
    .container-questions .bi-folder2-open{
        font-size: 20px;
        color: #2f32bc;
        padding: 5px 10px;
        border: 1px solid white;
        border-radius: 100%;
    }



    .question-item {
        background: white;
        border-radius: 20px;
        margin-bottom: 1rem;
        padding: 1.2rem 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid #eef2ff;
        position: relative;
    }
    .question-item:hover {
        transform: translateX(5px);
        border-color: #2f32bc30;
        box-shadow: 0 8px 20px rgba(47, 50, 188, 0.08);
    }
    .question-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 0.75rem;
        padding-right: 120px;
    }
    .question-number {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2f32bc, #4f46e5);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(47, 50, 188, 0.2);
    }
    .question-title {
        flex: 1;
    }
    .question-title h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
        line-height: 1.4;
    }
    .question-description {
        font-size: 0.85rem;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 1rem;
        padding-left: 3rem;
    }
    .question-details {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        padding-left: 3rem;
    }
    .detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #64748b;
    }
    .detail i {
        color: #2f32bc;
        font-size: 1rem;
    }
    .points-value {
        font-weight: 700;
        color: #2f32bc;
        font-size: 1rem;
    }
    .question-type {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        background: #f8fafc;
        border-radius: 20px;
        font-size: 0.7rem;
        color: #475569;
    }
    .question-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
        padding-left: 3rem;
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
    .btn-edit {
        background: #eef2ff;
        color: #2f32bc;
    }
    .btn-edit:hover {
        background: #2f32bc;
        color: white;
        transform: translateY(-2px);
    }
    .btn-delete {
        background: #fef2f2;
        color: #ef4444;
    }
    .btn-delete:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .question-item {
        animation: slideIn 0.3s ease forwards;
    }

    .drag-handle {
        cursor: grab;
        margin-right: 10px;
        padding: 6px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
    .drag-handle i {
        font-size: 18px;
        color: #64748b;
    }
    .sortable-ghost {
        opacity: 0.5;
        background: #eef2ff;
    }
    #sortable-questions{
        height: 500px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .question-select {
        display: flex !important;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 0 !important;
    }
    .question-select:hover {
        background: #f1f5f9;
        border-color: #2f32bc;
        transform: translateX(5px);
    }
    .question-select input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        min-width: 22px;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        margin-top: 2px;
    }
    .question-select input[type="checkbox"]:hover {
        border-color: #2f32bc;
        transform: scale(1.05);
    }
    .question-select input[type="checkbox"]:checked {
        background: linear-gradient(135deg, #2f32bc 0%, #353896 100%);
        border-color: #2f32bc;
    }
    .question-select input[type="checkbox"]:checked::before {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
    }
    .question-select input[type="checkbox"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(47, 50, 188, 0.2);
    }
    .question-select strong {
        color: #1e293b;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.4;
        flex: 1;
    }
    .question-select small {
        margin: 0.5rem 0 0 0;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: normal;
        line-height: 1.4;
    }
    .modal-new {
        max-height: 60vh;
        overflow-y: auto;
    }
    .modal-body-custom::-webkit-scrollbar {
        width: 8px;
    }
    .modal-body-custom::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .modal-body-custom::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #E1E2FE 0%, #353896 100%);
        border-radius: 10px;
    }
    .modal-body-custom::-webkit-scrollbar-thumb:hover {
        background: #2f32bc;
    }

    .options-list{
        max-height: 350px;
        overflow-y: auto;
    }
    .options-list::-webkit-scrollbar {
        width: 5px;
    }
    .options-list::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .options-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #E1E2FE 0%, #353896 100%);
        border-radius: 10px;
    }
    .options-list::-webkit-scrollbar-thumb:hover {
        background: #2f32bc;
    }
    .options-list input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        min-width: 22px;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        margin-top: 2px;
    }
    .options-list input[type="checkbox"]:hover {
        border-color: #2f32bc;
        transform: scale(1.05);
    }
    .options-list input[type="checkbox"]:checked {
        background: linear-gradient(135deg, #2f32bc 0%, #353896 100%);
        border-color: #2f32bc;
    }
    .options-list input[type="checkbox"]:checked::before {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
    }
    .options-list input[type="checkbox"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(47, 50, 188, 0.2);
    }

    .option-item{
        background: white;
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        transition: 0.25s;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .option-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.12);
    }
    .sortable-ghost {
        opacity: 0.4;
    }
</style>
@endsection
