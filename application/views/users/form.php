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

            <form action="<?= ($user) ? site_url('users/edit/' . $user->id) : site_url('users/create') ?>" method="post">
                <!-- Personal Information -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-person me-2"></i>Personal Information</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" placeholder="Enter first name"
                                value="<?= ($user) ? htmlspecialchars($user->first_name) : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name" placeholder="Optional"
                                value="<?= ($user && isset($user->middle_name)) ? htmlspecialchars($user->middle_name) : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" placeholder="Enter last name"
                                value="<?= ($user) ? htmlspecialchars($user->last_name) : '' ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Suffix</label>
                            <input type="text" class="form-control" name="suffix" placeholder="Jr., III"
                                value="<?= ($user && isset($user->suffix)) ? htmlspecialchars($user->suffix) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact & Security -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-shield-lock me-2"></i>Contact & Security</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="user@example.com"
                                value="<?= ($user) ? htmlspecialchars($user->email) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" placeholder="e.g., +63 912 345 6789"
                                value="<?= ($user && isset($user->phone)) ? htmlspecialchars($user->phone) : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Password <?= ($user) ? '<span class="text-muted">(leave blank to keep current)</span>' : '<span class="text-danger">*</span>' ?>
                            </label>
                            <input type="password" class="form-control" name="password" placeholder="<?= ($user) ? 'Enter new password' : 'Create password' ?>"
                                <?= ($user) ? '' : 'required' ?> minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role_id" required>
                                <option value="">Select a role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role->id ?>" <?= ($user && $user->role_id == $role->id) ? 'selected' : '' ?>><?= $role->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-toggle-on me-2"></i>Account Status</h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch"
                            <?= (!$user || $user->status) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusSwitch">
                            Account is active and user can log in
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check-lg"></i>
                        <?= ($user) ? 'Update User' : 'Create User' ?>
                    </button>
                    <a href="<?= site_url('users') ?>" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .dashboard-hero {
        margin-bottom: 2rem;
    }

    .hero-content {
        padding: 0.5rem 0;
    }

    .hero-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .hero-subtitle {
        color: #64748b;
        font-size: 0.95rem;
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
        color: #0f172a;
    }

    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .form-card-title {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #0f172a;
        font-size: 1.1rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0d9488;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        font-size: 1rem;
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
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
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
        color: #0f172a;
    }

    .form-check-input:checked {
        background-color: #0d9488;
        border-color: #0d9488;
    }

    .text-muted {
        font-size: 0.8rem;
        color: #94a3b8;
    }
</style>