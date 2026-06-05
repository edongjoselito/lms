<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
    $is_edit     = !empty($user);
    $form_action = $is_edit ? 'users/edit/' . $user->id : 'users/create';

    function ps_value($object, $field)
    {
        return (!empty($object) && isset($object->$field)) ? htmlspecialchars($object->$field, ENT_QUOTES, 'UTF-8') : '';
    }
?>

<div class="ps-page">

    <!-- BACK BUTTON -->
    <a href="<?= site_url('users') ?>" class="ps-back">
        <i class="bi bi-arrow-left-short"></i>
        Back to Users
    </a>

    <!-- PAGE HEADER -->
    <div class="ps-hero">
        <div class="ps-hero-bg"></div>

        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">
                    <?= $is_edit ? 'EU' : 'NU' ?>
                </div>

                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Users</span>
                        <span class="ps-tag ps-tag-code"><?= $is_edit ? 'Edit' : 'Create' ?></span>
                    </div>

                    <h1 class="ps-hero-title">
                        <?= $is_edit ? 'Edit User' : 'Add New User' ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="ps-layout ps-layout-full">
        <div class="ps-card">

            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-<?= $is_edit ? 'pencil-square' : 'person-plus-fill' ?>"></i>
                    <span><?= $is_edit ? 'Edit User' : 'User Information' ?></span>
                </div>
            </div>

            <div class="ps-card-body">

                <?= form_open($form_action) ?>

                    <!-- PERSONAL INFORMATION -->
                    <div class="ps-form-section">
                        <h6 class="ps-section-title">
                            <i class="bi bi-person me-2"></i>
                            Personal Information
                        </h6>

                        <div class="row g-3">

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="first_name"
                                       class="ps-form-control"
                                       value="<?= ps_value($user, 'first_name') ?>"
                                       required>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">
                                    Middle Name <span class="text-muted">(Optional)</span>
                                </label>
                                <input type="text"
                                       name="middle_name"
                                       class="ps-form-control"
                                       value="<?= ps_value($user, 'middle_name') ?>">
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="last_name"
                                       class="ps-form-control"
                                       value="<?= ps_value($user, 'last_name') ?>"
                                       required>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">
                                    Suffix <span class="text-muted">(Optional)</span>
                                </label>
                                <input type="text"
                                       name="suffix"
                                       class="ps-form-control"
                                       value="<?= ps_value($user, 'suffix') ?>">
                            </div>

                        </div>
                    </div>

                    <!-- CONTACT AND SECURITY -->
                    <div class="ps-form-section">
                        <h6 class="ps-section-title">
                            <i class="bi bi-shield-lock me-2"></i>
                            Contact & Security
                        </h6>

                        <div class="row g-3">

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                       name="email"
                                       class="ps-form-control"
                                       value="<?= ps_value($user, 'email') ?>"
                                       required>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">Phone Number</label>
                                <input type="text"
                                       name="phone"
                                       class="ps-form-control"
                                       value="<?= ps_value($user, 'phone') ?>">
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">
                                    Password
                                    <?php if ($is_edit): ?>
                                        <span class="text-muted">(Optional)</span>
                                    <?php else: ?>
                                        <span class="text-danger">*</span>
                                    <?php endif; ?>
                                </label>

                                <div class="ps-input-group">
                                    <input type="password"
                                           name="password"
                                           id="userPassword"
                                           class="ps-form-control"
                                           minlength="6"
                                           <?= $is_edit ? '' : 'required' ?>>

                                    <button type="button"
                                            class="ps-input-btn"
                                            onclick="toggleUserPasswordVisibility()"
                                            id="toggleUserPasswordBtn">
                                        <i class="bi bi-eye" id="toggleUserPasswordIcon"></i>
                                    </button>

                                    <button type="button"
                                            class="ps-input-btn ps-generate-btn"
                                            onclick="generateUserPassword()">
                                        <i class="bi bi-shuffle"></i>
                                        Generate
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="ps-form-label">
                                    Role <span class="text-danger">*</span>
                                </label>

                                <select name="role_id" class="ps-form-select" required>
                                    <option value="">Select role</option>

                                    <?php if (!empty($roles)): ?>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role->id ?>"
                                                <?= ($is_edit && $user->role_id == $role->id) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($role->name, ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- ACCOUNT STATUS -->
                    <div class="ps-form-section ps-form-section-last">
                        <div class="ps-status-box">

                            <div class="ps-status-left">
                                <i class="bi bi-toggle-on text-primary"></i>
                                <span>Account Status</span>
                            </div>

                            <div class="form-check form-switch m-0">
                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="form-check-input"
                                       id="statusSwitch"
                                       <?= (!$is_edit || !empty($user->status)) ? 'checked' : '' ?>>

                                <label class="form-check-label" for="statusSwitch">
                                    Account is active and user can log in
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- FORM ACTIONS -->
                    <div class="ps-form-actions">
                        <button type="submit" class="ps-submit-btn">
                            <i class="bi bi-check-lg"></i>
                            <?= $is_edit ? 'Update User' : 'Create User' ?>
                        </button>

                        <a href="<?= site_url('users') ?>" class="ps-cancel-btn">
                            Cancel
                        </a>
                    </div>

                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>

<style>
/* =========================================================
   PAGE WRAPPER
========================================================= */
.ps-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

/* =========================================================
   BACK BUTTON
========================================================= */
.ps-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 1.5rem;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}

