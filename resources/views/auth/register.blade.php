<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - GSC Risk Intelligence</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Ambient Light Spheres */
        .ambient-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            z-index: 1;
            pointer-events: none;
        }
        .sphere-1 {
            width: 300px;
            height: 300px;
            background: #3b82f6;
            top: -50px;
            left: -50px;
        }
        .sphere-2 {
            width: 350px;
            height: 350px;
            background: #6c63ff;
            bottom: -80px;
            right: -80px;
        }

        /* Glassmorphism Card */
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            padding: 40px;
            width: 100%;
            max-width: 460px;
            z-index: 10;
            position: relative;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .brand-logo {
            font-size: 32px;
            margin-bottom: 10px;
            display: inline-block;
        }
        .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .brand-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Form Inputs */
        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }
        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            transition: color 0.2s;
        }
        .form-control-custom {
            width: 100%;
            padding: 11px 16px 11px 46px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            background: #fff;
            transition: all 0.2s;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        .form-control-custom:focus + i {
            color: #3b82f6;
        }

        /* Buttons */
        .btn-register {
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            width: 100%;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .btn-register:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        .btn-register:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <!-- Latar Belakang Gradasi Glow -->
    <div class="ambient-sphere sphere-1"></div>
    <div class="ambient-sphere sphere-2"></div>

    <div class="register-card">
        
        <!-- Header Brand -->
        <div class="brand-header">
            <span class="brand-logo">📝</span>
            <div class="brand-title">DAFTAR AKUN BARU</div>
            <div class="brand-subtitle">Buat akun untuk mulai memantau risiko logistik</div>
        </div>

        <!-- Alert Notification -->
        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 12px; border-radius: 8px;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Register Form -->
        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <!-- Name Input -->
            <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">Nama Lengkap</label>
            <div class="input-group-custom">
                <input type="text" class="form-control-custom" name="name" placeholder="Masukkan nama lengkap Anda..." required>
                <i class="fa-solid fa-user"></i>
            </div>

            <!-- Email Input -->
            <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">Email</label>
            <div class="input-group-custom">
                <input type="email" class="form-control-custom" name="email" placeholder="Masukkan alamat email..." required>
                <i class="fa-solid fa-envelope"></i>
            </div>

            <!-- Password Input -->
            <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">Password</label>
            <div class="input-group-custom">
                <input type="password" class="form-control-custom" name="password" placeholder="Buat kata sandi minimal 8 karakter..." required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <!-- Password Confirmation Input -->
            <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">Konfirmasi Password</label>
            <div class="input-group-custom">
                <input type="password" class="form-control-custom" name="password_confirmation" placeholder="Ulangi kata sandi di atas..." required>
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-register mt-2">
                Daftar Akun Baru <i class="fa-solid fa-user-plus ms-1"></i>
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-3" style="font-size: 12.5px; color: #64748b;">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary fw-semibold" style="text-decoration: none;">Masuk Sistem</a>
        </div>

    </div>

</body>
</html>