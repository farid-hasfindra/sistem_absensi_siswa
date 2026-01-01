<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => \App\Models\Student::count(),
            'present_today' => \App\Models\Attendance::whereDate('created_at', today())->where('status', 'hadir')->distinct('student_id')->count(),
            'late_today' => \App\Models\Attendance::whereDate('created_at', today())->where('status', 'telat')->count(),
            'absent_today' => \App\Models\Attendance::whereDate('created_at', today())->where('status', 'alpha')->count(),
        ];
        return view('dashboard.index', compact('stats'));
    }
}
