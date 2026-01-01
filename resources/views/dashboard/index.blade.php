@extends('layouts.app')

@section('content')
    <div class="row fade-in-up">
        <div class="col-12 mb-4">
            <h2 class="fw-bold">Dashboard Overview</h2>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}</p>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background: var(--primary-gradient)">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Students</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_students'] }}</h3>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-white"
                style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Present Today</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['present_today'] }}</h3>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-white"
                style="background: linear-gradient(135deg, #fce38a 0%, #f38181 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Late Today</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['late_today'] }}</h3>
                        </div>
                        <i class="bi bi-clock-history fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-white"
                style="background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Absent/Alpha</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['absent_today'] }}</h3>
                        </div>
                        <i class="bi bi-x-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row fade-in-up" style="animation-delay: 0.2s;">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Recent Activity</h5>
                    <div class="alert alert-light text-center">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                            alt="Empty" style="width: 200px; opacity: 0.8;">
                        <p class="text-muted mt-2">No recent activity data available regarding charts yet.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white"
                style="background: var(--secondary-gradient)">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">Quick Actions</h5>
                    <a href="{{ route('attendance.index') }}" class="btn btn-light w-100 mb-2 fw-bold text-primary">Scanning
                        Page</a>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-light w-100 fw-bold">Manage Students</a>
                </div>
            </div>
        </div>
    </div>
@endsection