<?php $has_custom_login_image = !empty($login_image_url); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BlueCampus</title>
    <link rel="icon" type="image/png" href="<?= base_url('uploads/icon/favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    <style>
        :root {
            --blue-980: #081734;
            --blue-950: #0d2453;
            --blue-900: #13367a;
            --blue-850: #1a4bb1;
            --blue-800: #2563eb;
            --blue-700: #3b82f6;
            --blue-600: #60a5fa;
            --blue-100: #dbeafe;
            --blue-050: #eff6ff;
            --ink-950: #0f172a;
            --ink-800: #1e293b;
            --ink-700: #334155;
            --ink-500: #64748b;
            --ink-300: #cbd5e1;
            --ink-200: #e2e8f0;
            --white: #ffffff;
            --font-heading: 'Lexend', 'Segoe UI', sans-serif;
            --font-body: 'Manrope', 'Segoe UI', sans-serif;
            --shell-radius: 34px;
            --card-radius: 28px;
            --shell-shadow: 0 28px 80px rgba(15, 23, 42, 0.16);
            --card-shadow: 0 24px 60px rgba(37, 99, 235, 0.14);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        body {
            min-height: 100vh;
            font-family: var(--font-body);
            color: var(--ink-950);
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.28), transparent 28%),
                radial-gradient(circle at bottom right, rgba(96, 165, 250, 0.22), transparent 30%),
                linear-gradient(145deg, #edf5ff 0%, #dde9ff 48%, #f8fbff 100%);
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 999px;
            pointer-events: none;
            z-index: 0;
            filter: blur(10px);
        }

        body::before {
            width: 220px;
            height: 220px;
            top: 34px;
            left: -72px;
            background: rgba(37, 99, 235, 0.12);
        }

        body::after {
            width: 320px;
            height: 320px;
            right: -120px;
            bottom: -80px;
            background: rgba(29, 78, 216, 0.11);
        }

        .page-shell {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 1;
        }

        .login-shell {
            width: 100%;
            max-width: 1240px;
            min-height: min(730px, calc(100vh - 36px));
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(340px, 410px);
            background: rgba(255, 255, 255, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: var(--shell-radius);
            box-shadow: var(--shell-shadow);
            backdrop-filter: blur(14px);
            overflow: hidden;
        }

        .login-panel {
            grid-column: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.1), transparent 34%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.74) 0%, rgba(255, 255, 255, 0.96) 100%);
        }

        .form-card {
            width: 100%;
            max-width: 392px;
            padding: 30px 28px 24px;
            border-radius: var(--card-radius);
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(219, 234, 254, 0.95);
            box-shadow: var(--card-shadow);
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 18px;
            margin-bottom: 18px;
            border-bottom: 1px solid var(--ink-200);
        }

        .brand-icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--blue-900), var(--blue-600));
            color: var(--white);
            font-size: 1.35rem;
            box-shadow: 0 18px 28px rgba(37, 99, 235, 0.18);
            flex-shrink: 0;
        }

        .brand-copy small {
            display: block;
            margin-bottom: 4px;
            color: var(--blue-850);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-copy strong {
            display: block;
            font-family: var(--font-heading);
            font-size: 1.38rem;
            color: var(--blue-950);
            letter-spacing: -0.04em;
        }

        .form-card h1 {
            margin-bottom: 8px;
            font-family: var(--font-heading);
            font-size: 1.82rem;
            line-height: 1;
            letter-spacing: -0.05em;
            color: var(--blue-950);
        }

        .login-subtitle {
            margin-bottom: 18px;
            color: var(--ink-500);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .field-group {
            margin-bottom: 16px;
        }

        .field-label {
            display: block;
            margin-bottom: 7px;
            color: var(--ink-700);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, 0.1);
            color: var(--blue-850);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .input-wrap .form-control {
            height: 56px;
            padding: 14px 50px 14px 58px;
            border-radius: 18px;
            border: 1px solid #d8e7ff;
            background: #f8fbff;
            color: var(--ink-950);
            font-size: 0.93rem;
            font-weight: 600;
            box-shadow: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .input-wrap .form-control:focus {
            background: var(--white);
            border-color: var(--blue-700);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .input-wrap .form-control::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 12px;
            background: transparent;
            color: var(--ink-500);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .password-toggle:hover {
            background: rgba(37, 99, 235, 0.08);
            color: var(--blue-850);
        }

        .btn-login {
            width: 100%;
            height: 56px;
            border: 0;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-800) 52%, var(--blue-600) 100%);
            color: var(--white);
            font-size: 0.95rem;
            font-weight: 800;
            letter-spacing: -0.015em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 18px 28px rgba(37, 99, 235, 0.24);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 34px rgba(37, 99, 235, 0.28);
            filter: saturate(1.04);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--ink-200);
        }

        .login-footer a {
            color: var(--blue-850);
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .login-footer a:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .login-footer span {
            color: var(--ink-500);
            font-size: 0.76rem;
        }

        .hero-panel {
            grid-column: 1;
            grid-row: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 18px;
            padding: 30px 30px 26px;
            color: var(--white);
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.12), transparent 30%),
                linear-gradient(160deg, var(--blue-980) 0%, var(--blue-950) 24%, var(--blue-900) 58%, var(--blue-800) 100%);
            overflow: hidden;
        }

        .hero-panel--image {
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 42px 42px;
            opacity: 0.22;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.76), transparent 88%);
            -webkit-mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.76), transparent 88%);
            pointer-events: none;
        }

        .hero-panel--image::before {
            background: linear-gradient(180deg, rgba(8, 23, 52, 0.22) 0%, rgba(8, 23, 52, 0.48) 42%, rgba(8, 23, 52, 0.76) 100%);
            opacity: 1;
            mask-image: none;
            -webkit-mask-image: none;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            right: -180px;
            bottom: -220px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .hero-panel--image::after {
            width: 520px;
            height: 520px;
            right: -220px;
            bottom: -260px;
            background: rgba(96, 165, 250, 0.18);
        }

        .hero-copy,
        .hero-dashboard {
            position: relative;
            z-index: 1;
        }

        .hero-image-copy {
            position: relative;
            z-index: 1;
            max-width: 540px;
            margin-top: auto;
        }

        .hero-image-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            margin-bottom: 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(12px);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .hero-image-badge i {
            font-size: 0.88rem;
        }

        .hero-image-title {
            margin: 0 0 10px;
            font-family: var(--font-heading);
            font-size: clamp(2.1rem, 3.8vw, 3.35rem);
            line-height: 1.02;
            letter-spacing: -0.05em;
            color: var(--white);
        }

        .hero-image-note {
            margin: 0;
            max-width: 420px;
            color: rgba(255, 255, 255, 0.84);
            font-size: 0.94rem;
            line-height: 1.6;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            margin-bottom: 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(10px);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .hero-kicker i {
            font-size: 0.88rem;
        }

        .hero-copy h2 {
            max-width: 600px;
            margin-bottom: 10px;
            font-family: var(--font-heading);
            font-size: clamp(2.2rem, 3.8vw, 3.45rem);
            line-height: 1.02;
            letter-spacing: -0.05em;
        }

        .hero-copy p {
            max-width: 560px;
            margin-bottom: 18px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .hero-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hero-tags span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.92);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .hero-tags i {
            color: #bfdbfe;
        }

        .dashboard-frame {
            position: relative;
            padding: 16px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 24px 44px rgba(5, 18, 47, 0.2);
            backdrop-filter: blur(12px);
        }

        .floating-card {
            position: absolute;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.96);
            color: var(--ink-700);
            box-shadow: 0 16px 28px rgba(8, 27, 71, 0.18);
            z-index: 2;
        }

        .floating-card i {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--blue-850), var(--blue-600));
            color: var(--white);
            font-size: 0.9rem;
        }

        .floating-card strong {
            display: block;
            font-size: 0.83rem;
            color: var(--blue-950);
        }

        .floating-card span {
            display: block;
            font-size: 0.72rem;
            color: var(--ink-500);
        }

        .floating-card.top {
            top: -14px;
            right: 18px;
        }

        .floating-card.bottom {
            left: 18px;
            bottom: -16px;
        }

        .dashboard-board {
            padding: 16px;
            min-height: 330px;
            border-radius: 24px;
            background: linear-gradient(180deg, #f8fbff 0%, #edf4ff 100%);
            color: var(--ink-950);
        }

        .dashboard-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .window-meta {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .window-dots {
            display: flex;
            gap: 6px;
        }

        .window-dots span {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #c7ddff;
        }

        .board-name {
            font-size: 0.84rem;
            font-weight: 800;
            color: var(--blue-950);
        }

        .board-chip {
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--blue-050);
            color: var(--blue-900);
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .snapshot-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 12px;
        }

        .snapshot-card {
            padding: 12px;
            border-radius: 18px;
            background: var(--white);
            border: 1px solid #dbeafe;
            box-shadow: 0 12px 20px rgba(37, 99, 235, 0.06);
        }

        .snapshot-card .label {
            display: block;
            margin-bottom: 6px;
            color: var(--ink-500);
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .snapshot-card strong {
            display: block;
            margin-bottom: 6px;
            font-family: var(--font-heading);
            font-size: 1rem;
            letter-spacing: -0.04em;
            color: var(--blue-950);
        }

        .snapshot-bar {
            height: 6px;
            border-radius: 999px;
            overflow: hidden;
            background: #e3efff;
        }

        .snapshot-bar span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(135deg, var(--blue-800), var(--blue-600));
        }

        .dashboard-columns {
            display: grid;
            grid-template-columns: 1.08fr 0.92fr;
            gap: 10px;
        }

        .board-card {
            padding: 14px;
            border-radius: 18px;
            background: var(--white);
            border: 1px solid #dbeafe;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.06);
        }

        .board-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .board-card-header strong {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--blue-950);
        }

        .board-card-header span {
            color: var(--ink-500);
            font-size: 0.73rem;
            font-weight: 700;
        }

        .session-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-top: 1px solid #edf4ff;
        }

        .session-row:first-of-type {
            padding-top: 0;
            border-top: 0;
        }

        .session-time {
            min-width: 68px;
            padding: 7px 8px;
            border-radius: 12px;
            background: var(--blue-050);
            color: var(--blue-900);
            font-size: 0.72rem;
            font-weight: 800;
            text-align: center;
        }

        .session-copy strong,
        .activity-copy strong {
            display: block;
            margin-bottom: 2px;
            font-size: 0.83rem;
            color: var(--ink-950);
        }

        .session-copy span,
        .activity-copy span {
            display: block;
            color: var(--ink-500);
            font-size: 0.72rem;
            line-height: 1.35;
        }

        .activity-list {
            display: grid;
            gap: 10px;
        }

        .activity-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 14px;
            background: #f8fbff;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--blue-850), var(--blue-600));
            color: var(--white);
            font-size: 0.86rem;
            box-shadow: 0 10px 18px rgba(59, 130, 246, 0.16);
            flex-shrink: 0;
        }

        @media (min-width: 993px) and (max-height: 850px) {
            .page-shell {
                padding: 12px;
            }

            .login-shell {
                min-height: calc(100vh - 24px);
            }

            .login-panel {
                padding: 18px 16px;
            }

            .form-card {
                padding: 24px 22px 20px;
            }

            .hero-panel {
                padding: 24px 24px 20px;
            }

            .hero-copy h2 {
                font-size: clamp(2rem, 3.2vw, 2.8rem);
            }

            .hero-copy p {
                margin-bottom: 14px;
                font-size: 0.9rem;
            }

            .dashboard-frame {
                padding: 14px;
            }

            .dashboard-board {
                min-height: 304px;
                padding: 14px;
            }
        }

        @media (max-width: 1100px) {
            .login-shell {
                grid-template-columns: minmax(0, 1fr) minmax(330px, 390px);
            }

            .hero-panel {
                padding: 26px 24px 22px;
            }
        }

        @media (max-width: 992px) {
            .page-shell {
                padding: 18px;
            }

            .login-shell {
                min-height: auto;
                grid-template-columns: 1fr;
                max-width: 760px;
            }

            .login-panel {
                grid-column: auto;
                padding: 22px 20px 0;
            }

            .hero-panel {
                grid-column: auto;
                grid-row: auto;
                padding: 22px 20px 22px;
            }

            .hero-copy h2 {
                font-size: 2.2rem;
            }

            .snapshot-grid,
            .dashboard-columns {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .page-shell {
                padding: 0;
            }

            .login-shell {
                min-height: 100vh;
                border: 0;
                border-radius: 0;
                box-shadow: none;
                background: linear-gradient(180deg, var(--blue-950) 0px, var(--blue-900) 170px, #eef5ff 170px, #f8fbff 100%);
                backdrop-filter: none;
            }

            .login-panel {
                align-items: flex-start;
                padding: 18px 16px 20px;
            }

            .form-card {
                max-width: none;
                padding: 24px 18px 18px;
                border-radius: 26px;
                box-shadow: 0 20px 42px rgba(15, 23, 42, 0.14);
            }

            .brand-row {
                gap: 12px;
            }

            .brand-icon {
                width: 48px;
                height: 48px;
                border-radius: 16px;
                font-size: 1.15rem;
            }

            .brand-copy strong {
                font-size: 1.24rem;
            }

            .form-card h1 {
                font-size: 1.65rem;
            }

            .login-subtitle {
                margin-bottom: 14px;
                font-size: 0.86rem;
            }

            .hero-panel {
                display: none;
            }

            .login-footer span {
                display: none;
            }

            .login-footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <?= render_notifications() ?>

    <div class="page-shell">
        <div class="login-shell">
            <aside class="login-panel">
                <div class="form-card">
                    <div class="brand-row">
                        <div class="brand-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div class="brand-copy">
                            <small>BlueCampus</small>
                            <strong>LMS Portal</strong>
                        </div>
                    </div>

                    <h1>Welcome Back</h1>
                    <p class="login-subtitle">Sign in to continue in BlueCampus.</p>

                    <form action="<?= site_url('auth/login') ?>" method="post" autocomplete="off">
                        <div class="field-group">
                            <label class="field-label" for="email">Email Address</label>
                            <div class="input-wrap">
                                <span class="input-icon"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" autocomplete="email" required autofocus>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="password">Password</label>
                            <div class="input-wrap">
                                <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login to My Account
                        </button>
                    </form>

                    <div class="login-footer">
                        <a href="<?= site_url('auth/forgot_password') ?>">Forgot password?</a>
                        <span>Need help? Contact your school administrator.</span>
                    </div>
                </div>
            </aside>

            <?php if ($has_custom_login_image): ?>
                <section class="hero-panel hero-panel--image" style="background-image: url('<?= htmlspecialchars($login_image_url, ENT_QUOTES, 'UTF-8') ?>');">
                    <div class="hero-image-copy">
                        <div class="hero-image-badge">
                            <i class="bi bi-image-fill"></i>
                            BlueCampus
                        </div>
                        <h2 class="hero-image-title">A custom welcome screen for your platform.</h2>
                        <p class="hero-image-note">This login image is managed from the BlueCampus super admin settings.</p>
                    </div>
                </section>
            <?php else: ?>
                <section class="hero-panel">
                    <div class="hero-copy">
                        <div class="hero-kicker">
                            <i class="bi bi-grid-1x2-fill"></i>
                            BlueCampus
                        </div>

                        <h2>A cleaner space for learning.</h2>
                        <p>Courses, lessons, and grades in one place.</p>

                        <div class="hero-tags">
                            <span><i class="bi bi-journal-check"></i> Courses</span>
                            <span><i class="bi bi-journal-richtext"></i> Lessons</span>
                            <span><i class="bi bi-bar-chart-line"></i> Grades</span>
                        </div>
                    </div>

                    <div class="hero-dashboard">
                        <div class="dashboard-frame">
                            <div class="floating-card top">
                                <i class="bi bi-clipboard-check"></i>
                                <div>
                                    <strong>BlueCampus preview</strong>
                                    <span>Sample layout</span>
                                </div>
                            </div>

                            <div class="dashboard-board">
                                <div class="dashboard-top">
                                    <div class="window-meta">
                                        <div class="window-dots">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="board-name">Workspace</div>
                                    </div>
                                    <div class="board-chip">BlueCampus</div>
                                </div>

                                <div class="snapshot-grid">
                                    <div class="snapshot-card">
                                        <span class="label">Courses</span>
                                        <strong>Structured</strong>
                                        <div class="snapshot-bar"><span style="width: 82%;"></span></div>
                                    </div>
                                    <div class="snapshot-card">
                                        <span class="label">Lessons</span>
                                        <strong>Organized</strong>
                                        <div class="snapshot-bar"><span style="width: 74%;"></span></div>
                                    </div>
                                    <div class="snapshot-card">
                                        <span class="label">Grades</span>
                                        <strong>Visible</strong>
                                        <div class="snapshot-bar"><span style="width: 58%;"></span></div>
                                    </div>
                                </div>

                                <div class="dashboard-columns">
                                    <div class="board-card">
                                        <div class="board-card-header">
                                            <strong>Core Modules</strong>
                                            <span>LMS</span>
                                        </div>

                                        <div class="session-row">
                                            <div class="session-time">01</div>
                                            <div class="session-copy">
                                                <strong>Course Spaces</strong>
                                                <span>Subjects and class content.</span>
                                            </div>
                                        </div>

                                        <div class="session-row">
                                            <div class="session-time">02</div>
                                            <div class="session-copy">
                                                <strong>Learning Materials</strong>
                                                <span>Modules and resources.</span>
                                            </div>
                                        </div>

                                        <div class="session-row">
                                            <div class="session-time">03</div>
                                            <div class="session-copy">
                                                <strong>Grade Monitoring</strong>
                                                <span>Performance overview.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="board-card">
                                        <div class="board-card-header">
                                            <strong>Portal Highlights</strong>
                                            <span>Preview</span>
                                        </div>

                                        <div class="activity-list">
                                            <div class="activity-row">
                                                <div class="activity-icon"><i class="bi bi-journal-text"></i></div>
                                                <div class="activity-copy">
                                                    <strong>Lesson-ready layout</strong>
                                                    <span>Built for course delivery.</span>
                                                </div>
                                            </div>

                                            <div class="activity-row">
                                                <div class="activity-icon"><i class="bi bi-megaphone-fill"></i></div>
                                                <div class="activity-copy">
                                                    <strong>BlueCampus access</strong>
                                                    <span>Cleaner sign-in flow.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="floating-card bottom">
                                <i class="bi bi-bar-chart-steps"></i>
                                <div>
                                    <strong>Modern theme</strong>
                                    <span>Visual concept</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (!input || !icon) {
                return;
            }

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
