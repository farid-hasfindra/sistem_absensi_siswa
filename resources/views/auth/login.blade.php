<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Siswa</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="bg-animated"></div>

    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-md-5">
            <div class="glass-card p-5 fade-in-up">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Selamat Datang!</h2>
                    <p class="text-white-50">Silakan masuk untuk melanjutkan</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                            value="{{ old('username') }}">
                        @error('username')
                            <small class="text-warning">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <input type="password" name="password" id="loginPassword" class="form-control"
                                placeholder="Masukkan kata sandi" required>
                            <button type="button" class="btn btn-outline-secondary bg-white text-muted"
                                onclick="togglePassword('loginPassword')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <script>
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
                    </script>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary d-block">MASUK</button>
                    </div>
                </form>

                <div class="mt-4 text-center text-white-50" style="font-size: 0.8rem;">
                    &copy; 2026 Sistem Absensi Siswa
                </div>
            </div>
        </div>
    </div>
</body>

</html>