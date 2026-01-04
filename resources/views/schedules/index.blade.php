@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark">Manajemen Jadwal</h2>
                <p class="text-muted">Kelola Jadwal Kelas</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                <i class="bi bi-plus-lg me-2"></i> Tambah Jadwal Baru
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-soft">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4 border-0 rounded-top-start">Hari</th>
                                <th class="p-4 border-0">Waktu</th>
                                <th class="p-4 border-0">Kelas</th>
                                <th class="p-4 border-0">Mata Pelajaran</th>
                                <th class="p-4 border-0">Guru</th>
                                <th class="p-4 border-0 rounded-top-end text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules->sortBy('day') as $schedule)
                                @php
                                    $dayMap = [
                                        'Monday' => 'Senin',
                                        'Tuesday' => 'Selasa',
                                        'Wednesday' => 'Rabu',
                                        'Thursday' => 'Kamis',
                                        'Friday' => 'Jumat',
                                        'Saturday' => 'Sabtu',
                                        'Sunday' => 'Minggu',
                                    ];
                                    $dayIndo = $dayMap[$schedule->day] ?? $schedule->day;
                                @endphp
                                <tr>
                                    <td class="p-4 fw-bold text-primary">{{ $dayIndo }}</td>
                                    <td class="p-4">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </td>
                                    <td class="p-4">
                                        <span class="badge bg-light text-dark border">{{ $schedule->schoolClass->name }}</span>
                                    </td>
                                    <td class="p-4 fw-bold">{{ $schedule->subject->name }}</td>
                                    <td class="p-4 text-muted">{{ $schedule->teacher->name }}</td>
                                    <td class="p-4 text-end">
                                        <form action="{{ route('schedules.destroy', $schedule) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light text-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-muted">Belum ada jadwal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Jadwal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hari</label>
                                <select name="day" class="form-select rounded-3" required>
                                    <option value="Monday">Senin</option>
                                    <option value="Tuesday">Selasa</option>
                                    <option value="Wednesday">Rabu</option>
                                    <option value="Thursday">Kamis</option>
                                    <option value="Friday">Jumat</option>
                                    <option value="Saturday">Sabtu</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas</label>
                                <select name="class_id" class="form-select rounded-3" required>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Mulai</label>
                                <input type="time" name="start_time" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Selesai</label>
                                <input type="time" name="end_time" class="form-control rounded-3" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="subject_id" class="form-select rounded-3" required>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Guru</label>
                            <select name="teacher_id" class="form-select rounded-3" required>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Buat Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection