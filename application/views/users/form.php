<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title"><?= ($user) ? 'Edit User' : 'Add New User' ?></h1>
        <p class="hero-subtitle"><?= ($user) ? 'Update user account details and permissions' : 'Create a new user account for your institution' ?></p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('users') ?>" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Back to Users
            </a>
        </div>

        <div class="form-card">
            <h5 class="form-card-title">
                <i class="bi bi-<?= ($user) ? 'pencil-square' : 'person-plus-fill' ?> me-2"></i>
                <?= ($user) ? 'Edit User' : 'User Information' ?>
            </h5>

            <?= form_open(($user) ? 'users/edit/' . $user->id : 'users/create') ?>
                <!-- Personal Information -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-person me-2"></i>Personal Information</h6>
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name"
                                value="<?= ($user) ? htmlspecialchars($user->first_name) : '' ?>" required>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Middle Name <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" name="middle_name"
                                value="<?= ($user && isset($user->middle_name)) ? htmlspecialchars($user->middle_name) : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name"
                                value="<?= ($user) ? htmlspecialchars($user->last_name) : '' ?>" required>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Suffix <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" name="suffix"
                                value="<?= ($user && isset($user->suffix)) ? htmlspecialchars($user->suffix) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact & Security -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-shield-lock me-2"></i>Contact & Security</h6>
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email"
                                value="<?= ($user) ? htmlspecialchars($user->email) : '' ?>" required>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone"
                                value="<?= ($user && isset($user->phone)) ? htmlspecialchars($user->phone) : '' ?>">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">
                                Password <?= ($user) ? '<span class="text-muted">(Optional)</span>' : '<span class="text-danger">*</span>' ?>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="userPassword"
                                    <?= ($user) ? '' : 'required' ?> minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleUserPasswordVisibility()" id="toggleUserPasswordBtn">
                                    <i class="bi bi-eye" id="toggleUserPasswordIcon"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateUserPassword()">
                                    <i class="bi bi-shuffle"></i> Generate
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role_id" required>
                                <option value="">Select role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role->id ?>" <?= ($user && $user->role_id == $role->id) ? 'selected' : '' ?>><?= $role->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="form-section">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-toggle-on text-primary"></i>
                            <span class="fw-medium">Account Status</span>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch"
                                <?= (!$user || $user->status) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusSwitch">
                                Account is active and user can log in
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check-lg"></i>
                        <?= ($user) ? 'Update User' : 'Create User' ?>
                    </button>
                    <a href="<?= site_url('users') ?>" class="btn btn-light">Cancel</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<style>
    .dashboard-hero {
        margin-bottom: 1.25rem;
    }

    .hero-content {
        padding: 0;
    }

    .hero-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .hero-subtitle {
        color: #64748b;
        font-size: 0.875rem;
        margin: 0;
    }

    .back-link {
        color: #64748b;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #1e293b;
    }

    .form-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 2rem;
    }

    .form-card-title {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1e293b;
        font-size: 1.1rem;
    }

    .form-section {
        margin-bottom: 1.75rem;
        padding-bottom: 1.75rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #3b82f6;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        padding-bottom: 0.5rem;
    }

    .section-title i {
        font-size: 0.95rem;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-actions {
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 0.75rem;
    }

    .btn-light {
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.6rem 1.25rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
    }

    .btn-light:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }

    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .text-muted {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .input-group .btn-outline-secondary {
        border-left: none;
    }

    .input-group .btn-outline-secondary:first-of-type {
        border-radius: 0;
    }

    .input-group .btn-outline-secondary:last-of-type {
        border-radius: 0 8px 8px 0;
    }

    .input-group .form-control {
        border-radius: 8px 0 0 8px;
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
