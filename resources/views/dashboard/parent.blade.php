@extends('layouts.app')

@section('content')
    <div class="container-fluidpf-4">
        <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5 fade-in-up">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Wali Murid</h3>
            <p class="text-muted mb-0">Pantau aktivitas akademik putra/putri Anda.</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <form action="{{ route('dashboard') }}" method="GET" class="d-none d-md-block">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white">
                    <span class="input-group-text bg-white border-0 ps-3 text-primary"><i class="bi bi-funnel"></i></span>
                    <select name="period" class="form-select border-0 bg-white shadow-none ps-2 pe-5 py-2 fw-bold text-dark" style="cursor: pointer; min-width: 150px;" onchange="this.form.submit()">
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="1_month" {{ request('period') == '1_month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                        <option value="3_months" {{ request('period') == '3_months' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="6_months" {{ request('period') == '6_months' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    </select>
                </div>
            </form>
            <div class="bg-white px-4 py-2 rounded-pill shadow-sm d-none d-md-block">
                <span class="text-primary fw-bold"><i class="bi bi-calendar-check me-2"></i> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

        @if(count($childrenData) > 0)
            @foreach($childrenData as $index => $childItem)
                <!-- Child Section -->
                <div class="row mb-5 fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                    <!-- Profile & Daily Status -->
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-soft overflow-hidden rounded-4">
                            <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                                <div class="d-flex align-items-center mb-3 mb-md-0">
                                    <div class="position-relative me-4">
                                        @if($childItem->data->photo)
                                            <img src="{{ Storage::url($childItem->data->photo) }}" class="rounded-circle shadow-sm object-fit-cover" 
                                                width="80" height="80" alt="{{ $childItem->data->name }}">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white"
                                                style="width: 80px; height: 80px; font-size: 2.2rem; font-weight: bold; background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
                                                {{ substr($childItem->data->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="fw-bold text-dark mb-1">{{ $childItem->data->name }}</h4>
                                        <p class="text-muted mb-0">NIS: {{ $childItem->data->nis }} | Kelas {{ $childItem->data->schoolClass->name ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- Today Status Badge -->
                                <div>
                                    @if($childItem->today_status == 'hadir')
                                        <div class="d-flex align-items-center bg-success bg-opacity-10 text-success px-4 py-3 rounded-4">
                                            <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                                            <div class="text-start">
                                                <div class="small fw-bold text-uppercase opacity-75">Status Hari Ini</div>
                                                <div class="fw-bold fs-5">Siswa Hadir</div>
                                            </div>
                                        </div>
                                    @elseif($childItem->today_status == 'sakit')
                                        <div class="d-flex align-items-center bg-warning bg-opacity-10 text-warning px-4 py-3 rounded-4">
                                            <i class="bi bi-bandaid fs-3 me-3"></i>
                                            <div class="text-start">
                                                <div class="small fw-bold text-uppercase opacity-75">Status Hari Ini</div>
                                                <div class="fw-bold fs-5">Sakit</div>
                                            </div>
                                        </div>
                                    @elseif($childItem->today_status == 'izin')
                                        <div class="d-flex align-items-center bg-info bg-opacity-10 text-info px-4 py-3 rounded-4">
                                            <i class="bi bi-envelope-paper fs-3 me-3"></i>
                                            <div class="text-start">
                                                <div class="small fw-bold text-uppercase opacity-75">Status Hari Ini</div>
                                                <div class="fw-bold fs-5">Izin</div>
                                            </div>
                                        </div>
                                    @elseif($childItem->today_status == 'alpha')
                                        <div class="d-flex align-items-center bg-danger bg-opacity-10 text-danger px-4 py-3 rounded-4">
                                            <i class="bi bi-x-circle-fill fs-3 me-3"></i>
                                            <div class="text-start">
                                                <div class="small fw-bold text-uppercase opacity-75">Status Hari Ini</div>
                                                <div class="fw-bold fs-5">Tanpa Keterangan</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center bg-secondary bg-opacity-10 text-secondary px-4 py-3 rounded-4">
                                            <i class="bi bi-clock-history fs-3 me-3"></i>
                                            <div class="text-start">
                                                <div class="small fw-bold text-uppercase opacity-75">Status Hari Ini</div>
                                                <div class="fw-bold fs-5">Belum Absen</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="col-12 mb-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3 text-center">
                                        <div class="mb-2 text-success"><i class="bi bi-check-circle fs-4"></i></div>
                                        <h4 class="fw-bold mb-0 text-dark">{{ $childItem->stats['hadir'] }}</h4>
                                        <small class="text-muted">Total Hadir</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3 text-center">
                                        <div class="mb-2 text-warning"><i class="bi bi-bandaid fs-4"></i></div>
                                        <h4 class="fw-bold mb-0 text-dark">{{ $childItem->stats['sakit'] }}</h4>
                                        <small class="text-muted">Total Sakit</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3 text-center">
                                        <div class="mb-2 text-info"><i class="bi bi-envelope fs-4"></i></div>
                                        <h4 class="fw-bold mb-0 text-dark">{{ $childItem->stats['izin'] }}</h4>
                                        <small class="text-muted">Total Izin</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-3 text-center">
                                        <div class="mb-2 text-danger"><i class="bi bi-x-circle fs-4"></i></div>
                                        <h4 class="fw-bold mb-0 text-dark">{{ $childItem->stats['alpha'] }}</h4>
                                        <small class="text-muted">Total Alpha</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart & Timeline -->
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-soft rounded-4 h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-4">Statistik Absensi</h6>
                                <div id="attendanceChart{{ $childItem->data->id }}" class="d-flex justify-content-center"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card border-0 shadow-soft rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold text-dark mb-0">Riwayat Aktivitas</h6>
                                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-light rounded-pill px-3">Lihat Semua</a>
                                </div>

                                <!-- Timeline CSS -->
                                <div class="timeline ps-2" id="timeline-container-{{ $childItem->data->id }}">
                                    @include('dashboard.partials.timeline', ['recent' => $childItem->recent])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 text-center">
                <i class="bi bi-info-circle fs-1 mb-3 d-block text-primary"></i>
                <h5 class="fw-bold">Belum Ada Data Siswa</h5>
                <p class="mb-0">Akun Anda belum terhubung dengan data siswa manapun.</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach($childrenData as $childItem)
                var options{{ $childItem->data->id }} = {
                    series: [{{ $childItem->stats['hadir'] }}, {{ $childItem->stats['sakit'] }}, {{ $childItem->stats['izin'] }}, {{ $childItem->stats['alpha'] }}],
                    chart: {
                        type: 'donut',
                        height: 220,
                        fontFamily: 'Nunito, sans-serif'
                    },
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                    colors: ['#198754', '#ffc107', '#0dcaf0', '#dc3545'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    name: { show: false },
                                    value: {
                                        show: true,
                                        fontSize: '24px',
                                        fontWeight: 'bold',
                                        color: '#333',
                                        offsetY: 8
                                    },
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        label: 'Total',
                                        color: '#888',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: false },
                    legend: { position: 'right', fontSize: '12px' }
                };

                var chart{{ $childItem->data->id }} = new ApexCharts(document.querySelector("#attendanceChart{{ $childItem->data->id }}"), options{{ $childItem->data->id }});
                chart{{ $childItem->data->id }}.render();
            @endforeach
        });

        // Real-time Polling for Timeline
        setInterval(function() {
            @foreach($childrenData as $childItem)
                fetch("{{ route('dashboard.recent-activity') }}?student_id={{ $childItem->data->id }}&period={{ request('period', 'today') }}")
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('timeline-container-{{ $childItem->data->id }}').innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching recent activity:', error));
            @endforeach
        }, 5000); // 5 seconds
    </script>
    <style>
        .timeline .timeline-item:last-child { border-left-color: transparent !important; }
    </style>
@endsection