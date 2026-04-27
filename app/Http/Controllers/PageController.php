<?php

namespace App\Http\Controllers;

use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function catalog(Request $request)
    {
        $vacancies = Vacancy::all();
        $tests = Test::query()->where('status', 'active')->orderByDesc('created_at')->get();
        $counts = [];
        $count_tests = [];
        foreach ($tests as $test) {
            $counts[$test->id] = TestQuestion::where('test_id', $test->id)->count();
            $count_tests[$test->vacancy->id] = Test::where('status', 'active')
                ->where('vacancy_id', $test->vacancy->id)->count();
        }
        $query = Test::query()->where('status', 'active');
        if ($request->filled('vacancies')) {
            $ids = explode(',', $request->vacancies);
            $query->whereIn('vacancy_id', $ids);
        }
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $tests = $query->orderByDesc('created_at')->get();
        return view('users.catalog', ['vacancies' => $vacancies, 'tests' => $tests,
            'counts' => $counts, 'count_tests' => $count_tests]);
    }

    public function test(Test $test)
    {
        if(!session()->has('test_started_at_'.$test->id)){
            session()->put('test_started_at_'.$test->id, now()->timestamp);
            session()->put('test_time_limit_'.$test->id, $test->limit_time*60);
        }
        $started = session('test_started_at_'.$test->id);
        $limit = session('test_time_limit_'.$test->id);
        $remainingTime = $limit-(time()-$started);
        $remainingTime = max(0, (int)$remainingTime);
        if ($remainingTime <= 0) {
            session()->forget('test_answers');
            session()->forget('test_started_at');
            return redirect()->route('catalog')->with('error', 'Время теста истекло');
        }
        $test_questions = TestQuestion::query()->where('test_id', $test->id)->orderBy('position')->paginate(2);
        $question_options = QuestionOption::all();
        return view('users.test', ['test' => $test],
            ['test_questions' => $test_questions, 'question_options' => $question_options,
                'remainingTime' => $remainingTime]);
    }
    public function analytics()
    {
        return view('admin.analytics');
    }
    public function profile()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    public function verify($email)
    {
        return view('verify', ['email' => $email]);
    }
}
