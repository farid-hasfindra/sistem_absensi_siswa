@extends('layouts.app')

@section('content')
    <div class="fade-in-up">
        <!-- Welcome Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4 rounded-4 text-white shadow-lg bg-primary position-relative overflow-hidden"
                    style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-person-workspace" style="font-size: 8rem;"></i>
                    </div>
                    <div class="position-relative z-1">
                        <h2 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0 opacity-75">Wali Kelas: <strong>{{ $class->name ?? 'Belum ditentukan' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Students -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Total Siswa</div>
                            <h3 class="fw-bold mb-0">{{ $totalStudents }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hadir -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Hadir Hari Ini</div>
                            <h3 class="fw-bold mb-0">{{ $attendanceStats['hadir'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sakit/Izin -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 text-warning d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-envelope-paper-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Izin / Sakit</div>
                            <h3 class="fw-bold mb-0">{{ $attendanceStats['sakit'] + $attendanceStats['izin'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alpha -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3 text-danger d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-x-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Tanpa Keterangan</div>
                            <h3 class="fw-bold mb-0">{{ $attendanceStats['alpha'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Analytics Chart -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-0">Statistik Kehadiran Hari Ini</h5>
                    </div>
                    <div class="card-body p-4">
                        <div id="attendanceChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div
                        class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Aktivitas Terbaru</h5>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-light rounded-pill px-3">Lihat
                            Semua</a>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            @forelse($recentActivities as $activity)
                                <div class="d-flex align-items-center p-2 rounded-3 hover-bg-light transition-all">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                        style="width: 40px; height: 40px;">
                                        {{ substr($activity->student->name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark small">{{ $activity->student->name }}</div>
                                        <small class="text-muted">{{ ucfirst($activity->type) }} •
                                            {{ \Carbon\Carbon::parse($activity->scanned_at)->format('H:i') }}</small>
                                    </div>
                                    <div>
                                        @if($activity->status == 'hadir')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Hadir</span>
                                        @elseif($activity->status == 'telat')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">Telat</span>
                                        @else
                                            <span
                                                class="badge bg-warning-subtle text-warning rounded-pill">{{ ucfirst($activity->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-clock-history fs-1 mb-2 d-block opacity-25"></i>
                                    Belum ada aktivitas absensi hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                series: [{{ $attendanceStats['hadir'] }}, {{ $attendanceStats['sakit'] }}, {{ $attendanceStats['izin'] }}, {{ $attendanceStats['alpha'] }}],
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'Nunito, sans-serif'
                },
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                colors: ['#198754', '#ffc107', '#0dcaf0', '#dc3545'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => { // Sum all data
                                            return a + b
                                        }, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
            chart.render();
        });
    </script>
    <style>
        .hover-bg-light:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .transition-all {
            transition: all 0.2s ease;
        }
    </style>
@endsection