<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::query()->where('user_id', Auth::id())->orderByDesc('created_at')->paginate(3);
        $vacancies = Vacancy::all();
        return view('admin.tests.tests', ['tests' => $tests], ['vacancies' => $vacancies]);
    }

    public function filter(Request $request)
    {
        $query = Test::query()->orderByDesc('created_at');
        if ($request->status && $request->status!=='all'){
            $query = $query->where('status', $request->status);
        }
        if ($request->vacancies && $request->vacancies!=='all'){
            $query = $query->where('vacancy_id', $request->vacancies);
        }
        $tests = $query->paginate(3);
        $vacancies = Vacancy::all();
        return view('admin.tests.tests', ['tests' => $tests], ['vacancies' => $vacancies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vacancies = Vacancy::all();
        return view('admin.tests.constructor', ['vacancies' => $vacancies]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'vacancy_id' => 'required',
            'limit_time' => 'required',
            'passing_score' => 'required',
        ]);
        $test = new Test();
        $test->title = $request->title;
        $test->description = $request->description;
        $test->passing_score = $request->passing_score;
        $test->vacancy_id = $request->vacancy_id;
        $test->limit_time = $request->limit_time;
        $test->user_id = Auth::user()->id;
        $test->status = 'redact';
        $test->save();

        return redirect()->route('tests');
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        $test_questions = TestQuestion::query()->where('test_id', $test->id)->orderBy('position')->paginate(2);
        $question_options = QuestionOption::all();
        return view('admin.tests.preview', ['test' => $test], ['test_questions' => $test_questions,
            'question_options' => $question_options]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        $test_questions = TestQuestion::query()->where('test_id', $test->id)
            ->orderBy('position')->get();
        $vacancies = Vacancy::all();
        $question_categories = QuestionCategory::all();
        $questions = Question::whereNotIn('id', TestQuestion::where('test_id', $test->id)->pluck('question_id'))->get();
        return view('admin.tests.constructor_questions', ['test' => $test], ['vacancies' => $vacancies, 'test_questions' => $test_questions,
            'question_categories' => $question_categories, 'questions' => $questions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'vacancy_id' => 'required',
            'limit_time' => 'required',
            'passing_score' => 'required',
        ]);
        $test->title = $request->title;
        $test->description = $request->description;
        $test->passing_score = $request->passing_score;
        $test->vacancy_id = $request->vacancy_id;
        $test->limit_time = $request->limit_time;
        $test->update();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
//        $test->questions()->delete();
        $test_questions = TestQuestion::query()->where('test_id', $test->id)->get();
        foreach ($test_questions as $test_question) {
            $test_question->delete();
        }
        $test->delete();
        return redirect()->route('tests');
    }

    public function updateStatusActive(Test $test)
    {
        $test->status = 'active';
        $test->update();
        return redirect()->back();
    }
    public function updateStatusRedact(Test $test)
    {
        $test->status = 'redact';
        $test->update();
        return redirect()->back();
    }
}
