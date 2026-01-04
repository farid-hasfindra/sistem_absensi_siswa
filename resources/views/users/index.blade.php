@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark">Manajemen Pengguna</h2>
                <p class="text-muted">Kelola Admin, Guru, dan Wali Murid</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-lg me-2"></i> Tambah Pengguna Baru
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-soft">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4 border-0 rounded-top-start">Nama</th>
                                <th class="p-4 border-0">Username</th>
                                <th class="p-4 border-0">Peran</th>
                                <th class="p-4 border-0">Detail</th>
                                <th class="p-4 border-0 rounded-top-end text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="p-4 fw-bold">{{ $user->name }}</td>
                                    <td class="p-4">{{ $user->username }}</td>
                                    <td class="p-4">
                                        <span class="badge rounded-pill px-3 py-2 
                                                                            @if($user->role == 'admin') bg-dark 
                                                                            @elseif($user->role == 'guru') bg-primary 
                                                                            @else bg-warning text-dark @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-muted small">
                                        @if($user->role == 'guru' && $user->teacher)
                                            NIP: {{ $user->teacher->nip }}
                                        @elseif($user->role == 'wali_murid' && $user->parent)
                                            Telp: {{ $user->parent->phone }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-4 text-end">
                                        <button class="btn btn-sm btn-light text-warning me-1"
                                            onclick="editUser({{ $user->load('teacher', 'parent') }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light text-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran</label>
                                <select name="role" class="form-select rounded-3" id="roleSelect" required>
                                    <option value="guru">Guru</option>
                                    <option value="wali_murid">Wali Murid</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" name="password" id="userPassword"
                                    class="form-control rounded-start-3" required>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePassword('userPassword')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Teacher Fields -->
                        <div id="teacherFields" class="d-none bg-light p-3 rounded-3 mb-3">
                            <h6 class="fw-bold mb-3 text-primary">Detail Guru</h6>
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control">
                            </div>
                        </div>

                        <!-- Parent Fields -->
                        <div id="parentFields" class="d-none bg-light p-3 rounded-3 mb-3">
                            <h6 class="fw-bold mb-3 text-warning">Detail Wali Murid</h6>
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Buat Pengguna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="editName" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" id="editUsername" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi (Opsional)</label>
                            <div class="input-group">
                                <input type="password" name="password" id="editPassword"
                                    class="form-control rounded-start-3" placeholder="Biarkan kosong jika tidak diubah">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePassword('editPassword')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Role Specific Fields (Read Only/Hidden logic if needed, but for now mostly name/username/password) -->
                        <div id="editTeacherFields" class="d-none bg-light p-3 rounded-3 mb-3">
                            <h6 class="fw-bold mb-3 text-primary">Detail Guru</h6>
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" id="editNip" class="form-control">
                            </div>
                        </div>

                        <div id="editParentFields" class="d-none bg-light p-3 rounded-3 mb-3">
                            <h6 class="fw-bold mb-3 text-warning">Detail Wali Murid</h6>
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" id="editPhone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" id="editAddress" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('roleSelect').addEventListener('change', function () {
            const role = this.value;
            document.getElementById('teacherFields').classList.add('d-none');
            document.getElementById('parentFields').classList.add('d-none');

            if (role === 'guru') {
                document.getElementById('teacherFields').classList.remove('d-none');
            } else if (role === 'wali_murid') {
                document.getElementById('parentFields').classList.remove('d-none');
            }
        });

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.currentTarget.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        function editUser(user) {
            document.getElementById('editUserForm').action = `/users/${user.id}`;
            document.getElementById('editName').value = user.name;
            document.getElementById('editUsername').value = user.username;

            // Reset fields
            document.getElementById('editTeacherFields').classList.add('d-none');
            document.getElementById('editParentFields').classList.add('d-none');

            if (user.role === 'guru' && user.teacher) {
                document.getElementById('editTeacherFields').classList.remove('d-none');
                document.getElementById('editNip').value = user.teacher.nip;
            } else if (user.role === 'wali_murid' && user.parent) {
                document.getElementById('editParentFields').classList.remove('d-none');
                document.getElementById('editPhone').value = user.parent.phone;
                document.getElementById('editAddress').value = user.parent.address;
            }

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }
    </script>
@endsection