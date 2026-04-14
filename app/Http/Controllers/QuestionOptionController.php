<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;

class QuestionOptionController extends Controller
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
    public function store(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        if ($request->options){
            foreach ($request->options as $optionId => $data){
                QuestionOption::query()->where('id', $optionId)->update([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'is_correct' => isset($data['is_correct']),
                ]);
            }
        }
        if ($request->new_options){
            $position = $question->options()->count()+1;
            foreach ($request->new_options as $data){
                QuestionOption::create([
                    'question_id' => $id,
                    'title' => $data['title'] ?? '',
                    'description' => $data['description'] ?? '',
                    'position' => $position++,
                    'is_correct' => isset($data['is_correct']),
                ]);
            }
        }
        return redirect()->back();
    }

    public function reorder(Request $request)
    {
        foreach ($request->all() as $item){
            QuestionOption::where('id', $item['id'])->update([
                'position' => $item['position'],
            ]);
        }
        return response()->json([
            'status' => 'success'
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(QuestionOption $questionOption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionOption $questionOption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionOption $questionOption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionOption $questionOption)
    {
        $questionOption->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
