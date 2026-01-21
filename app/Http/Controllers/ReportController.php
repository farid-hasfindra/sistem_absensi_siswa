<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $date = $request->input('date', date('Y-m-d'));

        $query = \App\Models\Attendance::with(['student.schoolClass']) // Eager load class
            ->whereDate('date', $date);

        if ($user->role == 'wali_murid') {
            $studentIds = $user->parent ? $user->parent->students->pluck('id') : [];
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->role == 'guru') {
            if ($user->teacher && $user->teacher->class_id) {
                $classId = $user->teacher->class_id;
                $query->whereHas('student', function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                });
            }
        }

        $attendances = $query->latest()->get();

        return view('reports.index', compact('attendances', 'date'));
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $date = $request->input('date', date('Y-m-d'));
        $filename = "absensi-" . $date . ".csv";

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $query = \App\Models\Attendance::with(['student.schoolClass'])
            ->whereDate('date', $date)
            ->latest();

        if ($user->role == 'wali_murid') {
            $studentIds = $user->parent ? $user->parent->students->pluck('id') : [];
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->role == 'guru') {
            if ($user->teacher && $user->teacher->class_id) {
                $classId = $user->teacher->class_id;
                $query->whereHas('student', function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                });
            }
        }

        $attendances = $query->get();

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Date', 'Student', 'NIS', 'Class', 'Type', 'Status', 'Time']);

        foreach ($attendances as $row) {
            fputcsv($handle, [
                $row->date,
                $row->student->name,
                $row->student->nis,
                $row->student->schoolClass->name ?? '-',
                $row->type,
                $row->status,
                \Carbon\Carbon::parse($row->scanned_at)->format('H:i:s')
            ]);
        }

        fclose($handle);
        exit;
    }
}
