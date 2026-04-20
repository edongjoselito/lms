<div class="row">
    <div class="col-12">
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-person-circle me-2" style="color:#6366f1;"></i>
                My Profile
            </h5>
            <?php echo form_open('profile/update'); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user->first_name) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user->last_name) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user->role_name) ?>" readonly style="background:#f8fafc;">
                    </div>
                    <?php if ($user->school_name): ?>
                    <div class="col-md-6">
                        <label class="form-label">School</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user->school_name) ?>" readonly style="background:#f8fafc;">
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <label class="form-label">Last Login</label>
                        <input type="text" class="form-control" value="<?= $user->last_login ? date('F j, Y g:ia', strtotime($user->last_login)) : 'Never' ?>" readonly style="background:#f8fafc;">
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Changes</button>
                    <a href="<?= site_url('profile/change_password') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">
                        <i class="bi bi-key me-1"></i> Change Password
                    </a>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
