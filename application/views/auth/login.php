<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow: hidden;
        }

        .bg-shapes {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
        }

        .bg-shapes .shape-1 {
            width: 600px; height: 600px;
            background: #6366f1;
            top: -200px; right: -100px;
        }

        .bg-shapes .shape-2 {
            width: 400px; height: 400px;
            background: #06b6d4;
            bottom: -150px; left: -100px;
        }

        .bg-shapes .shape-3 {
            width: 300px; height: 300px;
            background: #8b5cf6;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .login-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.75rem;
            color: white;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3);
        }

        .login-card h2 {
            color: #f1f5f9;
            font-weight: 700;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .login-card .subtitle {
            color: #94a3b8;
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .form-floating .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            color: #f1f5f9;
            height: 56px;
            padding: 1rem 1rem 0.5rem 3rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-floating .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            color: #f1f5f9;
        }

        .form-floating label {
            color: #64748b;
            padding-left: 3rem;
        }

        .form-floating .form-control:focus ~ label,
        .form-floating .form-control:not(:placeholder-shown) ~ label {
            color: #94a3b8;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 5;
            font-size: 1.15rem;
            transition: color 0.3s;
        }

        .form-floating:focus-within .input-icon {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.025em;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 14px;
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            z-index: 5;
            padding: 0.25rem;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h2>Welcome Back</h2>
            <p class="subtitle">Sign in to your admin account</p>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-custom">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('auth/login') ?>" method="post" autocomplete="off">
                <div class="form-floating position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required autofocus>
                    <label for="email">Email address</label>
                </div>

                <div class="form-floating position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>

                <button type="submit" class="btn-login">
                    <span>Sign In</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
