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
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student Number</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($profile->student_number) ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($profile->last_name . ', ' . $profile->first_name) ?>" readonly>
                    </div>
                    <?php if (isset($current_enrollment) && $current_enrollment): ?>
                    <div class="col-md-4">
                        <label class="form-label">Current Grade Level</label>
                        <input type="text" class="form-control" value="<?= isset($current_enrollment->grade_level_name) ? htmlspecialchars($current_enrollment->grade_level_name) : (isset($current_enrollment->year_level) ? 'Grade ' . $current_enrollment->year_level : 'N/A') ?>" readonly>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Grade Level <span style="color:red;">*</span></label>
                        <select class="form-select" name="grade_level_id" id="grade_level_id" required>
                            <option value="">Select Grade Level</option>
                            <?php foreach ($grade_levels as $gl): ?>
                                <?php
                                // Use year_level if available, otherwise use name
                                if (isset($gl->year_level) && $gl->year_level) {
                                    $name = 'Grade ' . str_pad($gl->year_level, 2, '0', STR_PAD_LEFT);
                                } else {
                                    $name = htmlspecialchars($gl->name);
                                    // Format to Grade 01-12 if it's a number
                                    if (is_numeric($name) && $name >= 1 && $name <= 12) {
                                        $name = 'Grade ' . str_pad($name, 2, '0', STR_PAD_LEFT);
                                    }
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
                                    <option value="<?= $s->id ?>" data-program="<?= isset($s->program_id) ? $s->program_id : '' ?>" data-adviser="<?= isset($s->adviser_id) ? $s->adviser_id : '' ?>" data-adviser-name="<?= isset($s->adviser_name) ? htmlspecialchars($s->adviser_name) : '' ?>">
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
    var adviserSelect = document.querySelector('select[name="adviser_id"]');

    // Store all section options on page load
    var allSectionOptions = [];
    sectionSelect.querySelectorAll('option').forEach(function(option) {
        allSectionOptions.push({
            value: option.value,
            text: option.text,
            program: option.getAttribute('data-program'),
            adviserId: option.getAttribute('data-adviser'),
            adviserName: option.getAttribute('data-adviser-name')
        });
    });

    gradeLevelSelect.addEventListener('change', function() {
        var selectedGradeLevel = this.value;

        // Clear section selection
        sectionSelect.value = '';
        sectionSelect.innerHTML = '<option value="">Select Section</option>';

        // Filter sections by selected grade level (program_id)
        allSectionOptions.forEach(function(option) {
            if (option.value === '') return; // Skip the default option

            // Show section if no grade level selected or if it matches
            if (!selectedGradeLevel || option.program == selectedGradeLevel) {
                sectionSelect.innerHTML += '<option value="' + option.value + '" data-program="' + option.program + '" data-adviser="' + option.adviserId + '" data-adviser-name="' + option.adviserName + '">' + option.text + '</option>';
            }
        });

        // If no sections match, show a message
        if (sectionSelect.options.length <= 1) {
            sectionSelect.innerHTML += '<option value="">No sections available for this grade level</option>';
        }

        // Clear adviser when grade level changes
        adviserSelect.value = '';
    });

    sectionSelect.addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var adviserId = selectedOption.getAttribute('data-adviser');
        var adviserName = selectedOption.getAttribute('data-adviser-name');

        // Clear adviser selection
        adviserSelect.value = '';

        // If section has an adviser, select it
        if (adviserId) {
            // Check if the adviser exists in the dropdown
            var adviserExists = false;
            for (var i = 0; i < adviserSelect.options.length; i++) {
                if (adviserSelect.options[i].value == adviserId) {
                    adviserExists = true;
                    break;
                }
            }

            if (adviserExists) {
                adviserSelect.value = adviserId;
            } else {
                // If adviser doesn't exist in dropdown, add it
                var option = document.createElement('option');
                option.value = adviserId;
                option.text = adviserName || 'Adviser';
                adviserSelect.add(option);
                adviserSelect.value = adviserId;
            }
        }
    });
});
</script>
