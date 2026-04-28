<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use App\Jobs\AnalyzeAnswerJob;
use App\Services\AIService;

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
        $questionId = $request->question_id;

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'mimes:txt,docx|max:10240'
            ]);
            $path = $request->file('file')->store('test_files', 'public');
            $answers[$questionId] = [
                'type' => 'file',
                'value' => $path,
            ];
        } else {
            $answers[$questionId] = [
                'type'  => $request->type,
                'value' => $request->value,
            ];
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
        UserAnswer::where('user_id', auth()->id())->where('test_id', $test->id)->delete();

        foreach ($answers as $questionId => $data) {
            $type = $data['type'] ?? null;
            $value = $data['value'] ?? null;
            $question = Question::find($questionId);
            if ($type === 'radio' || $type === 'checkbox') {
                if (!is_array($value)) {
                    $value = [$value];
                }
                $correctOptions = QuestionOption::where('question_id', $questionId)->where('is_correct', true)->pluck('id')->map(fn($id) => (string)$id)->toArray();
                $value = array_map('strval', $value);
                sort($correctOptions);
                sort($value);
                $is_correct = $correctOptions === $value;
                $points = count(array_intersect($correctOptions, $value));
                UserAnswer::create([
                    'user_id' => auth()->id(),
                    'question_id' => $questionId,
                    'test_id' => $test->id,
                    'answers' => json_encode($value),
                    'is_correct' => $is_correct,
                    'points' => $points,
                ]);
                continue;
            } else {
                $text = $value;
                $text = $type === 'file' ? ($this->readFile($value) ?? '') : $value;
                $userAnswer = UserAnswer::create([
                    'user_id' => auth()->id(),
                    'question_id' => $questionId,
                    'test_id' => $test->id,
                    'answer' => $text,
                    'is_correct' => null,
                    'points' => 0,
                ]);
                AnalyzeAnswerJob::dispatch($userAnswer, $type, $data['extension'] ?? null);
            }
        }
        session()->forget('test_answers');
        session()->forget('test_started_at_'.$test->id);
        session()->forget('test_time_limit_'.$test->id);
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
