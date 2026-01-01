<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:students',
            'class' => 'required',
            'barcode_code' => 'required|unique:students',
        ]);

        Student::create($validated);

        return back()->with('success', 'Siswa berhasil ditambahkan');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:students,nis,' . $student->id,
            'class' => 'required',
            'barcode_code' => 'required|unique:students,barcode_code,' . $student->id, // Fixed: added closing quote
        ]);

        $student->update($validated);

        return back()->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return back()->with('success', 'Siswa berhasil dihapus');
    }

    public function generateBarcode(Student $student)
    {
        // Logic to return barcode view or download
        return response()->json(['code' => $student->barcode_code]);
    }
}
