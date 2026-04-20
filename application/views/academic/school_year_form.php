<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="mb-3">
            <a href="<?= site_url('academic/school_years') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-calendar3 me-2" style="color:#6366f1;"></i>Add School Year
            </h5>
            <form action="<?= site_url('academic/create_school_year') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Year Start</label>
                        <input type="number" class="form-control" name="year_start" min="2020" max="2050" value="<?= date('Y') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Year End</label>
                        <input type="number" class="form-control" name="year_end" min="2020" max="2050" value="<?= date('Y') + 1 ?>" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeSwitch">
                            <label class="form-check-label" for="activeSwitch">Set as active school year</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('academic/school_years') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
