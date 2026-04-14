<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\PageController::class, 'welcome'])->name('welcome');
Route::get('/registration', [\App\Http\Controllers\PageController::class, 'registration'])->name('registration');
Route::get('/authorization', [\App\Http\Controllers\PageController::class, 'authorization'])->name('authorization');

Route::post('/registration/user', [Usercontroller::class, 'registration'])->name('registrationUser');
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::get('/logout', [\App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::get('/vacancies', [\App\Http\Controllers\VacancyController::class, 'index'])->name('vacancies');
Route::post('/vacancies/store', [\App\Http\Controllers\VacancyController::class, 'store'])->name('vacanciesStore');
Route::put('/vacancies/update/{vacancy}', [\App\Http\Controllers\VacancyController::class, 'update'])->name('vacanciesUpdate');
Route::delete('/vacancies/delete/{vacancy}', [\App\Http\Controllers\VacancyController::class, 'destroy'])->name('vacanciesDelete');
Route::get('/vacancies/filter', [\App\Http\Controllers\VacancyController::class, 'filter'])->name('vacanciesFilter');

Route::get('/tests', [\App\Http\Controllers\TestController::class, 'index'])->name('tests');
Route::get('/tests/filter', [\App\Http\Controllers\TestController::class, 'filter'])->name('testsFilter');
Route::get('/tests/create', [\App\Http\Controllers\TestController::class, 'create'])->name('testCreate');
Route::post('/tests/store', [\App\Http\Controllers\TestController::class, 'store'])->name('testStore');
Route::get('/tests/edit/{test}', [\App\Http\Controllers\TestController::class, 'edit'])->name('testEdit');
Route::put('/tests/update/{test}', [\App\Http\Controllers\TestController::class, 'update'])->name('testUpdate');
Route::post('/tests/update/status/active/{test}', [\App\Http\Controllers\TestController::class, 'updateStatusActive'])->name('testUpdateStatusActive');
Route::post('/tests/update/status/redact/{test}', [\App\Http\Controllers\TestController::class, 'updateStatusRedact'])->name('testUpdateStatusRedact');
Route::delete('/tests/delete/{test}', [\App\Http\Controllers\TestController::class, 'destroy'])->name('testDelete');

Route::post('/tests/question/create/{test}', [\App\Http\Controllers\QuestionController::class, 'store'])->name('questionCreate');
Route::post('/tests/questions/reorder',
    [\App\Http\Controllers\QuestionController::class, 'reorder']
);
Route::post('/tests/{test}/attach-questions', [\App\Http\Controllers\QuestionController::class, 'attach'])->name('questions.attach');
Route::put('/test/question/edit/{question}', [\App\Http\Controllers\QuestionController::class, 'update'])->name('questionEdit');
Route::delete('/test/questions/delete/{test_question}', [\App\Http\Controllers\QuestionController::class, 'destroy'])->name('testQuestionDelete');

Route::post('/tests/question/{id}/options/store', [\App\Http\Controllers\QuestionOptionController::class, 'store'])->name('question.options.update');
Route::post('/tests/options/reorder',
[\App\Http\Controllers\QuestionOptionController::class, 'reorder']
);
Route::delete('/question-options/{questionOption}', [\App\Http\Controllers\QuestionOptionController::class, 'destroy'])->name('question-options.destroy');
Route::get('/tests/test/preview/{test}', [\App\Http\Controllers\TestController::class, 'show'])->name('testPreview');

Route::get('/catalog', [\App\Http\Controllers\PageController::class, 'catalog'])->name('catalog');
Route::get('/catalog/test/{test}', [\App\Http\Controllers\PageController::class, 'test'])->name('testCatalog');

Route::get('/admin/analytics', [\App\Http\Controllers\PageController::class, 'analytics'])->name('analytics');
