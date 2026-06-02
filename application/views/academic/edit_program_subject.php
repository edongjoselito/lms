<div class="row">
    <?php
    $program_code = isset($program->code) && trim((string) $program->code) !== ''
        ? trim((string) $program->code)
        : (isset($program->year_level) ? 'G' . str_pad((int) $program->year_level, 2, '0', STR_PAD_LEFT) : '-');
    $program_name = isset($program->name) && trim((string) $program->name) !== ''
        ? trim((string) $program->name)
        : (isset($program->year_level) ? 'Grade ' . str_pad((int) $program->year_level, 2, '0', STR_PAD_LEFT) : 'Program');
    ?>
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('academic/program_subjects/' . $program->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Program Subjects
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-pencil-square me-2" style="color:#6366f1;"></i>Edit Subject
            </h5>
            <p style="color:#64748b;margin-bottom:1.5rem;"><?= htmlspecialchars($program_code) ?> - <?= htmlspecialchars($program_name) ?></p>

            <?= form_open('academic/edit_program_subject/' . $program->id . '/' . $subject->id) ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" class="form-control" name="code" value="<?= htmlspecialchars($subject->code) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Assigned Teacher</label>
                        <select class="form-select select2-teacher" name="teacher_id">
                            <option value="">Select a teacher...</option>
                            <?php if (!empty($teachers)): ?>
                                <?php foreach ($teachers as $t): ?>
                                    <?php $is_assigned = false; ?>
                                    <?php if (!empty($assigned_teachers)): ?>
                                        <?php foreach ($assigned_teachers as $at): ?>
                                            <?php if ($at->id == $t->id): ?>
                                                <?php $is_assigned = true; break; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <option value="<?= $t->id ?>" <?= $is_assigned ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(trim($t->first_name . ' ' . $t->last_name)) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No teachers available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="2" required><?= htmlspecialchars($subject->description) ?></textarea>
                </div>
                <button type="submit" class="btn-primary-custom w-100 mt-2">
                    <i class="bi bi-check-lg"></i> Update Subject
                </button>
            <?= form_close() ?>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-teacher').select2({
        placeholder: 'Select a teacher...',
        allowClear: true,
        width: '100%'
    });
});
</script>
