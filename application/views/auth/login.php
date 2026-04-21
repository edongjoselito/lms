<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learning Management System</title>
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
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -200px;
            left: -200px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            bottom: -150px;
            right: -150px;
        }

        .login-left-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 500px;
        }

        .login-left-logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3.5rem;
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-left h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .login-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .login-features {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .login-feature {
            text-align: center;
        }

        .login-feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.5rem;
        }

        .login-feature span {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 2rem;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px -10px rgba(99, 102, 241, 0.15);
            border: 1px solid #e2e8f0;
            width: 100%;
            max-width: 440px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-card h2 {
            color: #0f172a;
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .login-card .subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-floating {
            margin-bottom: 1.25rem;
        }

        .form-floating .form-control {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            color: #0f172a;
            height: 56px;
            padding: 1rem 1rem 0.5rem 3rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-floating .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            color: #0f172a;
        }

        .form-floating label {
            color: #64748b;
            padding-left: 3rem;
        }

        .form-floating .form-control:focus ~ label,
        .form-floating .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 5;
            font-size: 1.2rem;
            transition: color 0.3s;
        }

        .form-floating:focus-within .input-icon {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            height: 54px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.025em;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            z-index: 5;
            padding: 0.25rem;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            .login-left {
                display: none;
            }
            .login-right {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-left">
            <div class="login-left-content">
                <div class="login-left-logo">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h1>Learning Management System</h1>
                <p>Empower your educational journey with our comprehensive platform for courses, assessments, and collaborative learning.</p>
                <div class="login-features">
                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <span>Rich Content</span>
                    </div>
                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <span>Assessments</span>
                    </div>
                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <span>Collaboration</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-card">
                <div class="login-header">
                    <h2>Welcome Back</h2>
                    <p class="subtitle">Sign in to continue</p>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert-custom">
                        <i class="bi bi-exclamation-circle-fill"></i>
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
                        Sign In
                    </button>
                </form>

                <div class="login-footer">
                    <a href="<?= site_url('auth/forgot_password') ?>">Forgot password?</a>
                </div>
            </div>
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
