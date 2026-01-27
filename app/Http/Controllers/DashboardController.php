<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role == 'guru' || $user->role == 'guru_mapel') {
            $class = ($user->role == 'guru' && $user->teacher) ? $user->teacher->schoolClass : null;
            // If not homeroom teacher (guru mapel or guru without class), show empty or all?
            // Since they scan via 'Attendance' menu, dashboard might just show stats for today if possible, or limited view.
            // For now, let's keep it safe. If no class, empty students.
            if ($user->role == 'guru_mapel') {
                $students = \App\Models\Student::all();
            } else {
                $students = $class ? $class->students : collect([]);
            }
            $totalStudents = $students->count();

            $attendanceStats = [
                'hadir' => \App\Models\Attendance::whereIn('student_id', $students->pluck('id'))->where('date', today())->where('status', 'hadir')->count(),
                'sakit' => \App\Models\Attendance::whereIn('student_id', $students->pluck('id'))->where('date', today())->where('status', 'sakit')->count(),
                'izin' => \App\Models\Attendance::whereIn('student_id', $students->pluck('id'))->where('date', today())->where('status', 'izin')->count(),
                'alpha' => \App\Models\Attendance::whereIn('student_id', $students->pluck('id'))->where('date', today())->where('status', 'alpha')->count(),
                'telat' => \App\Models\Attendance::whereIn('student_id', $students->pluck('id'))->where('date', today())->where('status', 'telat')->count(),
            ];

            $recentActivities = \App\Models\Attendance::with('student')
                ->whereIn('student_id', $students->pluck('id'))
                ->where('date', today())
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard.teacher', compact('class', 'totalStudents', 'attendanceStats', 'recentActivities'));

        } elseif ($user->role == 'wali_murid') {
            $children = $user->parent ? $user->parent->students : collect([]);

            $period = request('period', 'today');

            $childrenData = [];
            foreach ($children as $child) {
                // Filter Logic
                $query = $child->attendances();

                if ($period == '1_month') {
                    $query->where('date', '>=', now()->subMonth());
                } elseif ($period == '3_months') {
                    $query->where('date', '>=', now()->subMonths(3));
                } elseif ($period == '6_months') {
                    $query->where('date', '>=', now()->subMonths(6));
                } else {
                    // Default: Today
                    $query->whereDate('date', today());
                }

                // Clone query for counting to avoid issues if we needed to reuse it
                // Actually we can just call new queries or clone it.
                // Simple approach:

                $stats = [
                    'hadir' => (clone $query)->where('status', 'hadir')->count(),
                    'sakit' => (clone $query)->where('status', 'sakit')->count(),
                    'izin' => (clone $query)->where('status', 'izin')->count(),
                    'alpha' => (clone $query)->where('status', 'alpha')->count(),
                ];

                // Also apply filter to recent timeline
                $recent = (clone $query)->latest()->take(5)->get();
                $todayAttendance = $child->attendances()->whereDate('date', today())->first();

                $childrenData[] = (object) [
                    'data' => $child,
                    'stats' => $stats,
                    'recent' => $recent,
                    'today_status' => $todayAttendance ? $todayAttendance->status : 'belum_absen'
                ];
            }

            return view('dashboard.parent', compact('childrenData'));

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
    public function getRecentActivity(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'wali_murid') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $studentId = $request->input('student_id');
        $child = $user->parent->students()->where('id', $studentId)->first();

        if (!$child) {
            return response()->json(['error' => 'Student not found or not authorized'], 404);
        }

        $period = $request->input('period', 'today');
        $query = $child->attendances();

        if ($period == '1_month') {
            $query->where('date', '>=', now()->subMonth());
        } elseif ($period == '3_months') {
            $query->where('date', '>=', now()->subMonths(3));
        } elseif ($period == '6_months') {
            $query->where('date', '>=', now()->subMonths(6));
        } else {
            // Default: Today
            $query->whereDate('date', today());
        }

        $recent = $query->latest()->take(5)->get();

        return view('dashboard.partials.timeline', compact('recent'))->render();
    }
}
