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
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.85) 100%), url('/images/login_background.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow-y: auto;
        }

        /* Ambient Light Spheres */
        .ambient-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.25;
            z-index: 1;
            pointer-events: none;
        }
        .sphere-1 {
            width: 400px;
            height: 400px;
            background: #3b82f6;
            top: -100px;
            left: -100px;
        }
        .sphere-2 {
            width: 450px;
            height: 450px;
            background: #6c63ff;
            bottom: -150px;
            right: -100px;
        }

        /* Glassmorphism Card */
        .login-card {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 28px;
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.3);
            padding: 45px 35px;
            width: 100%;
            max-width: 450px;
            z-index: 10;
            position: relative;
            transition: transform 0.3s ease;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-logo {
            font-size: 40px;
            margin-bottom: 12px;
            display: inline-block;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .brand-subtitle {
            font-size: 12.5px;
            color: #475569;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Form Inputs */
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group-custom i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 16px;
            transition: color 0.2s;
        }
        .form-control-custom {
            width: 100%;
            padding: 13px 18px 13px 50px;
            border-radius: 14px;
            border: 2px solid rgba(148, 163, 184, 0.25);
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            background: rgba(255, 255, 255, 0.7);
            transition: all 0.25s ease;
        }
        .form-control-custom::placeholder {
            color: #94a3b8;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }
        .form-control-custom:focus + i {
            color: #3b82f6;
        }

        /* Buttons */
        .btn-login {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-size: 14.5px;
            font-weight: 700;
            width: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.45);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        /* Info Account Box for Demo */
        .demo-box {
            background: rgba(248, 250, 252, 0.75);
            border: 1px dashed rgba(148, 163, 184, 0.5);
            border-radius: 14px;
            padding: 14px;
            font-size: 11px;
            color: #475569;
            margin-top: 25px;
            line-height: 1.6;
        }
        .demo-box b {
            color: #0f172a;
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