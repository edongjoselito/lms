<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('subjects') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Subjects
            </a>
        </div>

        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-<?= ($subject) ? 'pencil-square' : 'plus-circle' ?> me-2" style="color:#6366f1;"></i>
                <?= ($subject) ? 'Edit Subject' : 'Add Subject' ?>
            </h5>

            <form action="<?= ($subject) ? site_url('subjects/edit/' . $subject->id) : site_url('subjects/create') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control" name="code"
                               value="<?= ($subject) ? htmlspecialchars($subject->code) : '' ?>" required
                               placeholder="e.g. CS101">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="1"
                                  placeholder="Subject description..."><?= ($subject) ? htmlspecialchars($subject->description) : '' ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program_id" id="programSelect">
                            <option value="">Select Program</option>
                            <?php foreach ($programs as $p): ?>
                                <option value="<?= $p->id ?>" <?= ($subject && $subject->program_id == $p->id) ? 'selected' : '' ?>>
                                    <?= $p->code ?> - <?= $p->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Grade Level</label>
                        <select class="form-select" name="grade_level_id" id="gradeLevelSelect">
                            <option value="">Select Grade Level</option>
                            <?php foreach ($grade_levels as $gl): ?>
                                <option value="<?= $gl->id ?>" <?= ($subject && $subject->grade_level_id == $gl->id) ? 'selected' : '' ?>>
                                    <?= $gl->code ?> - <?= $gl->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" name="semester_type" id="semesterSelect">
                            <option value="">Select Semester</option>
                            <option value="1st_sem" <?= ($subject && $subject->semester_type == '1st_sem') ? 'selected' : '' ?>>First Semester</option>
                            <option value="2nd_sem" <?= ($subject && $subject->semester_type == '2nd_sem') ? 'selected' : '' ?>>Second Semester</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid #e2e8f0;padding-top:1.5rem;">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check-lg"></i>
                        <?= ($subject) ? 'Update Subject' : 'Create Subject' ?>
                    </button>
                    <a href="<?= site_url('subjects') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('programSelect');
    const gradeLevelSelect = document.getElementById('gradeLevelSelect');
    const semesterSelect = document.getElementById('semesterSelect');
    const semesterLabel = semesterSelect.closest('.col-md-3').querySelector('label');
    
    function toggleSemester() {
        if (programSelect.value) {
            // Program selected (CHED) - show semester
            semesterSelect.closest('.col-md-3').style.display = '';
            semesterLabel.textContent = 'Semester';
            semesterSelect.required = true;
        } else if (gradeLevelSelect.value) {
            // Grade level selected (DepEd) - hide semester
            semesterSelect.closest('.col-md-3').style.display = 'none';
            semesterSelect.required = false;
        } else {
            // Neither selected - show semester as optional
            semesterSelect.closest('.col-md-3').style.display = '';
            semesterLabel.textContent = 'Semester (Optional)';
            semesterSelect.required = false;
        }
    }
    
    programSelect.addEventListener('change', function() {
        if (this.value) {
            gradeLevelSelect.value = '';
        }
        toggleSemester();
    });
    
    gradeLevelSelect.addEventListener('change', function() {
        if (this.value) {
            programSelect.value = '';
        }
        toggleSemester();
    });
    
    // Initial state
    toggleSemester();
});
</script>
