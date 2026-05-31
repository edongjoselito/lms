<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('enrollment') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Enrollment
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-pencil me-2" style="color:#6366f1;"></i>
                Edit Enrollment
            </h5>
            <form action="<?= site_url('enrollment/edit/' . $enrollment->id) ?>" method="post">
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
                        <select class="form-select" name="grade_level_id" required>
                            <option value="">Select Grade Level</option>
                            <?php if (empty($grade_levels)): ?>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($enrollment->year_level) && $enrollment->year_level == $i) ? 'selected' : '' ?>><?= 'Grade ' . str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                <?php endfor; ?>
                            <?php else: ?>
                                <?php foreach ($grade_levels as $gl): ?>
                                    <?php
                                    // Use year_level if available, otherwise use name
                                    if (isset($gl->year_level) && $gl->year_level) {
                                        $name = 'Grade ' . str_pad($gl->year_level, 2, '0', STR_PAD_LEFT);
                                        $value = $gl->id;
                                    } else {
                                        $name = htmlspecialchars($gl->name);
                                        $value = $gl->id;
                                        // Format to Grade 01-12 if it's a number
                                        if (is_numeric($name) && $name >= 1 && $name <= 12) {
                                            $name = 'Grade ' . str_pad($name, 2, '0', STR_PAD_LEFT);
                                        }
                                    }
                                    ?>
                                    <option value="<?= $value ?>" <?= ((int) $enrollment->grade_level_id === (int) $value) ? 'selected' : '' ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Section <span style="color:red;">*</span></label>
                        <select class="form-select" name="section_id" required>
                            <option value="">Select Section</option>
                            <?php foreach ($sections as $s): ?>
                                <option value="<?= $s->id ?>" data-grade-level="<?= isset($s->grade_level_id) ? $s->grade_level_id : (isset($s->year_level) ? $s->year_level : '') ?>" <?= $enrollment->section_id == $s->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s->name) ?>
                                    <?php if (isset($s->grade_level_name) && !empty($s->grade_level_name)): ?>
                                        (<?= htmlspecialchars($s->grade_level_name) ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Adviser</label>
                        <select class="form-select" name="adviser_id">
                            <option value="">Select Adviser</option>
                            <?php foreach ($advisers as $a): ?>
                                <?php 
                                $is_selected = false;
                                // Match by staff_id if available (when using staff table), otherwise by user_id
                                if (!empty($current_section) && isset($current_section->adviser_staff_id) && isset($a->staff_id) && $current_section->adviser_staff_id == $a->staff_id) {
                                    $is_selected = true;
                                } elseif (!empty($current_section) && isset($current_section->adviser_user_id) && $current_section->adviser_user_id == $a->id) {
                                    $is_selected = true;
                                } elseif (!empty($current_section) && isset($current_section->adviser_id) && $current_section->adviser_id == $a->id) {
                                    $is_selected = true;
                                }
                                ?>
                                <option value="<?= $a->id ?>" <?= $is_selected ? 'selected' : '' ?>><?= htmlspecialchars($a->last_name . ', ' . $a->first_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Update Enrollment</button>
                    <a href="<?= site_url('enrollment') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
