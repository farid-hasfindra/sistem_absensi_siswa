@extends('layouts.app')

@section('content')
    <div class="fade-in-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Laporan Absensi</h2>
                <p class="text-muted">View and export attendance history</p>
            </div>
            <div>
                <a href="{{ route('reports.export') }}" class="btn btn-success text-white">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Filter Date</label>
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
                                <th class="p-4 border-0 rounded-top-start">Time</th>
                                <th class="p-4 border-0">Student Name</th>
                                <th class="p-4 border-0">Class</th>
                                <th class="p-4 border-0">Type</th>
                                <th class="p-4 border-0">Status</th>
                                <th class="p-4 border-0 rounded-top-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td class="p-4">{{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i:s') }}</td>
                                    <td class="p-4 fw-bold">{{ $attendance->student->name }}</td>
                                    <td class="p-4">{{ $attendance->student->class }}</td>
                                    <td class="p-4">
                                        @if($attendance->type == 'pagi')
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Pagi</span>
                                        @elseif($attendance->type == 'solat')
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Solat</span>
                                        @else
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Pulang</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
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
                                    <td class="p-4 text-muted">{{ $attendance->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-5 text-center text-muted">No attendance records found for this
                                        date.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection