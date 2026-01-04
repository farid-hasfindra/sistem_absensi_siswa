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

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:students',
            'name' => 'required',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'barcode_code' => 'required|unique:students',
        ]);

        Student::create($request->all());

        return back()->with('success', 'Siswa berhasil ditambahkan');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nis' => 'required|unique:students,nis,' . $student->id,
            'name' => 'required',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'barcode_code' => 'required|unique:students,barcode_code,' . $student->id,
        ]);

        $student->update($request->all());

        return back()->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return back()->with('success', 'Siswa berhasil dihapus');
    }

    public function generateBarcode(Student $student)
    {
        return response()->json(['code' => $student->barcode_code]);
    }
}
