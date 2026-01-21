@extends('layouts.app')

@section('content')
    <div class="fade-in-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Tambah Absensi Manual</h2>
                <p class="text-muted">Input data absensi siswa secara manual (Izin, Sakit, dll)</p>
            </div>
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold">Pilih Siswa</label>
                                <select name="student_id"
                                    class="form-select select2 @error('student_id') is-invalid @enderror" required>
                                    <option value="">-- Cari Nama Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->nis }} - {{ $student->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Waktu (Opsional)</label>
                                    <input type="time" name="time" class="form-control" value="{{ date('H:i') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Sesi Absensi</label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="pagi">Absen Pagi</option>
                                        <option value="solat">Absen Sholat</option>
                                        <option value="pulang">Absen Pulang</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Status Kehadiran</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        required>
                                        <option value="sakit">Sakit</option>
                                        <option value="izin">Izin</option>
                                        <option value="alpha">Alpha (Tanpa Keterangan)</option>
                                        <option value="hadir">Hadir</option>
                                        <option value="telat">Telat</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Simpan Data Absensi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection