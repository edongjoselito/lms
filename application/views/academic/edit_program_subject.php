<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="mb-3">
            <a href="<?= site_url('academic/program_subjects/' . $program->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Program Subjects
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-pencil-square me-2" style="color:#6366f1;"></i>Edit Subject
            </h5>
            <p style="color:#64748b;margin-bottom:1.5rem;"><?= $program->code ?> - <?= $program->name ?></p>

            <form action="<?= site_url('academic/edit_program_subject/' . $program->id . '/' . $subject->id) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Course Code</label>
                    <input type="text" class="form-control" name="code" value="<?= htmlspecialchars($subject->code) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="2"><?= htmlspecialchars($subject->description) ?></textarea>
                </div>
                <button type="submit" class="btn-primary-custom w-100 mt-2">
                    <i class="bi bi-check-lg"></i> Update Subject
                </button>
            </form>
        </div>
    </div>
</div>
