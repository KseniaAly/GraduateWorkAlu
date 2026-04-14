<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome(){
        return view('welcome');
    }

    public function registration(){
        return view('registration');
    }

    public function authorization(){
        return view('authorization');
    }
    public function catalog()
    {
        $vacancies = Vacancy::all();
        $tests = Test::query()->where('status', 'active')->orderByDesc('created_at')->get();
        $counts = [];
        $count_tests = [];
        foreach ($tests as $test) {
            $counts[$test->id] = count(TestQuestion::where('test_id', $test->id)->get());
            $count_tests[$test->vacancy->id] = count(Test::where('status', 'active')
                ->where('vacancy_id', $test->vacancy->id)->get());
        }
        return view('users.catalog', ['vacancies' => $vacancies, 'tests' => $tests,
            'counts' => $counts, 'count_tests' => $count_tests]);
    }

    public function test(Test $test)
    {
        return view('users.test', ['test' => $test]);
    }
    public function analytics()
    {
        return view('admin.analytics');
    }
}
