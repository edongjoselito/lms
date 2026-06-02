<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - BlueCampus</title>

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
            --card-radius: 28px;
            --card-shadow: 0 24px 60px rgba(37, 99, 235, 0.14);
        }

        * {
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
            margin: 0;
            font-family: var(--font-body);
            color: var(--ink-950);
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.28), transparent 28%),
                radial-gradient(circle at bottom right, rgba(96, 165, 250, 0.22), transparent 30%),
                linear-gradient(145deg, #edf5ff 0%, #dde9ff 48%, #f8fbff 100%);
            overflow-x: hidden;
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 24px 18px;
        }

        .signup-container {
            width: 100%;
            max-width: 1320px;
        }

        .form-card {
            padding: 38px 36px;
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
            display: inline-flex;
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

        .form-title {
            margin-bottom: 8px;
            font-family: var(--font-heading);
            font-size: clamp(1.55rem, 2.5vw, 2rem);
            line-height: 1.1;
            letter-spacing: -0.05em;
            color: var(--blue-950);
        }

        .signup-subtitle {
            margin-bottom: 24px;
            color: var(--ink-500);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .form-section {
            margin-bottom: 24px;
            padding: 22px;
            border: 1px solid var(--ink-200);
            border-radius: 22px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .form-section:last-of-type {
            margin-bottom: 16px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            font-size: 0.84rem;
            font-weight: 800;
            color: var(--blue-900);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .section-title i {
            font-size: 1rem;
            color: var(--blue-700);
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

        .required {
            color: #dc2626;
        }

        .input-wrap .form-control,
        .input-wrap .form-select {
            min-height: 48px;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #d8e7ff;
            background-color: #f8fbff;
            color: var(--ink-950);
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .input-wrap textarea.form-control {
            min-height: 90px;
            resize: vertical;
        }

        .input-wrap .form-control:focus,
        .input-wrap .form-select:focus {
            background-color: var(--white);
            border-color: var(--blue-700);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .input-wrap .form-control::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }

        .help-text,
        .field-message {
            display: block;
            margin-top: 5px;
            font-size: 0.75rem;
        }

        .captcha-panel {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px;
            border-radius: 16px;
            border: 1px dashed #b9d6ff;
            background: #f8fbff;
        }

        .captcha-image {
            max-width: 100%;
            border-radius: 12px;
            border: 2px solid #d8e7ff;
            background: #ffffff;
        }

        .captcha-refresh {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--blue-100);
            color: var(--blue-850);
            font-size: 1.25rem;
            transition: transform 0.18s ease, background 0.18s ease;
        }

        .captcha-refresh:hover {
            transform: rotate(25deg);
            background: #cfe4ff;
        }

        .btn-submit {
            width: 100%;
            min-height: 54px;
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

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 34px rgba(37, 99, 235, 0.28);
            filter: saturate(1.04);
        }

        .signup-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--ink-200);
        }

        .signup-footer a {
            color: var(--blue-850);
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .signup-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 767.98px) {
            .page-shell {
                padding: 0;
            }

            .signup-container {
                max-width: none;
            }

            .form-card {
                min-height: 100vh;
                border-radius: 0;
                box-shadow: none;
                padding: 24px 18px;
            }

            .form-section {
                padding: 18px 14px;
                border-radius: 18px;
            }

            .captcha-panel {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <?= render_notifications() ?>

    <?php
    if (!function_exists('h')) {
        function h($value)
        {
            return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        }
    }

    $form_data = isset($form_data) && is_array($form_data) ? $form_data : [];

    function old_value($field, $default = '')
    {
        global $form_data;
        return isset($form_data[$field]) ? h($form_data[$field]) : h($default);
    }

    function old_selected($field, $value)
    {
        global $form_data;
        return (isset($form_data[$field]) && (string) $form_data[$field] === (string) $value) ? 'selected' : '';
    }

    $school_types = [
        'deped' => 'DepEd (K-12)',
        'ched'  => 'CHED (Higher Ed)',
        'tesda' => 'TESDA (Tech-Voc)',
        'both'  => 'All (K-12, CHED, TESDA)',
    ];

    $selected_school_type = isset($form_data['type']) && $form_data['type'] !== ''
        ? (string) $form_data['type']
        : 'deped';

    $districts = [
        'Boston District',
        'Cateel 1',
        'Cateel 2',
        'Baganga North',
        'Baganga South',
        'Caraga North',
        'Caraga South',
        'Manay North',
        'Manay South',
        'Tarragona',
        'San Isidro North',
        'San Isidro South',
        'Gov. Gen North',
        'Gov. Gen South',
        'Lupon West',
        'Lupon East',
        'Banaybanay',
    ];
    ?>

    <main class="page-shell">
        <div class="signup-container">
            <div class="form-card">
                <header class="brand-row">
                    <div class="brand-icon" aria-hidden="true">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div class="brand-copy">
                        <small>BlueCampus</small>
                        <strong>LMS Portal</strong>
                    </div>
                </header>

                <h1 class="form-title">Sign Up for School Account</h1>
                <p class="signup-subtitle">Register your school to get started with BlueCampus LMS. Fields marked with <span class="required">*</span> are required.</p>

                <?= form_open('auth/create_school', ['autocomplete' => 'off', 'id' => 'schoolSignupForm']) ?>

                <section class="form-section" aria-labelledby="schoolInfoTitle">
                    <h2 class="section-title" id="schoolInfoTitle">
                        <i class="bi bi-building"></i>
                        School Information
                    </h2>

                    <div class="row g-3">
                        <div class="col-12 col-lg-6 field-group">
                            <label class="field-label" for="schoolName">School Name <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="name" id="schoolName" required oninput="this.value = this.value.toUpperCase()" value="<?= old_value('name') ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 field-group">
                            <label class="field-label" for="schoolType">School Type <span class="required">*</span></label>
                            <div class="input-wrap">
                                <select class="form-select" name="type" id="schoolType" required>
                                    <option value="">Select type</option>
                                    <?php foreach ($school_types as $value => $label): ?>
                                        <option value="<?= h($value) ?>" <?= $selected_school_type === (string) $value ? 'selected' : '' ?>><?= h($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3 field-group">
                            <label class="field-label" for="schoolIdNumber">School ID Number <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="school_id_number" id="schoolIdNumber" required value="<?= old_value('school_id_number') ?>">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-section" aria-labelledby="locationTitle">
                    <h2 class="section-title" id="locationTitle">
                        <i class="bi bi-geo-alt"></i>
                        Location
                    </h2>

                    <div class="row g-3">
                        <div class="col-12 col-md-4 field-group">
                            <label class="field-label" for="district">District</label>
                            <div class="input-wrap">
                                <select class="form-select" name="district" id="district">
                                    <option value="">Select district</option>
                                    <?php foreach ($districts as $district): ?>
                                        <option value="<?= h($district) ?>" <?= old_selected('district', $district) ?>><?= h($district) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 field-group">
                            <label class="field-label" for="division">Division</label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="division" id="division" value="Davao Oriental" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 field-group">
                            <label class="field-label" for="region">Region</label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="region" id="region" value="XI" readonly>
                            </div>
                        </div>

                        <div class="col-12 field-group">
                            <label class="field-label" for="address">Complete Address</label>
                            <div class="input-wrap">
                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Street, barangay, municipality/city, province"><?= old_value('address') ?></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-section" aria-labelledby="accountSetupTitle">
                    <h2 class="section-title" id="accountSetupTitle">
                        <i class="bi bi-person-lock"></i>
                        Account Setup
                    </h2>

                    <div class="row g-3">
                        <div class="col-12 col-lg-6 field-group">
                            <label class="field-label" for="emailInput">Email Address <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="email" class="form-control" name="email" id="emailInput" required value="<?= old_value('email') ?>">
                            </div>
                            <small id="emailMessage" class="field-message"></small>
                            <small class="help-text text-muted">A confirmation link will be sent to this email.</small>
                        </div>

                        <div class="col-12 col-lg-6 field-group">
                            <label class="field-label" for="contactNumber">Contact Number</label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="contact_number" id="contactNumber" value="<?= old_value('contact_number') ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 field-group">
                            <label class="field-label" for="passwordInput">Password <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="password" class="form-control" name="password" id="passwordInput" required minlength="8" placeholder="At least 8 characters" value="<?= old_value('password') ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 field-group">
                            <label class="field-label" for="confirmPasswordInput">Confirm Password <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="password" class="form-control" name="confirm_password" id="confirmPasswordInput" required minlength="8" placeholder="Re-enter your password" value="<?= old_value('confirm_password') ?>">
                            </div>
                            <small id="passwordMessage" class="field-message"></small>
                        </div>
                    </div>
                </section>

                <section class="form-section" aria-labelledby="securityTitle">
                    <h2 class="section-title" id="securityTitle">
                        <i class="bi bi-shield-check"></i>
                        Security Verification
                    </h2>

                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-6 field-group">
                            <label class="field-label">Captcha Image</label>
                            <div class="captcha-panel">
                                <img src="<?= isset($captcha_image) ? $captcha_image : '' ?>" alt="Captcha" class="captcha-image" id="captchaImg">
                                <button type="button" class="captcha-refresh" onclick="refreshCaptcha()" title="Refresh captcha" aria-label="Refresh captcha">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 field-group">
                            <label class="field-label" for="captchaInput">Enter Captcha <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="captcha" id="captchaInput" required autocomplete="off">
                            </div>
                        </div>
                    </div>
                </section>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-person-plus"></i>
                    Create School Account
                </button>

                <?= form_close() ?>

                <footer class="signup-footer">
                    <a href="<?= site_url('auth') ?>">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </footer>
            </div>
        </div>
    </main>

    <script>
        function refreshCaptcha() {
            fetch('<?= site_url('auth/captcha_refresh') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data && data.image) {
                        document.getElementById('captchaImg').src = data.image;
                    }
                })
                .catch(error => console.error('Error refreshing captcha:', error));
        }

        const emailInput = document.getElementById('emailInput');
        const emailMessage = document.getElementById('emailMessage');
        let emailTimeout = null;

        emailInput.addEventListener('input', function () {
            clearTimeout(emailTimeout);
            const email = this.value.trim();

            if (email.length < 3) {
                emailMessage.textContent = '';
                return;
            }

            emailMessage.textContent = 'Checking...';
            emailMessage.style.color = '#64748b';

            emailTimeout = setTimeout(function () {
                const formData = new FormData();
                formData.append('email', email);

                fetch('<?= site_url('auth/check_email') ?>', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            emailMessage.textContent = 'Email already registered';
                            emailMessage.style.color = '#dc2626';
                        } else {
                            emailMessage.textContent = 'Email available';
                            emailMessage.style.color = '#16a34a';
                        }
                    })
                    .catch(error => {
                        console.error('Error checking email:', error);
                        emailMessage.textContent = '';
                    });
            }, 500);
        });

        const passwordInput = document.getElementById('passwordInput');
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');
        const passwordMessage = document.getElementById('passwordMessage');

        function validatePasswordMatch() {
            if (confirmPasswordInput.value.length === 0) {
                passwordMessage.textContent = '';
                return;
            }

            if (passwordInput.value !== confirmPasswordInput.value) {
                passwordMessage.textContent = 'Passwords do not match';
                passwordMessage.style.color = '#dc2626';
            } else {
                passwordMessage.textContent = 'Passwords match';
                passwordMessage.style.color = '#16a34a';
            }
        }

        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    </script>
</body>

</html>
