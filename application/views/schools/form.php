<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-3">
            <a href="<?= site_url('schools') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Schools
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-building me-2" style="color:#6366f1;"></i>
                <?= ($school) ? 'Edit School' : 'Add School' ?>
            </h5>
            <form action="<?= ($school) ? site_url('schools/edit/' . $school->id) : site_url('schools/create') ?>" method="post">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">School Name</label>
                        <input type="text" class="form-control" name="name" value="<?= ($school) ? htmlspecialchars($school->name) : '' ?>" required placeholder="e.g. ABC National High School">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">School ID Number</label>
                        <input type="text" class="form-control" name="school_id_number" value="<?= ($school) ? htmlspecialchars($school->school_id_number) : '' ?>" placeholder="DepEd / CHED ID">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type" required>
                            <option value="deped" <?= ($school && $school->type == 'deped') ? 'selected' : '' ?>>DepEd (K-12)</option>
                            <option value="ched" <?= ($school && $school->type == 'ched') ? 'selected' : '' ?>>CHED (Higher Ed)</option>
                            <option value="both" <?= ($school && $school->type == 'both') ? 'selected' : '' ?>>Both</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= ($school) ? htmlspecialchars($school->email) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact Number</label>
                        <input type="text" class="form-control" name="contact_number" value="<?= ($school) ? htmlspecialchars($school->contact_number) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Division</label>
                        <input type="text" class="form-control" name="division" value="<?= ($school) ? htmlspecialchars($school->division) : '' ?>" placeholder="e.g. SDO Cavite">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Region</label>
                        <input type="text" class="form-control" name="region" value="<?= ($school) ? htmlspecialchars($school->region) : '' ?>" placeholder="e.g. Region IV-A">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2"><?= ($school) ? htmlspecialchars($school->address) : '' ?></textarea>
                    </div>
                    <?php if ($school): ?>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" <?= ($school->status) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusSwitch">Active</label>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('schools') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
