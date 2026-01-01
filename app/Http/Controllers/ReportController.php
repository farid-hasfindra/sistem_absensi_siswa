<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $attendances = \App\Models\Attendance::with('student')
            ->whereDate('date', $date)
            ->latest()
            ->get();

        return view('reports.index', compact('attendances', 'date'));
    }

    public function export()
    {
        // Simple HTML to Excel export
        $filename = "absensi-" . date('Y-m-d') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $attendances = \App\Models\Attendance::with('student')->latest()->get();

        echo "<table border='1'>";
        echo "<tr><th>Date</th><th>Student</th><th>NIS</th><th>Class</th><th>Type</th><th>Status</th><th>Time</th></tr>";
        foreach ($attendances as $row) {
            echo "<tr>";
            echo "<td>{$row->date}</td>";
            echo "<td>{$row->student->name}</td>";
            echo "<td>{$row->student->nis}</td>";
            echo "<td>{$row->student->class}</td>";
            echo "<td>{$row->type}</td>";
            echo "<td>{$row->status}</td>";
            echo "<td>{$row->scanned_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit;
    }
}
