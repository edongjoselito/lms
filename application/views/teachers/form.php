<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('teachers') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Teachers
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-person-badge me-2" style="color:#6366f1;"></i>
                <?= isset($teacher) ? 'Edit Teacher' : 'Add Teacher' ?>
            </h5>
            <form action="<?= isset($teacher) ? site_url('teachers/edit/' . (isset($teacher->IDNumber) ? $teacher->IDNumber : (isset($teacher->id) ? $teacher->id : ''))) : site_url('teachers/create') ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;color:#334155;">First Name <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="first_name" required style="border-radius:10px;padding:0.75rem;" value="<?= isset($teacher) ? htmlspecialchars($teacher->first_name) : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;color:#334155;">Last Name <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="last_name" required style="border-radius:10px;padding:0.75rem;" value="<?= isset($teacher) ? htmlspecialchars($teacher->last_name) : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;">Email <span style="color:red;">*</span></label>
                    <input type="email" class="form-control" name="email" required style="border-radius:10px;padding:0.75rem;" value="<?= isset($teacher) ? htmlspecialchars($teacher->email) : '' ?>">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;">Password <?= !isset($teacher) ? '<span style="color:red;">*</span>' : '<span style="color:#94a3b8;font-size:0.8rem;font-weight:400;">(leave blank to keep current)</span>' ?></label>
                    <div style="position:relative;">
                        <input type="password" class="form-control" name="password" id="passwordField" <?= !isset($teacher) ? 'required' : '' ?> style="border-radius:10px;padding:0.75rem;padding-right:3rem;" placeholder="<?= isset($teacher) ? 'Enter new password to change' : '' ?>">
                        <button type="button" onclick="togglePassword()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <?php if (!isset($teacher)): ?>
                    <button type="button" onclick="generatePassword()" style="margin-top:0.5rem;font-size:0.8rem;padding:0.4rem 0.8rem;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;cursor:pointer;">
                        <i class="bi bi-magic"></i> Generate Password
                    </button>
                    <?php endif; ?>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> <?= isset($teacher) ? 'Update Teacher' : 'Create Teacher' ?></button>
                    <a href="<?= site_url('teachers') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('passwordField');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('bi-eye');
        eyeIcon.classList.add('bi-eye-slash');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('bi-eye-slash');
        eyeIcon.classList.add('bi-eye');
    }
}

function generatePassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('passwordField').value = password;
}
</script>
