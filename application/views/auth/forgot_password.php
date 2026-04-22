<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Learning Management System</title>
    <link rel="icon" type="image/png" href="<?= base_url('uploads/icon/favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 1rem;
        }

        .forgot-container {
            width: 100%;
            max-width: 440px;
        }

        .forgot-card {
            background: white;
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px -10px rgba(99, 102, 241, 0.15);
            border: 1px solid #e2e8f0;
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .forgot-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.3);
        }

        .forgot-card h1 {
            color: #0f172a;
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .forgot-card .subtitle {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
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

        .form-floating .form-control:focus~label,
        .form-floating .form-control:not(:placeholder-shown)~label {
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

        .btn-submit {
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

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.35);
        }

        .btn-submit:active {
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

        .alert-success-custom {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #16a34a;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .back-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .back-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?= render_notifications() ?>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="forgot-icon">
                    <i class="bi bi-key-fill"></i>
                </div>
                <h1>Forgot Password</h1>
                <p class="subtitle">Enter your email address and we'll send you instructions to reset your password.</p>
            </div>

            <form action="<?= site_url('auth/forgot_password') ?>" method="post" autocomplete="off">
                <div class="form-floating position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required autofocus>
                    <label for="email">Email address</label>
                </div>

                <button type="submit" class="btn-submit">
                    Send Reset Instructions
                </button>
            </form>

            <div class="back-link">
                <a href="<?= site_url('auth') ?>">
                    <i class="bi bi-arrow-left"></i> Back to Login
                </a>
            </div>
        </div>
    </div>
</body>

</html>
