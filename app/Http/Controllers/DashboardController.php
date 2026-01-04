<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role == 'guru') {
            $class = $user->teacher ? $user->teacher->schoolClass : null;
            $students = $class ? $class->students : collect([]);
            $totalStudents = $students->count();

            $presentToday = \App\Models\Attendance::whereIn('student_id', $students->pluck('id'))
                ->where('date', today())
                ->where('status', 'hadir')
                ->count();

            return view('dashboard.teacher', compact('class', 'totalStudents', 'presentToday'));

        } elseif ($user->role == 'wali_murid') {
            $children = $user->parent ? $user->parent->students : collect([]);
            return view('dashboard.parent', compact('children'));

        } else {
            $stats = [
                'total_students' => \App\Models\Student::count(),
                'present_today' => \App\Models\Attendance::where('date', today())->where('status', 'hadir')->distinct('student_id')->count(),
                'late_today' => \App\Models\Attendance::where('date', today())->where('status', 'telat')->distinct('student_id')->count(),
                'absent_today' => \App\Models\Attendance::where('date', today())->where('status', 'alpha')->distinct('student_id')->count(),
            ];
            return view('dashboard.index', compact('stats'));
        }
    }
}
