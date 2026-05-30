<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-3">
            <a href="<?= site_url('academic/subjects') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Subjects
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-book-fill me-2" style="color:#6366f1;"></i>
                <?= ($subject) ? 'Edit Subject' : 'Add Subject' ?>
            </h5>
            <form action="<?= ($subject) ? site_url('academic/edit_subject/' . $subject->id) : site_url('academic/create_subject') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control" name="code" value="<?= ($subject) ? htmlspecialchars($subject->code) : '' ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"><?= ($subject) ? htmlspecialchars($subject->description) : '' ?></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($programs as $pr): ?>
                                <option value="<?= $pr->id ?>" <?= ($subject && $subject->program_id == $pr->id) ? 'selected' : '' ?>><?= $pr->code ?> - <?= $pr->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Year Level</label>
                        <input type="number" class="form-control" name="year_level" min="1" max="12" value="<?= ($subject) ? $subject->year_level : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Units</label>
                        <input type="number" step="0.5" class="form-control" name="units" value="<?= ($subject) ? $subject->units : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lecture Hours</label>
                        <input type="number" step="0.5" class="form-control" name="lec_hours" value="<?= ($subject) ? $subject->lec_hours : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lab Hours</label>
                        <input type="number" step="0.5" class="form-control" name="lab_hours" value="<?= ($subject) ? $subject->lab_hours : '' ?>">
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('academic/subjects') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
