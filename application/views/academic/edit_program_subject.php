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
            <p style="margin-bottom:1.5rem;"><strong>Subject:</strong> <?= $subject->code ?> - <?= $subject->description ?></p>
            
            <form action="<?= site_url('academic/edit_program_subject/' . $program->id . '/' . $subject->id) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <select class="form-select" name="semester_type" required>
                        <option value="">Select Semester</option>
                        <option value="1st_sem" <?= ($subject->semester_type == '1st_sem') ? 'selected' : '' ?>>First Semester</option>
                        <option value="2nd_sem" <?= ($subject->semester_type == '2nd_sem') ? 'selected' : '' ?>>Second Semester</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Year Level</label>
                    <select class="form-select" name="year_level" required>
                        <option value="">Select Year Level</option>
                        <option value="1st" <?= ($subject->year_level == '1st') ? 'selected' : '' ?>>1st Year</option>
                        <option value="2nd" <?= ($subject->year_level == '2nd') ? 'selected' : '' ?>>2nd Year</option>
                        <option value="3rd" <?= ($subject->year_level == '3rd') ? 'selected' : '' ?>>3rd Year</option>
                        <option value="4th" <?= ($subject->year_level == '4th') ? 'selected' : '' ?>>4th Year</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Units</label>
                    <input type="number" class="form-control" name="units" value="<?= $subject->units ?>" required min="0">
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="mb-2">
                            <label class="form-label" style="font-size:0.85rem;">Lec Hours</label>
                            <input type="number" class="form-control" name="lec_hours" value="<?= $subject->lec_hours ?>" min="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <label class="form-label" style="font-size:0.85rem;">Lab Hours</label>
                            <input type="number" class="form-control" name="lab_hours" value="<?= $subject->lab_hours ?>" min="0">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-primary-custom w-100 mt-2">
                    <i class="bi bi-check-lg"></i> Update Subject
                </button>
            </form>
        </div>
    </div>
</div>
