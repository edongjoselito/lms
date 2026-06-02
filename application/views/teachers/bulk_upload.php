<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('teachers') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Teachers
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-upload me-2" style="color:#6366f1;"></i>
                Bulk Upload Teachers
            </h5>
            <div class="alert alert-info" style="border-radius:10px;border:none;background:#eff6ff;color:#1e40af;">
                <i class="bi bi-info-circle me-2"></i>
                Upload a CSV file with the following columns: <strong>First Name, Last Name, Email, Password</strong>
            </div>
            <?= form_open_multipart('teachers/bulk_upload') ?>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;">CSV File <span style="color:red;">*</span></label>
                    <input type="file" class="form-control" name="csv_file" accept=".csv" required style="border-radius:10px;padding:0.75rem;">
                    <small style="color:#64748b;">Maximum file size: 2MB</small>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-upload"></i> Upload Teachers</button>
                    <a href="<?= site_url('teachers') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
