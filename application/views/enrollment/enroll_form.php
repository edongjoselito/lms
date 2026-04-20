<?php $sy_label = isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A'; ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-3">
            <a href="<?= site_url('enrollment') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Enrollment
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-clipboard-check-fill me-2" style="color:#6366f1;"></i>Enroll Student
                <small style="color:#94a3b8;font-weight:400;font-size:0.8rem;">(S.Y. <?= $sy_label ?>)</small>
            </h5>
            <form action="<?= site_url('enrollment/enroll') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Student</label>
                        <select class="form-select" name="student_id" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?= $s->id ?>"><?= htmlspecialchars($s->full_name) ?> (<?= $s->lrn ?: $s->student_id ?: $s->email ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">System</label>
                        <select class="form-select" name="system_type" id="enrSysType" required>
                            <option value="deped">DepEd</option>
                            <option value="ched">CHED</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="enrGlField">
                        <label class="form-label">Grade Level</label>
                        <select class="form-select" name="grade_level_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($grade_levels as $gl): ?>
                                <option value="<?= $gl->id ?>"><?= $gl->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6" id="enrStrandField">
                        <label class="form-label">Strand</label>
                        <select class="form-select" name="strand_id">
                            <option value="">-- N/A --</option>
                            <?php foreach ($strands as $st): ?>
                                <option value="<?= $st->id ?>"><?= $st->code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6" id="enrProgField">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($programs as $pr): ?>
                                <option value="<?= $pr->id ?>"><?= $pr->code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="enrYlField">
                        <label class="form-label">Year Level</label>
                        <input type="number" class="form-control" name="year_level" min="1" max="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Section</label>
                        <select class="form-select" name="section_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($sections as $sec): ?>
                                <option value="<?= $sec->id ?>"><?= htmlspecialchars($sec->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6" id="enrSemField">
                        <label class="form-label">Semester / Quarter</label>
                        <select class="form-select" name="semester_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($semesters as $sem): ?>
                                <option value="<?= $sem->id ?>"><?= $sem->name ?> (<?= $sem->type ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Enroll</button>
                    <a href="<?= site_url('enrollment') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('enrSysType').addEventListener('change', function() {
    var v = this.value;
    ['enrGlField','enrStrandField'].forEach(function(id) { document.getElementById(id).style.display = (v === 'deped') ? '' : 'none'; });
    ['enrProgField','enrYlField'].forEach(function(id) { document.getElementById(id).style.display = (v === 'ched') ? '' : 'none'; });
});
document.getElementById('enrSysType').dispatchEvent(new Event('change'));
</script>
