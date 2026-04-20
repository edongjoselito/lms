<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('profile') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-key me-2" style="color:#6366f1;"></i>
                Change Password
            </h5>
            <?php echo form_open('profile/update_password'); ?>
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="new_password" required minlength="6">
                    <small style="color:#94a3b8;font-size:0.75rem;">Minimum 6 characters</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Change Password</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
