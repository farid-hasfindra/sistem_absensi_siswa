@extends('layouts.app')

@section('content')
    <div class="row fade-in-up">
        <div class="col-12 mb-4">
            <h2 class="fw-bold">Ringkasan Dashboard</h2>
            <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}</p>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-white mx-2 mb-2 h-100" style="border-bottom: 4px solid var(--primary-color);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Siswa</h6>
                        <h3 class="fw-bolder mb-0 text-dark mt-2">{{ $stats['total_students'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                        style="width: 50px; height: 50px; background-color: var(--primary-color);">
                        <i class="bi bi-mortarboard-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-white mx-2 mb-2 h-100" style="border-bottom: 4px solid var(--success-color);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Hadir</h6>
                        <h3 class="fw-bolder mb-0 text-dark mt-2">{{ $stats['present_today'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                        style="width: 50px; height: 50px; background-color: var(--success-color);">
                        <i class="bi bi-check-lg fs-4 fw-bold"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-white mx-2 mb-2 h-100" style="border-bottom: 4px solid var(--secondary-color);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Terlambat</h6>
                        <h3 class="fw-bolder mb-0 text-dark mt-2">{{ $stats['late_today'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                        style="width: 50px; height: 50px; background-color: var(--secondary-color);">
                        <i class="bi bi-alarm-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-white mx-2 mb-2 h-100" style="border-bottom: 4px solid var(--danger-color);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Absen</h6>
                        <h3 class="fw-bolder mb-0 text-dark mt-2">{{ $stats['absent_today'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                        style="width: 50px; height: 50px; background-color: var(--danger-color);">
                        <i class="bi bi-x-lg fs-4 fw-bold"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row fade-in-up" style="animation-delay: 0.2s;">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Aktivitas Terbaru</h5>
                    <div class="alert alert-light text-center">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                            alt="Empty" style="width: 200px; opacity: 0.8;">
                        <p class="text-muted mt-2">Belum ada data aktivitas terbaru terkait grafik.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white mx-2 mb-2 h-100 border-0 shadow-soft">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-dark">Aksi Cepat</h5>
                    <a href="{{ route('attendance.index') }}"
                        class="btn btn-primary w-100 mb-2 py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        <i class="bi bi-upc-scan fs-5"></i> Scan Absensi
                    </a>
                    <a href="{{ route('students.index') }}"
                        class="btn btn-outline-secondary w-100 py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 border-2">
                        <i class="bi bi-people fs-5"></i> Kelola Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection