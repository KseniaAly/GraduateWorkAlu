<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vacancies = Vacancy::query()->orderByDesc('created_at')->paginate(3);
        return view('admin.vacancies', ['vacancies' => $vacancies]);
    }

    public function filter(Request $request){
        $query = Vacancy::query()->orderByDesc('created_at');
        if ($request->status && $request->status!=='all'){
            $query = $query->where('status', $request->status);
        }
        if ($request->type && $request->type!=='all'){
            $query = $query->where('type', $request->type);
        }
        $vacancies = $query->paginate(3);

        return view('admin.vacancies', ['vacancies' => $vacancies]);
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
    public function store(Request $request)
    {
        $request->validate([
                'title' => 'required|string|max:255',
                'salary' => 'required|numeric',
                'description' => 'required|string',
                'employment_type' => 'required',
        ]);
        $vacancy = new Vacancy();
        $vacancy->title = $request->title;
        $vacancy->description = $request->description;
        $vacancy->type = $request->employment_type;
        $vacancy->salary = $request->salary;
        $vacancy->save();

        return redirect()->route('vacancies');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacancy $vacancy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacancy $vacancy)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'salary' => 'required',
            'employment_type' => 'required',
        ]);
        $vacancy->title = $request->title;
        $vacancy->description = $request->description;
        $vacancy->salary = $request->salary;
        $vacancy->type = $request->employment_type;
        $vacancy->update();

        return redirect()->route('vacancies');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return redirect()->route('vacancies');
    }
}
