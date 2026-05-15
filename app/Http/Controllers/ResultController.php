<?php

namespace App\Http\Controllers;

use App\Models\QuestionOption;
use App\Models\Result;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Test $test)
    {
        $test_questions = TestQuestion::query()->where('test_id', $test->id)->orderBy('position')->paginate(2);
        $questions_count = TestQuestion::query()->where('test_id', $test->id)->count();
        $question_options = QuestionOption::all();
        $user_answers = UserAnswer::query()->where('user_answers.test_id', $test->id)
            ->where('user_answers.user_id', auth()->id())->get();
        $correct_count = $user_answers->where('is_correct', true)->count();
        $percentage = $questions_count > 0 ? round(($correct_count / $questions_count) * 100) : 0;
        return view('users.view_test', ['test' => $test],
        ['question_options' => $question_options, 'test_questions' => $test_questions, 'questions_count' => $questions_count,
            'user_answers' => $user_answers, 'correct_count' => $correct_count, 'percentage' => $percentage]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Test $test)
    {
        Result::query()->where('test_id', $test->id)->where('results.user_id', auth()->id())->delete();
        $result = new Result();
        $result->user_id = auth()->id();
        $result->test_id = $test->id;
        $result->total_points = $request->total_points;
        if ((int)$request->percent >= $test->passing_score){
            $result->passed = true;
        } else {
            $result->passed = false;
        }
        $result->completed_time = $request->completed_time;
        $result->save();
        return redirect()->route('profile');
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
}
