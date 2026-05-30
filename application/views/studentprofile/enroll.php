<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('studentprofile') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Student Profiles
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-person-plus me-2" style="color:#6366f1;"></i>
                Enroll Student
            </h5>
            <form action="<?= site_url('studentprofile/enroll/' . $profile->id) ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student Number</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($profile->student_number) ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($profile->last_name . ', ' . $profile->first_name) ?>" readonly>
                    </div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Grade Level <span style="color:red;">*</span></label>
                        <select class="form-select" name="grade_level_id" id="grade_level_id" required>
                            <option value="">Select Grade Level</option>
                            <?php foreach ($grade_levels as $gl): ?>
                                <?php
                                $name = htmlspecialchars($gl->name);
                                // Format to Grade 01-12 if it's a number
                                if (is_numeric($name) && $name >= 1 && $name <= 12) {
                                    $name = 'Grade ' . str_pad($name, 2, '0', STR_PAD_LEFT);
                                }
                                ?>
                                <option value="<?= $gl->id ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Section <span style="color:red;">*</span></label>
                        <select class="form-select" name="section_id" id="section_id" required>
                            <option value="">Select Section</option>
                            <?php if (empty($sections)): ?>
                                <option value="">No sections available</option>
                            <?php else: ?>
                                <?php foreach ($sections as $s): ?>
                                    <option value="<?= $s->id ?>" data-grade-level="<?= $s->grade_level_id ?>">
                                        <?= htmlspecialchars($s->name) ?>
                                        <?php if (isset($s->grade_level_name) && !empty($s->grade_level_name)): ?>
                                            (<?= htmlspecialchars($s->grade_level_name) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Adviser</label>
                        <select class="form-select" name="adviser_id">
                            <option value="">Select Adviser</option>
                            <?php foreach ($advisers as $a): ?>
                                <option value="<?= $a->id ?>"><?= htmlspecialchars($a->last_name . ', ' . $a->first_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Enroll Student</button>
                    <a href="<?= site_url('studentprofile') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var gradeLevelSelect = document.getElementById('grade_level_id');
    var sectionSelect = document.getElementById('section_id');

    gradeLevelSelect.addEventListener('change', function() {
        var selectedGradeLevel = this.value;
        var sectionOptions = sectionSelect.querySelectorAll('option');

        // Clear section selection
        sectionSelect.value = '';

        // For now, show all sections regardless of grade level
        // since grade_level_id in sections may not match static 1-12 values
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        sectionOptions.forEach(function(option) {
            if (option.value !== '') {
                sectionSelect.innerHTML += '<option value="' + option.value + '">' + option.text + '</option>';
            }
        });
    });
});
</script>
