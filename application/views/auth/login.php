<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learning Management System</title>
    <link rel="icon" type="image/png" href="<?= base_url('uploads/icon/favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-tertiary: #94a3b8;
            --border-soft: #e2e8f0;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'SF Pro Display', 'Segoe UI', Roboto, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        body {
            min-height: 100vh;
            font-family: var(--font-sans);
            color: var(--text-primary);
            letter-spacing: -0.01em;
            background: #e5eaf0;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 1.1;
            background: linear-gradient(135deg, #1e293b 0%, #3b82f6 50%, #60a5fa 100%);
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
            inset: 0;
            background:
                radial-gradient(ellipse at top left, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
            pointer-events: none;
        }

        .login-left-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 460px;
        }

        .login-left-logo {
            width: 72px;
            height: 72px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .login-left h1 {
            font-size: 2.25rem;
            font-weight: 600;
            margin-bottom: 0.875rem;
            letter-spacing: -0.035em;
            line-height: 1.15;
        }

        .login-left p {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 2.5rem;
            letter-spacing: -0.005em;
        }

        .login-features {
            display: flex;
            gap: 1.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .login-feature {
            text-align: center;
        }

        .login-feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.65rem;
            font-size: 1.05rem;
            color: #e5e7eb;
        }

        .login-feature span {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: -0.005em;
        }

        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 2rem;
        }

        .login-card {
            background: white;
            border-radius: 18px;
            padding: 2.75rem 2.5rem;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: left;
            margin-bottom: 2rem;
        }

        .login-card h2 {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.6rem;
            margin-bottom: 0.45rem;
            letter-spacing: -0.03em;
        }

        .login-card .subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            letter-spacing: -0.005em;
        }

        .form-floating {
            margin-bottom: 0.875rem;
        }

        .form-floating .form-control {
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            color: var(--text-primary);
            height: 52px;
            padding: 1rem 1rem 0.5rem 2.75rem;
            font-size: 0.9rem;
            font-family: var(--font-sans);
            transition: all 0.15s ease;
        }

        .form-floating .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            color: var(--text-primary);
        }

        .form-floating label {
            color: var(--text-tertiary);
            padding-left: 2.75rem;
            font-size: 0.9rem;
            letter-spacing: -0.005em;
        }

        .form-floating .form-control:focus~label,
        .form-floating .form-control:not(:placeholder-shown)~label {
            color: var(--text-secondary);
        }

        .input-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            z-index: 5;
            font-size: 1rem;
            transition: color 0.15s;
        }

        .form-floating:focus-within .input-icon {
            color: var(--text-primary);
        }

        .btn-login {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
            font-weight: 500;
            font-size: 0.925rem;
            font-family: var(--font-sans);
            letter-spacing: -0.005em;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 1.25rem;
            box-shadow: 0 1px 3px rgba(59, 130, 246, 0.2);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .password-toggle {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-tertiary);
            cursor: pointer;
            z-index: 5;
            padding: 0.25rem;
            transition: color 0.15s;
        }

        .password-toggle:hover {
            color: var(--text-primary);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-soft);
            color: var(--text-tertiary);
            font-size: 0.825rem;
        }

        .login-footer a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            letter-spacing: -0.005em;
        }

        .login-footer a:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
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
    <?= render_notifications() ?>
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