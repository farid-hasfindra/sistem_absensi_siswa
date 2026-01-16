<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMP Negeri 1 Pangkalan Koto Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin: 0;
        }

        /* Animated Background */
        .bg-animate {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
            filter: blur(60px);
            animation: float 20s infinite ease-in-out;
        }

        .circle-1 {
            width: 400px;
            height: 400px;
            background: rgba(67, 97, 238, 0.2);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 300px;
            height: 300px;
            background: rgba(72, 149, 239, 0.2);
            bottom: -50px;
            right: -50px;
            animation-delay: -5s;
        }

        .circle-3 {
            width: 200px;
            height: 200px;
            background: rgba(63, 55, 201, 0.1);
            top: 40%;
            left: 40%;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-20px) scale(1.05);
            }
        }

        /* Glass Card */
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            animation: cardEntrance 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            position: relative;
            z-index: 10;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Animation */
        .school-logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.15);
            margin-bottom: 1.5rem;
            animation: logoFloat 3s ease-in-out infinite;
            border: 4px solid white;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        /* Form Styling */
        .form-floating>.form-control {
            border-radius: 12px;
            border: 2px solid transparent;
            background-color: #f8f9fa;
            padding-left: 1.5rem;
            transition: all 0.3s ease;
        }

        .form-floating>.form-control:focus {
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
        }

        .form-floating>label {
            padding-left: 1.5rem;
            color: #adb5bd;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .password-toggle {
            cursor: pointer;
            color: var(--primary-color);
            background: transparent;
            border: none;
            padding: 0;
            font-size: 1.2rem;
            transition: color 0.3s;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
        }

        .password-toggle:hover {
            color: var(--secondary-color);
        }

        .school-title {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
    </style>
</head>

<body>

    <!-- Animated Background -->
    <div class="bg-animate">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
    </div>

    <div class="container d-flex justify-content-center">
        <div class="login-card text-center">

            <!-- School Brand -->
            <div class="mb-4">
                <img src="{{ asset('logo_sekolah_baru.jpg') }}" alt="Logo Sekolah" class="school-logo">
                <h4 class="school-title mb-1">SMP NEGERI 1</h4>
                <p class="text-secondary fw-bold small text-uppercase letter-spacing-2">Pangkalan Koto Baru</p>
            </div>

            <div class="text-start mb-4 ps-1">
                <p class="text-muted mb-0 small">Selamat Datang,</p>
                <h2 class="fw-bold text-dark">Silakan Masuk 👋</h2>
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" name="username" class="form-control" id="usernameInput" placeholder="Username"
                        required value="{{ old('username') }}">
                    <label for="usernameInput">Username</label>
                    @error('username')
                        <small class="text-danger ps-3">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" name="password" class="form-control" id="loginPassword"
                        placeholder="Kata Sandi" required style="padding-right: 45px;">
                    <label for="loginPassword">Kata Sandi</label>
                    <button type="button" class="password-toggle" onclick="togglePassword('loginPassword')">
                        <i class="bi bi-eye"></i>
                    </button>
                    <!-- Eye Animation Script -->
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 mb-4">
                    MASUK SEKARANG <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center">
                <p class="text-secondary small mb-0">
                    &copy; 2026 Sistem Absensi Siswa.
                    <br>All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.currentTarget.querySelector('i');

            // Add animation class
            icon.style.transform = "scale(0.8)";
            setTimeout(() => icon.style.transform = "scale(1)", 150);

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
</body>

</html>