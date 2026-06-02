<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('studentprofile') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Student Profiles
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-upload me-2" style="color:#6366f1;"></i>
                Bulk Upload Students
            </h5>
            <div class="alert alert-info" style="background:#eff6ff;border-color:#dbeafe;color:#1e40af;border-radius:12px;padding:1rem;">
                <strong>Instructions:</strong>
                <ul style="margin:0.5rem 0 0 1.5rem;padding:0;">
                    <li>Download the CSV template using the button below</li>
                    <li>Fill in the required fields (Student Number, First Name, Last Name, Birth Date)</li>
                    <li>Birth Date must be in YYYY-MM-DD format</li>
                    <li>Student Number will be used as the login username</li>
                    <li>Default password will be the student's birth date (YYYY-MM-DD)</li>
                    <li>Upload the filled template to create student accounts</li>
                </ul>
            </div>
            <div class="mb-4">
                <a href="<?= site_url('studentprofile/download_template') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">
                    <i class="bi bi-download me-1"></i> Download CSV Template
                </a>
            </div>
            <?= form_open_multipart('studentprofile/bulk_upload') ?>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;">Upload CSV File <span style="color:red;">*</span></label>
                    <input type="file" class="form-control" name="file" accept=".csv" required style="border-radius:10px;padding:0.75rem;">
                    <small style="color:#64748b;">Supported format: .csv (Max size: 5MB)</small>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-upload"></i> Upload Students</button>
                    <a href="<?= site_url('studentprofile') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
