<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class UserAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $answers = session()->get('test_answers', []);
//        UserAnswer::where('user_id', auth()->id())->where('test_id', $test->id)->delete();
        $questionId = $request->question_id;
        $type = $request->type;
        if ($request->hasFile('file')) {
            $path = $request->file('file')
                ->store('test_files', 'public');
            $answers[$questionId] = $path;
        } else {
            $answers[$questionId] = $request->value;
        }
        session()->put('test_answers', $answers);

        return response()->json(['status' => 'saved']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Test $test)
    {
        $answers = session('test_answers', []);
//        UserAnswer::where('user_id', auth()->id())->where('test_id', $test->id)->delete();
        foreach ($answers as $questionId => $answer) {
            $question = Question::with('options')->find($questionId);
            $correctOptions = $question->options->where('is_correct', true)->pluck('id')->toArray();
            if (is_array($answer)) {
                UserAnswer::create([
                    'user_id' => auth()->id(),
                    'question_id' => $questionId,
                    'test_id' => $test->id,
                    'answers' => json_encode($answer),
                    'points' => 0,
                ]);
            } else {
                UserAnswer::create([
                    'user_id' => auth()->id(),
                    'question_id' => $questionId,
                    'answer' => $answer,
                    "test_id" => $test->id,
                    'points' => 0,
                ]);
            }
        }
        session()->forget('test_answers');
        return redirect()->route('profile');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAnswer $userAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAnswer $userAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAnswer $userAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAnswer $userAnswer)
    {
        //
    }
}
