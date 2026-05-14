<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Test;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Test $test)
    {
        return view('users.result', ['test' => $test]);
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
