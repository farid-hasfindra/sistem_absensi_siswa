@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold text-dark">Dashboard Guru</h2>
            <p class="text-muted">Selamat Datang, {{ Auth::user()->name }} ({{ $class->name ?? 'Belum ada Kelas' }})</p>
        </div>

        @if($class)
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card bg-white h-100" style="border-bottom: 4px solid var(--primary-color);">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold text-xs">Siswa Saya</h6>
                                <h3 class="fw-bolder mb-0 text-dark mt-2">{{ $totalStudents }}</h3>
                            </div>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card bg-white h-100" style="border-bottom: 4px solid var(--success-color);">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold text-xs">Hadir Hari Ini</h6>
                                <h3 class="fw-bolder mb-0 text-dark mt-2">{{ $presentToday }}</h3>
                            </div>
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-check-lg fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-soft">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Absensi Kelas Hari Ini</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="p-3 border-0">Nama</th>
                                    <th class="p-3 border-0">Status</th>
                                    <th class="p-3 border-0">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class->students as $student)
                                    @php
                                        $attendance = $student->attendances()->where('date', today())->first();
                                    @endphp
                                    <tr>
                                        <td class="p-3 fw-bold">{{ $student->name }}</td>
                                        <td class="p-3">
                                            @if($attendance)
                                                <span
                                                    class="badge {{ $attendance->status == 'hadir' ? 'bg-success' : ($attendance->status == 'telat' ? 'bg-warning' : 'bg-danger') }} rounded-pill px-3">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Belum Scan</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-muted">
                                            {{ $attendance ? \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-4 text-center text-muted">Tidak ada siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info border-0 shadow-sm rounded-4">
                Anda belum ditugaskan ke kelas manapun. Silakan hubungi Administrator.
            </div>
        @endif
    </div>
@endsection