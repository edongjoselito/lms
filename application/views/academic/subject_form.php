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
                        <label class="form-label">System Type</label>
                        <select class="form-select" name="system_type" id="systemType" required>
                            <option value="deped" <?= ($subject && $subject->system_type == 'deped') ? 'selected' : '' ?>>DepEd (K-12)</option>
                            <option value="ched" <?= ($subject && $subject->system_type == 'ched') ? 'selected' : '' ?>>CHED (Higher Ed)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control" name="code" value="<?= ($subject) ? htmlspecialchars($subject->code) : '' ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Subject Name</label>
                        <input type="text" class="form-control" name="name" value="<?= ($subject) ? htmlspecialchars($subject->name) : '' ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"><?= ($subject) ? htmlspecialchars($subject->description) : '' ?></textarea>
                    </div>

                    <div class="col-12"><hr class="my-1"><p class="mb-0" style="font-weight:600;font-size:0.85rem;color:#6366f1;" id="depedLabel">DepEd Fields</p></div>
                    <div class="col-md-4" id="gradeField">
                        <label class="form-label">Grade Level</label>
                        <select class="form-select" name="grade_level_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($grade_levels as $gl): ?>
                                <option value="<?= $gl->id ?>" <?= ($subject && $subject->grade_level_id == $gl->id) ? 'selected' : '' ?>><?= $gl->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4" id="laField">
                        <label class="form-label">Learning Area</label>
                        <select class="form-select" name="learning_area_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($learning_areas as $la): ?>
                                <option value="<?= $la->id ?>" <?= ($subject && $subject->learning_area_id == $la->id) ? 'selected' : '' ?>><?= $la->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4" id="strandField">
                        <label class="form-label">SHS Strand</label>
                        <select class="form-select" name="strand_id">
                            <option value="">-- N/A --</option>
                            <?php foreach ($strands as $st): ?>
                                <option value="<?= $st->id ?>" <?= ($subject && $subject->strand_id == $st->id) ? 'selected' : '' ?>><?= $st->code ?> - <?= $st->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12"><p class="mb-0" style="font-weight:600;font-size:0.85rem;color:#6366f1;" id="chedLabel">CHED Fields</p></div>
                    <div class="col-md-4" id="progField">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($programs as $pr): ?>
                                <option value="<?= $pr->id ?>" <?= ($subject && $subject->program_id == $pr->id) ? 'selected' : '' ?>><?= $pr->code ?> - <?= $pr->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2" id="ylField">
                        <label class="form-label">Year Level</label>
                        <input type="number" class="form-control" name="year_level" min="1" max="6" value="<?= ($subject) ? $subject->year_level : '' ?>">
                    </div>
                    <div class="col-md-3" id="semField">
                        <label class="form-label">Semester</label>
                        <select class="form-select" name="semester_type">
                            <option value="">-- Select --</option>
                            <option value="1st_sem" <?= ($subject && $subject->semester_type == '1st_sem') ? 'selected' : '' ?>>1st Semester</option>
                            <option value="2nd_sem" <?= ($subject && $subject->semester_type == '2nd_sem') ? 'selected' : '' ?>>2nd Semester</option>
                            <option value="summer" <?= ($subject && $subject->semester_type == 'summer') ? 'selected' : '' ?>>Summer</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="unitsField">
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
<script>
document.getElementById('systemType').addEventListener('change', function() {
    var v = this.value;
    var depedEls = ['gradeField','laField','strandField','depedLabel'];
    var chedEls = ['progField','ylField','semField','unitsField','chedLabel'];
    depedEls.forEach(function(id) { document.getElementById(id).style.display = (v === 'deped') ? '' : 'none'; });
    chedEls.forEach(function(id) { document.getElementById(id).style.display = (v === 'ched') ? '' : 'none'; });
});
document.getElementById('systemType').dispatchEvent(new Event('change'));
</script>
