<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:subjects']);
        Subject::create($request->all());
        return back()->with('success', 'Mata pelajaran berhasil dibuat.');
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate(['name' => 'required|unique:subjects,name,' . $subject->id]);
        $subject->update($request->all());
        return back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
