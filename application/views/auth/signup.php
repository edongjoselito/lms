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

        .page-shell {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 18px;
            z-index: 1;
        }

        .signup-container {
            width: 100%;
            max-width: 1400px;
        }

        .form-card {
            padding: 40px 36px;
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

        .signup-subtitle {
            margin-bottom: 24px;
            color: var(--ink-500);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .form-section {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--ink-200);
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--blue-900);
            margin-bottom: 16px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
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

        .field-label .required {
            color: #dc2626;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .form-control {
            height: 48px;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #d8e7ff;
            background: #f8fbff;
            color: var(--ink-950);
            font-size: 0.9rem;
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

        .row {
            display: flex;
            gap: 16px;
        }

        .col {
            flex: 1;
        }

        .captcha-section {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .captcha-image {
            border-radius: 12px;
            border: 2px solid #d8e7ff;
            background: white;
        }

        .captcha-refresh {
            color: var(--blue-700);
            cursor: pointer;
            font-size: 1.5rem;
            transition: color 0.18s ease;
        }

        .captcha-refresh:hover {
            color: var(--blue-900);
        }

        .btn-submit {
            width: 100%;
            height: 54px;
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
            margin-top: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 34px rgba(37, 99, 235, 0.28);
            filter: saturate(1.04);
        }

        .btn-submit:active {
            transform: translateY(0);
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

        @media (max-width: 640px) {
            .page-shell {
                padding: 0;
            }

            .signup-container {
                max-width: none;
            }

            .form-card {
                border-radius: 0;
                box-shadow: none;
                padding: 24px 18px;
            }

            .row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <?= render_notifications() ?>

    <div class="page-shell">
        <div class="signup-container">
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

                <h1>Sign Up for School Account</h1>
                <p class="signup-subtitle">Register your school to get started with BlueCampus LMS.</p>

                <?= form_open('auth/create_school', array('autocomplete' => 'off')) ?>
                    <!-- Row 1: Basic Information (3 elements) -->
                    <div class="row form-section">
                        <h6 class="section-title col-12">Basic Information</h6>
                        <div class="col-md-4 field-group">
                            <label class="field-label">School Name <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="name" required oninput="this.value = this.value.toUpperCase()" value="<?= isset($form_data['name']) ? htmlspecialchars($form_data['name']) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">School Type <span class="required">*</span></label>
                            <div class="input-wrap">
                                <select class="form-control" name="type" required>
                                    <option value="">Select type</option>
                                    <option value="deped" <?= isset($form_data['type']) && $form_data['type'] == 'deped' ? 'selected' : '' ?>>DepEd (K-12)</option>
                                    <option value="ched" <?= isset($form_data['type']) && $form_data['type'] == 'ched' ? 'selected' : '' ?>>CHED (Higher Ed)</option>
                                    <option value="tesda" <?= isset($form_data['type']) && $form_data['type'] == 'tesda' ? 'selected' : '' ?>>TESDA (Tech-Voc)</option>
                                    <option value="both" <?= isset($form_data['type']) && $form_data['type'] == 'both' ? 'selected' : '' ?>>All (K-12, CHED, TESDA)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">School ID Number <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="school_id_number" required value="<?= isset($form_data['school_id_number']) ? htmlspecialchars($form_data['school_id_number']) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Contact & Location (3 elements) -->
                    <div class="row form-section">
                        <h6 class="section-title col-12">Contact & Location</h6>
                        <div class="col-md-4 field-group">
                            <label class="field-label">Contact Number</label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="contact_number" value="<?= isset($form_data['contact_number']) ? htmlspecialchars($form_data['contact_number']) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">District</label>
                            <div class="input-wrap">
                                <select class="form-control" name="district">
                                    <option value="">Select district</option>
                                    <option value="Baganga District" <?= isset($form_data['district']) && $form_data['district'] == 'Baganga District' ? 'selected' : '' ?>>Baganga District</option>
                                    <option value="Boston District" <?= isset($form_data['district']) && $form_data['district'] == 'Boston District' ? 'selected' : '' ?>>Boston District</option>
                                    <option value="Cateel District" <?= isset($form_data['district']) && $form_data['district'] == 'Cateel District' ? 'selected' : '' ?>>Cateel District</option>
                                    <option value="Caraga District" <?= isset($form_data['district']) && $form_data['district'] == 'Caraga District' ? 'selected' : '' ?>>Caraga District</option>
                                    <option value="Manay District" <?= isset($form_data['district']) && $form_data['district'] == 'Manay District' ? 'selected' : '' ?>>Manay District</option>
                                    <option value="Tarragona District" <?= isset($form_data['district']) && $form_data['district'] == 'Tarragona District' ? 'selected' : '' ?>>Tarragona District</option>
                                    <option value="Lupon District" <?= isset($form_data['district']) && $form_data['district'] == 'Lupon District' ? 'selected' : '' ?>>Lupon District</option>
                                    <option value="Banaybanay District" <?= isset($form_data['district']) && $form_data['district'] == 'Banaybanay District' ? 'selected' : '' ?>>Banaybanay District</option>
                                    <option value="San Isidro District" <?= isset($form_data['district']) && $form_data['district'] == 'San Isidro District' ? 'selected' : '' ?>>San Isidro District</option>
                                    <option value="Governor Generoso District" <?= isset($form_data['district']) && $form_data['district'] == 'Governor Generoso District' ? 'selected' : '' ?>>Governor Generoso District</option>
                                    <option value="Mati City District" <?= isset($form_data['district']) && $form_data['district'] == 'Mati City District' ? 'selected' : '' ?>>Mati City District</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">Division</label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="division" value="Davao Oriental" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Location & Email (3 elements) -->
                    <div class="row form-section">
                        <div class="col-md-4 field-group">
                            <label class="field-label">Region</label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="region" value="XI" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">Email Address <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="email" class="form-control" name="email" id="emailInput" required value="<?= isset($form_data['email']) ? htmlspecialchars($form_data['email']) : '' ?>">
                            </div>
                            <small id="emailMessage" style="font-size: 0.75rem; margin-top: 4px; display: block;"></small>
                            <small class="text-muted" style="font-size: 0.75rem; margin-top: 4px; display: block;">Confirmation link will be sent to this email</small>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">Complete Address</label>
                            <div class="input-wrap">
                                <textarea class="form-control" name="address" rows="3"><?= isset($form_data['address']) ? htmlspecialchars($form_data['address']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Account Setup (3 elements) -->
                    <div class="row form-section">
                        <h6 class="section-title col-12">Account Setup</h6>
                        <div class="col-md-4 field-group">
                            <label class="field-label">Password <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="password" class="form-control" name="password" id="passwordInput" required minlength="8" placeholder="At least 8 characters">
                            </div>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">Confirm Password <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="password" class="form-control" name="confirm_password" id="confirmPasswordInput" required minlength="8" placeholder="Re-enter your password">
                            </div>
                            <small id="passwordMessage" style="font-size: 0.75rem; margin-top: 4px; display: block;"></small>
                        </div>
                        <div class="col-md-4 field-group">
                            <label class="field-label">Captcha <span class="required">*</span></label>
                            <div class="captcha-section">
                                <img src="<?= $captcha_image ?>" alt="Captcha" class="captcha-image" id="captchaImg">
                                <i class="bi bi-arrow-clockwise captcha-refresh" onclick="refreshCaptcha()" title="Refresh captcha"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Row 5: Captcha Input (Full Width) -->
                    <div class="row form-section">
                        <div class="col-md-4 field-group">
                            <label class="field-label">Enter Captcha <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="text" class="form-control" name="captcha" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-person-plus"></i>
                        Create School Account
                    </button>
                <?= form_close() ?>

                <div class="signup-footer">
                    <a href="<?= site_url('auth') ?>">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function refreshCaptcha() {
            fetch('<?= site_url('auth/captcha_refresh') ?>')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captchaImg').src = data.image;
                })
                .catch(error => console.error('Error refreshing captcha:', error));
        }

        let emailTimeout;
        document.getElementById('emailInput').addEventListener('input', function() {
            clearTimeout(emailTimeout);
            const email = this.value;
            const messageEl = document.getElementById('emailMessage');

            if (email.length < 3) {
                messageEl.textContent = '';
                return;
            }

            messageEl.textContent = 'Checking...';
            messageEl.style.color = '#6c757d';

            emailTimeout = setTimeout(function() {
                const formData = new FormData();
                formData.append('email', email);

                fetch('<?= site_url('auth/check_email') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        messageEl.textContent = 'Email already registered';
                        messageEl.style.color = '#dc3545';
                    } else {
                        messageEl.textContent = 'Email available';
                        messageEl.style.color = '#28a745';
                    }
                })
                .catch(error => {
                    console.error('Error checking email:', error);
                    messageEl.textContent = '';
                });
            }, 500);
        });

        // Password confirmation validation
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
                passwordMessage.style.color = '#dc3545';
            } else {
                passwordMessage.textContent = 'Passwords match';
                passwordMessage.style.color = '#28a745';
            }
        }

        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    </script>
</body>

</html>
