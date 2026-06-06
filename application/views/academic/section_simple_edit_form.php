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
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;color:#334155;">Subject-Teacher Assignments</label>
                    <small style="color:#64748b;display:block;margin-bottom:0.75rem;">Assign teachers to specific subjects in this section.</small>
                    
                    <div id="subject-teacher-assignments" style="border:1px solid #e2e8f0;border-radius:10px;padding:1rem;background:#f8fafc;">
                        <div class="assignment-row" style="display:flex;gap:0.75rem;margin-bottom:0.75rem;align-items:center;">
                            <select class="form-select subject-select" name="subject_ids[]" style="flex:1;border-radius:8px;padding:0.5rem;font-size:0.875rem;">
                                <option value="">-- Select Subject --</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= $subject->id ?>"><?= htmlspecialchars($subject->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="form-select teacher-select" name="staff_ids[]" style="flex:1;border-radius:8px;padding:0.5rem;font-size:0.875rem;">
                                <option value="">-- Select Teacher --</option>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= isset($teacher->IDNumber) ? $teacher->IDNumber : (isset($teacher->id) ? $teacher->id : '') ?>"><?= htmlspecialchars($teacher->last_name . ', ' . $teacher->first_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-danger btn-sm remove-assignment" style="border-radius:8px;padding:0.4rem 0.8rem;">&times;</button>
                        </div>
                    </div>
                    <button type="button" id="add-assignment" class="btn btn-secondary btn-sm" style="border-radius:8px;padding:0.5rem 1rem;margin-top:0.5rem;font-size:0.875rem;">+ Add Assignment</button>
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

    // Add new assignment row
    $('#add-assignment').on('click', function() {
        var newRow = $('.assignment-row:first').clone();
        newRow.find('select').val('');
        newRow.find('.subject-select').select2({width: '100%', placeholder: '-- Select Subject --'});
        newRow.find('.teacher-select').select2({width: '100%', placeholder: '-- Select Teacher --'});
        $('#subject-teacher-assignments').append(newRow);
    });

    // Remove assignment row
    $(document).on('click', '.remove-assignment', function() {
        if ($('.assignment-row').length > 1) {
            $(this).closest('.assignment-row').remove();
        }
    });

    // Initialize Select2 for subject and teacher dropdowns
    $('.subject-select, .teacher-select').select2({
        width: '100%',
        placeholder: function() {
            return $(this).hasClass('subject-select') ? '-- Select Subject --' : '-- Select Teacher --';
        }
    });

    // Pre-populate existing assignments
    <?php if (!empty($section_teachers)): ?>
        $('#subject-teacher-assignments').empty();
        <?php foreach ($section_teachers as $assignment): ?>
            var newRow = $('<div class="assignment-row" style="display:flex;gap:0.75rem;margin-bottom:0.75rem;align-items:center;">' +
                '<select class="form-select subject-select" name="subject_ids[]" style="flex:1;border-radius:8px;padding:0.5rem;font-size:0.875rem;">' +
                '<option value="">-- Select Subject --</option>' +
                <?php foreach ($subjects as $subject): ?>
                    '<option value="<?= $subject->id ?>" <?= $assignment->subject_id == $subject->id ? 'selected' : '' ?>><?= htmlspecialchars($subject->name) ?></option>' +
                <?php endforeach; ?>
                '</select>' +
                '<select class="form-select teacher-select" name="staff_ids[]" style="flex:1;border-radius:8px;padding:0.5rem;font-size:0.875rem;">' +
                '<option value="">-- Select Teacher --</option>' +
                <?php foreach ($teachers as $teacher): ?>
                    '<option value="<?= isset($teacher->IDNumber) ? $teacher->IDNumber : (isset($teacher->id) ? $teacher->id : '') ?>" <?= $assignment->staff_id == (isset($teacher->IDNumber) ? $teacher->IDNumber : (isset($teacher->id) ? $teacher->id : '')) ? 'selected' : '' ?>><?= htmlspecialchars($teacher->last_name . ', ' . $teacher->first_name) ?></option>' +
                <?php endforeach; ?>
                '</select>' +
                '<button type="button" class="btn btn-danger btn-sm remove-assignment" style="border-radius:8px;padding:0.4rem 0.8rem;">&times;</button>' +
                '</div>');
            newRow.find('.subject-select, .teacher-select').select2({width: '100%'});
            $('#subject-teacher-assignments').append(newRow);
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>
