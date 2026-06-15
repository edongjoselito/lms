<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('studentprofile') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Student Profiles
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-badge me-2" style="color:#6366f1;"></i>
                <?= isset($profile) ? 'Edit Student Profile' : 'Add Student Profile' ?>
            </h5>
            <?= form_open(isset($profile) ? 'studentprofile/edit/' . $profile->id : 'studentprofile/create') ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student Number / LRN <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="student_number" value="<?= isset($profile) ? htmlspecialchars($profile->student_number) : '' ?>" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="<?= isset($profile) ? htmlspecialchars($profile->first_name) : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="<?= isset($profile) ? htmlspecialchars($profile->middle_name) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="<?= isset($profile) ? htmlspecialchars($profile->last_name) : '' ?>" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Birth Date <span style="color:red;">*</span></label>
                        <input type="date" class="form-control" name="birth_date" value="<?= isset($profile) ? htmlspecialchars($profile->birth_date) : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sex</label>
                        <select class="form-select" name="gender">
                            <option value="">Select Sex</option>
                            <option value="Male" <?= isset($profile) && isset($profile->gender) && $profile->gender === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= isset($profile) && isset($profile->gender) && $profile->gender === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= isset($profile) && isset($profile->email) ? htmlspecialchars($profile->email) : '' ?>">
                    </div>
                </div>
                <?php if (!isset($profile)): ?>
                <div class="mt-3 p-3" style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;">
                    <p style="margin:0;font-size:0.875rem;color:#0369a1;">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Note:</strong> A user account will be automatically created with:
                        <br>• Login Username: Student Number
                        <br>• Password: Birth Date (YYYY-MM-DD format)
                        <br>• Role: Student
                    </p>
                </div>
                <?php endif; ?>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('studentprofile') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
