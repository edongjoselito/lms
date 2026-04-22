<div class="row">
    <div class="col-12">
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
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?= ($program) ? htmlspecialchars($program->name) : '' ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"><?= ($program) ? htmlspecialchars($program->description) : '' ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type" id="programType" required onchange="toggleProgramFields()">
                            <option value="program" <?= ($program && (isset($program->type) && $program->type == 'program' || !isset($program->type))) ? 'selected' : '' ?>>Program (CHED)</option>
                            <option value="grade_level" <?= ($program && isset($program->type) && $program->type == 'grade_level') ? 'selected' : '' ?>>Grade Level (DepEd)</option>
                        </select>
                    </div>
                    
                    <!-- Program-specific fields -->
                    <div id="programFields" class="col-12">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Degree Type</label>
                                <select class="form-select" name="degree_type">
                                    <?php foreach (array('bachelor','master','doctorate','diploma','certificate') as $dt): ?>
                                        <option value="<?= $dt ?>" <?= ($program && isset($program->degree_type) && $program->degree_type == $dt) ? 'selected' : '' ?>><?= ucfirst($dt) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Units</label>
                                <input type="number" step="0.5" class="form-control" name="total_units" value="<?= ($program && isset($program->total_units)) ? $program->total_units : '150' ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Years to Complete</label>
                                <input type="number" class="form-control" name="years_to_complete" value="<?= ($program && isset($program->years_to_complete)) ? $program->years_to_complete : '4' ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grade Level-specific fields -->
                    <div id="gradeLevelFields" class="col-12" style="display:none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="elementary" <?= ($program && isset($program->category) && $program->category == 'elementary') ? 'selected' : '' ?>>Elementary</option>
                                    <option value="junior_high" <?= ($program && isset($program->category) && $program->category == 'junior_high') ? 'selected' : '' ?>>Junior High</option>
                                    <option value="senior_high" <?= ($program && isset($program->category) && $program->category == 'senior_high') ? 'selected' : '' ?>>Senior High</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Level Order</label>
                                <input type="number" class="form-control" name="level_order" value="<?= ($program && isset($program->level_order)) ? $program->level_order : '0' ?>">
                            </div>
                        </div>
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

<script>
function toggleProgramFields() {
    var type = document.getElementById('programType').value;
    var programFields = document.getElementById('programFields');
    var gradeLevelFields = document.getElementById('gradeLevelFields');
    
    if (type === 'grade_level') {
        programFields.style.display = 'none';
        gradeLevelFields.style.display = 'block';
    } else {
        programFields.style.display = 'block';
        gradeLevelFields.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleProgramFields();
});
</script>
