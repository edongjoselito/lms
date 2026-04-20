<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-3">
            <a href="<?= site_url('academic/sections') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Sections
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-diagram-3-fill me-2" style="color:#6366f1;"></i>
                <?= (isset($section) && $section) ? 'Edit Section' : 'Add Section' ?>
            </h5>
            <form action="<?= (isset($section) && $section) ? site_url('academic/edit_section/' . $section->id) : site_url('academic/create_section') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Section Name</label>
                        <input type="text" class="form-control" name="name" value="<?= (isset($section) && $section) ? htmlspecialchars($section->name) : '' ?>" required placeholder="e.g. Einstein, Section A">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">System Type</label>
                        <select class="form-select" name="system_type" id="secSystemType" required>
                            <option value="deped" <?= (isset($section) && $section && $section->system_type == 'deped') ? 'selected' : '' ?>>DepEd (K-12)</option>
                            <option value="ched" <?= (isset($section) && $section && $section->system_type == 'ched') ? 'selected' : '' ?>>CHED (Higher Ed)</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="secGradeField">
                        <label class="form-label">Grade Level</label>
                        <select class="form-select" name="grade_level_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($grade_levels as $gl): ?>
                                <option value="<?= $gl->id ?>" <?= (isset($section) && $section && $section->grade_level_id == $gl->id) ? 'selected' : '' ?>><?= $gl->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6" id="secStrandField">
                        <label class="form-label">SHS Strand</label>
                        <select class="form-select" name="strand_id">
                            <option value="">-- N/A --</option>
                            <?php foreach ($strands as $st): ?>
                                <option value="<?= $st->id ?>" <?= (isset($section) && $section && $section->strand_id == $st->id) ? 'selected' : '' ?>><?= $st->code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6" id="secProgField">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($programs as $pr): ?>
                                <option value="<?= $pr->id ?>" <?= (isset($section) && $section && $section->program_id == $pr->id) ? 'selected' : '' ?>><?= $pr->code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="secYlField">
                        <label class="form-label">Year Level</label>
                        <input type="number" class="form-control" name="year_level" min="1" max="6" value="<?= (isset($section) && $section) ? $section->year_level : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adviser</label>
                        <select class="form-select" name="adviser_id">
                            <option value="">-- None --</option>
                            <?php foreach ($teachers as $t): ?>
                                <option value="<?= $t->id ?>" <?= (isset($section) && $section && $section->adviser_id == $t->id) ? 'selected' : '' ?>><?= $t->first_name . ' ' . $t->last_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" class="form-control" name="capacity" value="<?= (isset($section) && $section) ? $section->capacity : '40' ?>">
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('academic/sections') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('secSystemType').addEventListener('change', function() {
    var v = this.value;
    ['secGradeField','secStrandField'].forEach(function(id) { document.getElementById(id).style.display = (v === 'deped') ? '' : 'none'; });
    ['secProgField','secYlField'].forEach(function(id) { document.getElementById(id).style.display = (v === 'ched') ? '' : 'none'; });
});
document.getElementById('secSystemType').dispatchEvent(new Event('change'));
</script>
