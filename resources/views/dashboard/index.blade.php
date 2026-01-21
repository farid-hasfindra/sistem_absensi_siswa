@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mb-4 fade-in-left">
            <h2 class="fw-bold text-dark">Ringkasan Dashboard</h2>
            <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="col-md-3 mb-4 fade-in-up delay-100">
            <div class="card border-0 hover-lift h-100"
                style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 1px;">Siswa</h6>
                        <h3 class="fw-bolder mb-0 mt-2">{{ $stats['total_students'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 50px; height: 50px; background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-mortarboard-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4 fade-in-up delay-200">
            <div class="card border-0 hover-lift h-100"
                style="background: linear-gradient(135deg, #2ec4b6 0%, #20a4f3 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 1px;">Hadir</h6>
                        <h3 class="fw-bolder mb-0 mt-2">{{ $stats['present_today'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 50px; height: 50px; background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-check-lg fs-4 fw-bold"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4 fade-in-up delay-300">
            <div class="card border-0 hover-lift h-100"
                style="background: linear-gradient(135deg, #FFB703 0%, #FB8500 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 1px;">Terlambat</h6>
                        <h3 class="fw-bolder mb-0 mt-2">{{ $stats['late_today'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 50px; height: 50px; background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-alarm-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4 fade-in-up delay-400">
            <div class="card border-0 hover-lift h-100"
                style="background: linear-gradient(135deg, #ef476f 0%, #d90429 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold text-xs"
                            style="font-size: 0.75rem; letter-spacing: 1px;">Absen</h6>
                        <h3 class="fw-bolder mb-0 mt-2">{{ $stats['absent_today'] }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 50px; height: 50px; background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-x-lg fs-4 fw-bold"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-2 fade-in-up delay-500">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                            alt="Empty State" class="img-fluid hover-scale" style="width: 250px; opacity: 0.9;">
                    </div>
                    <h5 class="fw-bold text-dark">Belum Ada Aktivitas</h5>
                    <p class="text-muted">Data aktivitas terbaru akan muncul di sini.</p>
                </div>
            </div>
        </div>
    </div>
@endsection