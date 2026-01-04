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
        $request->merge(['barcode_code' => $request->barcode_code ?? 'STU-' . uniqid()]); // Fallback/Auto logic if empty, but let's force auto.

        // Actually, user said: "otomatis Barcode nya langsung dibuatkan", implies usually user doesn't input it.
        // Let's generate it based on NIS or random if not present, but user might remove input field.
        // Let's modify validation to remove barcode_code requirement from input if we generate it.

        $code = 'STU-' . strtoupper(uniqid());
        $request->merge(['barcode_code' => $code]);

        $request->validate([
            'nis' => 'required|unique:students',
            'name' => 'required',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
        ]);

        Student::create([
            'nis' => $request->nis,
            'name' => $request->name,
            'class_id' => $request->class_id,
            'parent_id' => $request->parent_id,
            'barcode_code' => $code, // Force auto generated
        ]);

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
