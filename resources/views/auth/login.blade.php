<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GSC Risk Intelligence</title>

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
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            padding: 40px;
            width: 100%;
            max-width: 440px;
            z-index: 10;
            position: relative;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
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
            margin-bottom: 20px;
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
            padding: 12px 16px 12px 46px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-size: 13.5px;
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
        .btn-login {
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
        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        /* Info Account Box for Demo */
        .demo-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 11px;
            color: #475569;
            margin-top: 25px;
            line-height: 1.5;
        }
        .demo-box b {
            color: #1e293b;
        }
    </style>
</head>
<body>

    <!-- Latar Belakang Gradasi Glow -->
    <div class="ambient-sphere sphere-1"></div>
    <div class="ambient-sphere sphere-2"></div>

    <div class="login-card">
        
        <!-- Header Brand -->
        <div class="brand-header">
            <span class="brand-logo">🌍</span>
            <div class="brand-title">GSC RISK INTELLIGENCE</div>
            <div class="brand-subtitle">Silakan login untuk memantau risiko rantai pasok global</div>
        </div>

        <!-- Alert Notification -->
        @if(session('success'))
            <div class="alert alert-success py-2 px-3 mb-3 text-center" style="font-size: 12px; border-radius: 8px;">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 mb-3 text-center" style="font-size: 12px; border-radius: 8px;">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf

            <!-- Email Input -->
            <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">Email</label>
            <div class="input-group-custom">
                <input type="email" class="form-control-custom" name="email" placeholder="Masukkan alamat email..." required>
                <i class="fa-solid fa-envelope"></i>
            </div>

            <!-- Password Input -->
            <label class="form-label fw-bold text-dark mb-1" style="font-size: 12px;">Password</label>
            <div class="input-group-custom">
                <input type="password" class="form-control-custom" name="password" placeholder="Masukkan kata sandi..." required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-login mt-2">
                Masuk Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
            </button>
        </form>

        <!-- Register Link -->
        <div class="text-center mt-3" style="font-size: 12.5px; color: #64748b;">
            Belum memiliki akun? <a href="{{ route('register') }}" class="text-primary fw-semibold" style="text-decoration: none;">Daftar Sekarang</a>
        </div>

        <!-- Demo Credentials Box -->
        <div class="demo-box">
            <span class="fw-bold d-block mb-1 text-center" style="font-size: 11.5px; color: #334155;">🔑 Kredensial Demo Pengujian</span>
            <div class="d-flex justify-content-between mb-1">
                <span>Admin: <b>admin@example.com</b></span>
                <span>User: <b>user@example.com</b></span>
            </div>
            <div class="text-center">
                Sandi Default: <b>password</b>
            </div>
        </div>

    </div>

</body>
</html>