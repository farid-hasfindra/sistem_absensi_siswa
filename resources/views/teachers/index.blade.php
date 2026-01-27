@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Daftar Guru</h3>
                <p class="text-muted mb-0">Kelola dan lihat informasi guru.</p>
            </div>
            <!-- Add Guru button could go here if we separate user creation, 
                     but for now users are created in User Management. 
                     Maybe just a link to User Management? -->
            <a href="{{ route('users.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-person-plus-fill me-2"></i> Kelola Pengguna
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">Nama Guru</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">NIP</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">Wali Kelas</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">Mata Pelajaran</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">Total Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px; font-weight: bold;">
                                                {{ substr($teacher->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $teacher->name }}</div>
                                                <div class="small text-muted">{{ $teacher->user->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-muted">{{ $teacher->nip ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($teacher->schoolClass)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                {{ $teacher->schoolClass->name }}
                                            </span>
                                        @else
                                            <span class="text-muted small">Bukan Wali Kelas</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $subjects = $teacher->schedules->pluck('subject.name')->unique();
                                        @endphp
                                        @if($subjects->count() > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($subjects as $subject)
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">{{ $subject }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 fw-bold text-dark">
                                        {{ $teacher->schedules->count() }} Jam
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                                            alt="Empty" style="width: 150px; opacity: 0.5;">
                                        <p class="mt-3 mb-0">Belum ada data guru.</p>
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