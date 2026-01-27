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
            'password' => 'required|min:6',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // Maxwell: Create User First
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->nis . '@student.ac.id', // Auto-email
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'siswa', // New Role
                'username' => $request->nis, // Fallback/Legacy
            ]);

            $data = $request->except(['photo', 'password']);
            $data['user_id'] = $user->id;

            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('students', 'public');
            }

            Student::create($data);
        });

        return back()->with('success', 'Siswa dan Akun berhasil ditambahkan');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nis' => 'required|unique:students,nis,' . $student->id,
            'name' => 'required',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:6',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $student) {
            $data = $request->except(['photo', 'password']);

            // Update Photo
            if ($request->hasFile('photo')) {
                if ($student->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $student->photo)) {
                    \Illuminate\Support\Facades\Storage::delete('public/' . $student->photo);
                }
                $data['photo'] = $request->file('photo')->store('students', 'public');
            }

            $student->update($data);

            // Update User Account
            if ($student->user) {
                $userUpdates = [
                    'name' => $request->name,
                    'email' => $request->nis . '@student.ac.id',
                    'username' => $request->nis,
                ];

                if ($request->filled('password')) {
                    $userUpdates['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
                }

                $student->user->update($userUpdates);
            }
        });

        return back()->with('success', 'Data siswa dan akun berhasil diupdate');
    }

    public function destroy(Student $student)
    {
        if ($student->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $student->photo)) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $student->photo);
        }

        // Delete associated user
        if ($student->user) {
            $student->user->delete(); // This handles student deletion via cascade if set, otherwise we delete student first ? 
            // DB cascade delete is set on students table (user_id), but verify logic.
            // Actually, we delete student here. If we delete user, student might be deleted by cascade.
            // But we want to ensure both are gone.
            // Safe bet: Delete User (cascade deletes student) OR Delete Student then User.
            // Migration says: students.user_id onDelete cascade? NO. 
            // Migration: Schema::table('students', ... ->onDelete('cascade')); 
            // Wait, constraints usually mean: if User is deleted, Student is deleted.
            // So executing $student->user->delete() is the right way to remove both.
            // But if I call $student->delete() first (which this method does later), I might lose access to $student->user.
            // Let's delete user first.
        } else {
            $student->delete();
        }

        return back()->with('success', 'Siswa dan Akun berhasil dihapus');
    }

    public function generateBarcode(Student $student)
    {
        return response()->json(['code' => $student->barcode_code]);
    }
}
