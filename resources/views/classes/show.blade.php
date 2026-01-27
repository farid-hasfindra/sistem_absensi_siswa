@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('classes.index') }}"
                    class="text-decoration-none text-muted small hover-primary mb-1 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kelas
                </a>
                <h3 class="fw-bold text-dark mb-1">Detail Kelas {{ $schoolClass->name }}</h3>
                <p class="text-muted mb-0">
                    Wali Kelas: <span
                        class="fw-bold text-primary">{{ $schoolClass->teacher->name ?? 'Belum ditentukan' }}</span>
                </p>
            </div>
            <!-- Add Student Button could be here, linking to Student Management -->
            <a href="{{ route('students.index') }}?class_id={{ $schoolClass->id }}"
                class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i> Tambah Siswa
            </a>
        </div>

        <!-- Students List -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Daftar Siswa ({{ $schoolClass->students->count() }})</h6>
                <div class="input-group input-group-sm w-auto">
                    <input type="text" class="form-control border-end-0 ps-3 rounded-start-pill" placeholder="Cari siswa..."
                        id="searchStudent">
                    <span class="input-group-text bg-white border-start-0 pe-3 rounded-end-pill"><i
                            class="bi bi-search"></i></span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="studentsTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase" width="5%">No</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">Nama Siswa</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">NIS/NISN</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase">Orang Tua/Wali</th>
                                <th class="px-4 py-3 border-0 text-secondary small text-uppercase text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schoolClass->students as $index => $student)
                                <tr>
                                    <td class="px-4 py-3 fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 position-relative">
                                                @if($student->photo)
                                                    <img src="{{ Storage::url($student->photo) }}"
                                                        class="rounded-circle shadow-sm object-fit-cover" width="40" height="40"
                                                        alt="{{ $student->name }}">
                                                @else
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                                                        style="width: 40px; height: 40px;">
                                                        {{ substr($student->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('students.show', $student->id) }}"
                                                    class="text-decoration-none text-dark hover-primary">
                                                    <div class="fw-bold">{{ $student->name }}</div>
                                                </a>
                                                <div class="small text-muted">
                                                    {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-dark">{{ $student->nis }}</div>
                                        <div class="small text-muted">{{ $student->nisn ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($student->parent)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-fill text-muted me-2"></i>
                                                {{ $student->parent->name }}
                                            </div>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill">Belum
                                                Taut</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <a href="{{ route('students.show', $student->id) }}"
                                            class="btn btn-sm btn-light text-primary rounded-circle" data-bs-toggle="tooltip"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                                            alt="Empty" style="width: 120px; opacity: 0.5;">
                                        <p class="mt-3 mb-0">Belum ada siswa di kelas ini.</p>
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

@section('scripts')
    <script>
        document.getElementById('searchStudent').addEventListener('keyup', function () {
            let searchText = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('#studentsTable tbody tr');

            tableRows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    </script>
@endsection