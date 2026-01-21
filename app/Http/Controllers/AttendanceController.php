<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'barcode_code' => 'required',
            'session_type' => 'required|in:pagi,solat,pulang' // Validate manual session
        ]);

        $student = \App\Models\Student::where('barcode_code', $request->barcode_code)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan!'
            ], 404);
        }

        // Use Manual Selection
        $type = $request->session_type;
        $now = now();
        $hour = $now->hour;
        $status = 'hadir';

        // Additional Logic for Morning Lateness - DISABLED by request
        // If Absen Pagi is selected and it is past 7 AM, mark as late.
        // if ($type == 'pagi' && $hour > 7) {
        //    $status = 'telat';
        // }

        // Check duplicate for this specific session type
        $existing = \App\Models\Attendance::where('student_id', $student->id)
            ->where('date', today())
            ->where('type', $type)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Sudah melakukan absen ' . ucfirst($type) . '!',
                'student' => $student
            ]);
        }

        \App\Models\Attendance::create([
            'student_id' => $student->id,
            'type' => $type,
            'status' => $status,
            'scanned_at' => $now,
            'date' => today(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen ' . ucfirst($type) . ' Berhasil!',
            'student' => $student,
            'type' => $type,
            'time' => $now->format('H:i:s')
        ]);
    }
    public function create()
    {
        $user = auth()->user();
        if ($user->role !== 'guru' || !$user->teacher) {
            abort(403, 'Unauthorized action.');
        }

        $students = $user->teacher->schoolClass ? $user->teacher->schoolClass->students : collect([]);
        return view('attendance.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'type' => 'required|in:pagi,solat,pulang',
            'status' => 'required|in:hadir,telat,izin,sakit,alpha',
        ]);

        // Check duplicate
        $existing = \App\Models\Attendance::where('student_id', $request->student_id)
            ->where('date', $request->date)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return back()->with('error', 'Data absensi untuk siswa dan sesi ini sudah ada!');
        }

        $scannedAt = $request->date . ' ' . ($request->time ?? now()->format('H:i:s'));

        \App\Models\Attendance::create([
            'student_id' => $request->student_id,
            'type' => $request->type,
            'status' => $request->status,
            'date' => $request->date,
            'scanned_at' => $scannedAt
        ]);

        return redirect()->route('reports.index')->with('success', 'Data absensi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Attendance $attendance)
    {
        return view('attendance.edit', compact('attendance'));
    }

    public function update(Request $request, \App\Models\Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:hadir,telat,izin,sakit,alpha',
            'type' => 'required|in:pagi,solat,pulang'
        ]);

        $attendance->update([
            'status' => $request->status,
            'type' => $request->type
        ]);

        return redirect()->route('reports.index')->with('success', 'Data absensi berhasil diperbarui!');
    }

    public function destroy(\App\Models\Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('reports.index')->with('success', 'Data absensi berhasil dihapus!');
    }
}

