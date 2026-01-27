<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'schoolClass', 'schoolClass.students', 'schedules', 'schedules.subject'])->get();
        // Note: Relation for schedules might need adjustment based on ERD (schedules usually linked to teacher_id or user_id?)
        // Let's check Schedule Model. Schedule belongsTo Teacher. Teacher belongsTo User.
        // So schedules are on Teacher model usually.
        // Let's re-verify Schedule model relationship in next step if needed, but assuming 'teacher' relationship on Schedule.
        // Wait, Teacher model has no 'schedules' relation defined in my previous view. I should add it.

        return view('teachers.index', compact('teachers'));
    }
}
