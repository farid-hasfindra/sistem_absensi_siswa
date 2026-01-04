<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['schoolClass', 'parent'])->latest()->paginate(10);
        $classes = \App\Models\SchoolClass::all();
        $parents = \App\Models\ParentModel::all();
        return view('students.index', compact('students', 'classes', 'parents'));
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function store(Request $request)
    {
        $request->merge(['barcode_code' => $request->barcode_code ?? 'STU-' . strtoupper(uniqid())]);

        $request->validate([
            'nis' => 'required|unique:students',
            'name' => 'required',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($data);

        return back()->with('success', 'Siswa berhasil ditambahkan');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nis' => 'required|unique:students,nis,' . $student->id,
            'name' => 'required',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $student->photo)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $student->photo);
            }
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return back()->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy(Student $student)
    {
        if ($student->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $student->photo)) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $student->photo);
        }
        $student->delete();
        return back()->with('success', 'Siswa berhasil dihapus');
    }

    public function generateBarcode(Student $student)
    {
        return response()->json(['code' => $student->barcode_code]);
    }
}
