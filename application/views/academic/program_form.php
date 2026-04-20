<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-3">
            <a href="<?= site_url('academic/programs') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Programs
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-mortarboard-fill me-2" style="color:#6366f1;"></i>
                <?= ($program) ? 'Edit Program' : 'Add Program' ?>
            </h5>
            <form action="<?= ($program) ? site_url('academic/edit_program/' . $program->id) : site_url('academic/create_program') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control" name="code" value="<?= ($program) ? htmlspecialchars($program->code) : '' ?>" required placeholder="BSIT">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Program Name</label>
                        <input type="text" class="form-control" name="name" value="<?= ($program) ? htmlspecialchars($program->name) : '' ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"><?= ($program) ? htmlspecialchars($program->description) : '' ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Degree Type</label>
                        <select class="form-select" name="degree_type" required>
                            <?php foreach (array('bachelor','master','doctorate','diploma','certificate') as $dt): ?>
                                <option value="<?= $dt ?>" <?= ($program && $program->degree_type == $dt) ? 'selected' : '' ?>><?= ucfirst($dt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Units</label>
                        <input type="number" step="0.5" class="form-control" name="total_units" value="<?= ($program) ? $program->total_units : '150' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Years to Complete</label>
                        <input type="number" class="form-control" name="years_to_complete" value="<?= ($program) ? $program->years_to_complete : '4' ?>">
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('academic/programs') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
