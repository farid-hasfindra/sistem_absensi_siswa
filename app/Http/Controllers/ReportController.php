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

    public function export()
    {
        $user = auth()->user();
        $filename = "absensi-" . date('Y-m-d') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $query = \App\Models\Attendance::with(['student.schoolClass'])->latest();

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

        echo "<table border='1'>";
        echo "<tr><th>Date</th><th>Student</th><th>NIS</th><th>Class</th><th>Type</th><th>Status</th><th>Time</th></tr>";
        foreach ($attendances as $row) {
            echo "<tr>";
            echo "<td>{$row->date}</td>";
            echo "<td>{$row->student->name}</td>";
            echo "<td>{$row->student->nis}</td>";
            echo "<td>" . ($row->student->schoolClass->name ?? '-') . "</td>";
            echo "<td>{$row->type}</td>";
            echo "<td>{$row->status}</td>";
            echo "<td>{$row->scanned_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit;
    }
}
