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
        $request->validate(['barcode_code' => 'required']);

        $student = \App\Models\Student::where('barcode_code', $request->barcode_code)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan!'
            ], 404);
        }

        // Determine Time Slot
        $now = now();
        $hour = $now->hour;
        $type = 'pagi';
        $status = 'hadir';

        if ($hour >= 6 && $hour < 11) {
            $type = 'pagi';
            if ($hour > 7) $status = 'telat'; // Example late time
        } elseif ($hour >= 11 && $hour < 14) {
            $type = 'solat';
        } elseif ($hour >= 14) {
            $type = 'pulang';
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Belum waktunya absen!'
            ], 400);
        }

        // Check duplicate
        $existing = \App\Models\Attendance::where('student_id', $student->id)
            ->where('date', today())
            ->where('type', $type)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Sudah melakukan absen ' . $type . '!',
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
