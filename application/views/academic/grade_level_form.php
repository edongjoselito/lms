<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('academic/grade_levels') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Grade Levels
            </a>
        </div>

        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-<?= ($grade_level) ? 'pencil-square' : 'plus-circle' ?> me-2" style="color:#6366f1;"></i>
                <?= ($grade_level) ? 'Edit Grade Level' : 'Add Grade Level' ?>
            </h5>

            <form action="<?= ($grade_level) ? site_url('academic/edit_grade_level/' . $grade_level->id) : site_url('academic/create_grade_level') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control" name="code"
                               value="<?= ($grade_level) ? htmlspecialchars($grade_level->code) : '' ?>" required
                               placeholder="e.g. G1, G7">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                               value="<?= ($grade_level) ? htmlspecialchars($grade_level->name) : '' ?>" required
                               placeholder="e.g. Grade 1, Grade 7">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Level Order</label>
                        <input type="number" class="form-control" name="level_order"
                               value="<?= ($grade_level) ? $grade_level->level_order : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category" required>
                            <option value="">Select Category</option>
                            <option value="elementary" <?= ($grade_level && $grade_level->category == 'elementary') ? 'selected' : '' ?>>Elementary</option>
                            <option value="junior_high" <?= ($grade_level && $grade_level->category == 'junior_high') ? 'selected' : '' ?>>Junior High School</option>
                            <option value="senior_high" <?= ($grade_level && $grade_level->category == 'senior_high') ? 'selected' : '' ?>>Senior High School</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid #e2e8f0;padding-top:1.5rem;">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check-lg"></i>
                        <?= ($grade_level) ? 'Update Grade Level' : 'Create Grade Level' ?>
                    </button>
                    <a href="<?= site_url('academic/grade_levels') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
