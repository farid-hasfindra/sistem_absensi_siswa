<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SchoolClass;
use App\Models\Teacher;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with('teacher')->get();
        $teachers = Teacher::all();
        return view('classes.index', compact('classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'level' => 'nullable',
            'teacher_id' => 'nullable|exists:teachers,id'
        ]);

        SchoolClass::create($request->all());

        return back()->with('success', 'Kelas berhasil dibuat.');
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $schoolClass->update($request->all());
        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