.ps-back i {
    font-size: 1.1rem;
}

.ps-back:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
}

/* =========================================================
   HERO HEADER
========================================================= */
.ps-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37, 99, 235, 0.16);
}

.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.ps-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.ps-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}

.ps-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.ps-hero-avatar {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 1px;
}

.ps-hero-info {
    min-width: 0;
}

.ps-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.ps-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

/* =========================================================
   TAGS
========================================================= */
.ps-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.ps-tag-degree {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.ps-tag-code {
    background: rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.25);
}

/* =========================================================
   CARD LAYOUT
========================================================= */
.ps-layout {
    display: grid;
    gap: 1.5rem;
    align-items: start;
}

.ps-layout-full {
    grid-template-columns: 1fr;
}

.ps-card {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
}

.ps-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    flex-wrap: wrap;
}

.ps-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.ps-card-title i {
    color: #2563eb;
    font-size: 1rem;
}

.ps-card-body {
    padding: 1.5rem;
}

/* =========================================================
   FORM SECTIONS
========================================================= */
.ps-form-section {
    margin-bottom: 1.75rem;
    padding-bottom: 1.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.ps-form-section-last {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.ps-section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #3b82f6;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    padding-bottom: 0.5rem;
}

.ps-section-title i {
    font-size: 0.95rem;
}

/* =========================================================
   FORM FIELDS
========================================================= */
.ps-form-label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #475569;
    margin-bottom: 0.5rem;
}

.ps-form-control,
.ps-form-select {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    color: #1e293b;
    background-color: #fff;
}

.ps-form-control:focus,
.ps-form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

/* =========================================================
   PASSWORD INPUT GROUP
========================================================= */
.ps-input-group {
    display: flex;
    align-items: stretch;
}

.ps-input-group .ps-form-control {
    border-radius: 8px 0 0 8px;
    border-right: none;
}

.ps-input-group .ps-form-control:focus {
    border-right: none;
}

.ps-input-btn {
    border: 1px solid #e2e8f0;
    border-left: none;
    background: #fff;
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
    color: #475569;
}

.ps-input-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
}

.ps-input-btn:first-of-type {
    border-radius: 0;
}

.ps-input-btn:last-of-type {
    border-radius: 0 8px 8px 0;
}

.ps-generate-btn {
    font-size: 0.82rem;
    font-weight: 600;
}

/* =========================================================
   STATUS SECTION
========================================================= */
.ps-status-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.ps-status-left {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #1e293b;
}

.form-check-input:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

/* =========================================================
   FORM ACTIONS
========================================================= */
.ps-form-actions {
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.ps-submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1.25rem;
    border-radius: 10px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    border: none;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
}

.ps-submit-btn:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.ps-cancel-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.6rem 1.25rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 500;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s;
}

.ps-cancel-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
    text-decoration: none;
}

/* =========================================================
   HELPERS
========================================================= */
.text-muted {
    font-size: 0.8rem;
    color: #94a3b8 !important;
}

/* =========================================================
   RESPONSIVE
========================================================= */
@media (max-width: 768px) {
    .ps-hero-content {
        padding: 1.5rem;
    }

    .ps-hero-left {
        align-items: flex-start;
    }

    .ps-hero-avatar {
        width: 58px;
        height: 58px;
        font-size: 1.15rem;
    }

    .ps-card-body {
        padding: 1rem;
    }

    .ps-input-group {
        flex-wrap: wrap;
    }

    .ps-input-group .ps-form-control {
        flex: 1 1 100%;
        border-right: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .ps-input-btn {
        border: 1px solid #e2e8f0;
    }

    .ps-input-btn:first-of-type {
        border-radius: 8px 0 0 8px;
    }

    .ps-input-btn:last-of-type {
        border-radius: 0 8px 8px 0;
    }
}
</style>

<script>
function generateUserPassword() {
    const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    let password = '';

    for (let i = 0; i < 12; i++) {
        password += characters.charAt(Math.floor(Math.random() * characters.length));
    }

    document.getElementById('userPassword').value = password;
}

function toggleUserPasswordVisibility() {
    const passwordInput = document.getElementById('userPassword');
    const toggleIcon = document.getElementById('toggleUserPasswordIcon');

    if (!passwordInput || !toggleIcon) {
        return;
    }

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>