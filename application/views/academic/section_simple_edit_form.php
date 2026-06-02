<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('academic/sections') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Sections
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-pencil-square me-2" style="color:#6366f1;"></i>
                Edit Section
            </h5>
            <p style="color:#64748b;margin-bottom:1.5rem;">
                Grade Level: <?= isset($grade_level->name) ? htmlspecialchars($grade_level->name) : (isset($grade_level->year_level) ? 'Grade ' . str_pad($grade_level->year_level, 2, '0', STR_PAD_LEFT) : '-') ?>
            </p>
            <?= form_open('academic/edit_section/' . $section->id) ?>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;">Section Name <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" name="name" required style="border-radius:10px;padding:0.75rem;" value="<?= htmlspecialchars($section->name) ?>">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;">Adviser</label>
                    <select class="form-select select2-teacher" name="adviser_id" style="border-radius:10px;padding:0.75rem;">
                        <option value="">-- Select Adviser --</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher->id ?>" <?= ($section->adviser_id == $teacher->id) ? 'selected' : '' ?>><?= htmlspecialchars($teacher->last_name . ', ' . $teacher->first_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Update Section</button>
                    <a href="<?= site_url('academic/sections') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-teacher').select2({
        placeholder: 'Select an adviser...',
        allowClear: true,
        width: '100%'
    });
});
</script>
