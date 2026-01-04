@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold text-dark">Dashboard Wali Murid</h2>
            <p class="text-muted">Selamat Datang, {{ Auth::user()->name }}</p>
        </div>

        @if($children->count() > 0)
            @foreach($children as $child)
                <div class="card border-0 shadow-soft mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-primary">
                            <i class="bi bi-person-circle me-2"></i> {{ $child->name }}
                            <span class="text-muted small fw-normal">({{ $child->nis }})</span>
                        </h5>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                            {{ $child->schoolClass->name ?? 'Tanpa Kelas' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold text-muted mb-3 text-uppercase text-xs" style="letter-spacing: 0.5px">Absensi Terbaru
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle">
                                <thead class="text-muted border-bottom">
                                    <tr>
                                        <th class="pb-2">Tanggal</th>
                                        <th class="pb-2">Tipe</th>
                                        <th class="pb-2">Waktu</th>
                                        <th class="pb-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($child->attendances()->latest()->take(5)->get() as $attendance)
                                        <tr>
                                            <td class="py-2 text-dark fw-bold">
                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                            <td class="py-2">{{ ucfirst($attendance->type) }}</td>
                                            <td class="py-2 text-muted">
                                                {{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i') }}</td>
                                            <td class="py-2">
                                                <span
                                                    class="badge {{ $attendance->status == 'hadir' ? 'bg-success' : ($attendance->status == 'telat' ? 'bg-warning' : 'bg-danger') }} rounded-pill px-2">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Belum ada data absensi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning border-0 shadow-sm rounded-4">
                Tidak ada siswa yang terhubung dengan akun Anda. Silakan hubungi Administrator.
            </div>
        @endif
    </div>
@endsection