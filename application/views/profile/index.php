<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title">My Profile</h1>
        <p class="hero-subtitle">Manage your account information and security settings</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-card">
            <h5 class="form-card-title">
                <i class="bi bi-person-circle me-2"></i>
                Personal Information
            </h5>
            <?php echo form_open('profile/update'); ?>
            <!-- Personal Information -->
            <div class="form-section">
                <h6 class="section-title"><i class="bi bi-person me-2"></i>Personal Information</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user->first_name) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user->last_name) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                    </div>
                </div>
            </div>

            <!-- Account Details -->
            <div class="form-section">
                <h6 class="section-title"><i class="bi bi-info-circle me-2"></i>Account Details</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user->role_name) ?>" readonly>
                        <small class="text-muted">Role is assigned by administrator</small>
                    </div>
                    <?php if ($user->school_name): ?>
                        <div class="col-md-6">
                            <label class="form-label">School</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user->school_name) ?>" readonly>
                            <small class="text-muted">Your assigned institution</small>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <label class="form-label">Last Login</label>
                        <input type="text" class="form-control bg-light" value="<?= $user->last_login ? date('F j, Y g:ia', strtotime($user->last_login)) : 'Never' ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Changes</button>
                <a href="<?= site_url('profile/change_password') ?>" class="btn btn-light">
                    <i class="bi bi-key me-1"></i> Change Password
                </a>
            </div>
            <?php echo form_close(); ?>
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

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }

    .form-control.bg-light {
        background: #f8fafc;
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

    .text-muted {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.25rem;
        display: block;
    }
</style>