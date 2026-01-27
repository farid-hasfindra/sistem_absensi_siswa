@extends('layouts.app')

@section('content')
    <div class="fade-in-up">
        <div class="mb-4">
            <div>
                <h2 class="fw-bold">Laporan Absensi</h2>
                <p class="text-muted">Lihat dan ekspor riwayat absensi</p>
            </div>
            <div>
                <div>
                    @if(Auth::user()->role === 'guru' || Auth::user()->role === 'guru_mapel')
                        <a href="{{ route('attendance.create') }}" class="btn btn-primary text-white me-2">
                            <i class="bi bi-plus-lg"></i> Tambah Absensi
                        </a>
                    @endif
                    <a href="{{ route('reports.export', ['date' => $date]) }}" class="btn btn-success text-white">
                        <i class="bi bi-file-earmark-excel"></i> Ekspor Excel
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 my-4">
                <div class="card-body p-4">
                    <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Filter Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="p-3 border-0 rounded-top-start">Waktu</th>
                                    <th class="p-3 border-0">Nama Siswa</th>
                                    <th class="p-3 border-0">Kelas</th>
                                    <th class="p-3 border-0">Tipe</th>
                                    <th class="p-3 border-0">Status</th>
                                    <th class="p-3 border-0">Diabsen Oleh</th>
                                    <th
                                        class="p-3 border-0 @unless(Auth::user()->role === 'guru' || Auth::user()->role === 'guru_mapel') rounded-top-end @endunless">
                                        Tanggal</th>
                                    @if(Auth::user()->role === 'guru' || Auth::user()->role === 'guru_mapel')
                                        <th class="p-3 border-0 rounded-top-end">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td class="p-3">{{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i:s') }}
                                        </td>
                                        <td class="p-3 fw-bold">{{ $attendance->student->name }}</td>
                                        <td class="p-3">{{ $attendance->student->class }}</td>
                                        <td class="p-3">
                                            @if($attendance->type == 'pagi')
                                                <span
                                                    class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Pagi</span>
                                            @elseif($attendance->type == 'solat')
                                                <span
                                                    class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Solat</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Pulang</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            @if($attendance->status == 'hadir')
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Hadir</span>
                                            @elseif($attendance->status == 'telat')
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Telat</span>
                                            @else
                                                <span
                                                    class="badge bg-dark bg-opacity-10 text-dark px-3 py-2 rounded-pill">{{ ucfirst($attendance->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="p-3 small">
                                            @if($attendance->recorder)
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <div class="fw-bold">{{ $attendance->recorder->name }}</div>
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ ucfirst(str_replace('_', ' ', $attendance->recorder->role)) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-muted">{{ $attendance->date }}</td>
                                        @if(Auth::user()->role === 'guru' || Auth::user()->role === 'guru_mapel')
                                            <td class="p-3">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('attendance.edit', $attendance->id) }}"
                                                        class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ (Auth::user()->role === 'guru' || Auth::user()->role === 'guru_mapel') ? 8 : 7 }}"
                                            class="p-5 text-center text-muted">Tidak ada data absensi untuk tanggal
                                            ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection