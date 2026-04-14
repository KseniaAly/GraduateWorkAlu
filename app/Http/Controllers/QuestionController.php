<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Test $test)
    {
        $request->validate([
            'question_category' => 'required',
            'description' => 'required',
            'points_max' => 'required',
            'title' => 'required',
        ]);
        $question = new Question();
        $question->title = $request->title;
        $question->description = $request->description;
        $question->question_category_id = $request->question_category;
        $question->points_max = $request->points_max;
        $question->save();

        $lastPosition = TestQuestion::where('test_id', $test->id)->max('position');
        $test_question = new TestQuestion();
        $test_question->test_id = $test->id;
        $test_question->question_id = $question->id;
        $test_question->position = $lastPosition ? $lastPosition + 1 : 1;
        $test_question->save();

        return redirect()->back();
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            TestQuestion::where('id', $item['id'])
                ->update([
                    'position' => $item['position']
                ]);
        }
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function attach(Request $request, Test $test)
    {
        $request->validate([
            'questions' => 'required|array|min:1',
        ]);
        $lastPosition = TestQuestion::where('test_id', $test->id)->max('position');
        $position = $lastPosition ? $lastPosition+1 : 1;
        foreach ($request->questions as $questionId) {
            $exists = TestQuestion::where('test_id', $test->id)->where('question_id', $questionId)
                ->exists();
            if ($exists){
                continue;
            }
            TestQuestion::create([
                'test_id' => $test->id,
                'question_id' => $questionId,
                'position' => $position++
            ]);
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_category' => 'required',
            'description' => 'required',
            'points_max' => 'required',
            'title' => 'required',
        ]);
        $question->title = $request->title;
        $question->description = $request->description;
        $question->question_category_id = $request->question_category;
        $question->points_max = $request->points_max;
        $question->update();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestQuestion $testQuestion)
    {
        $testId = $testQuestion->test_id;
        $deletedPosition = $testQuestion->position;
        $testQuestion->delete();
        TestQuestion::where('test_id', $testId)
            ->where('position', '>', $deletedPosition)
            ->decrement('position');
        return redirect()->back();
    }
}
