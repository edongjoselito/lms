<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <div>
                <h5 style="font-weight:700;margin-bottom:0.5rem;">Enrollment Dashboard</h5>
                <p style="color:#64748b;margin:0;">View all enrolled students and enrollment statistics</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="form-card" style="background:linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);color:white;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.875rem;opacity:0.9;margin-bottom:0.5rem;">Total Enrolled</div>
                    <div style="font-size:2rem;font-weight:700;"><?= $stats['total_enrolled'] ?></div>
                </div>
                <i class="bi bi-people-fill" style="font-size:3rem;opacity:0.3;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-card" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%);color:white;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.875rem;opacity:0.9;margin-bottom:0.5rem;">Total Sections</div>
                    <div style="font-size:2rem;font-weight:700;"><?= $stats['total_sections'] ?></div>
                </div>
                <i class="bi bi-grid-fill" style="font-size:3rem;opacity:0.3;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-card" style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);color:white;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.875rem;opacity:0.9;margin-bottom:0.5rem;">Grade Levels</div>
                    <div style="font-size:2rem;font-weight:700;"><?= $stats['total_grade_levels'] ?></div>
                </div>
                <i class="bi bi-layers-fill" style="font-size:3rem;opacity:0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap;">
                <h5 style="font-weight:700;margin:0;">Enrolled Students</h5>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#enrollStudentModal" style="border-radius:10px;font-weight:600;">
                    <i class="bi bi-plus-lg me-1"></i> New Enrollment
                </button>
            </div>
            <?php if (empty($enrollments)): ?>
                <div style="text-align:center;padding:3rem 1rem;">
                    <i class="bi bi-inbox" style="font-size:4rem;color:#cbd5e1;display:block;margin-bottom:1rem;"></i>
                    <h5 style="color:#64748b;margin-bottom:0.5rem;">No Enrollments Yet</h5>
                    <p style="color:#94a3b8;max-width:400px;margin:0 auto 1.25rem;">No students have been enrolled yet. Use the button below to enroll a student from Student Profiles without leaving this page.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollStudentModal" style="border-radius:10px;font-weight:600;padding:0.7rem 1.2rem;">
                        <i class="bi bi-person-plus-fill me-1"></i> Enroll Student
                    </button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" style="margin:0;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Student Number</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Name</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Birth Date</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Grade Level</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Section</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">School Year</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Status</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $e): ?>
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:0.75rem 1rem;font-weight:500;"><?= htmlspecialchars($e->student_number ?? '') ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?= htmlspecialchars(($e->last_name ?? '') . ', ' . ($e->first_name ?? '')) ?>
                                        <?php if ($e->middle_name): ?>
                                            <?= ' ' . htmlspecialchars(substr($e->middle_name, 0, 1) . '.') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($e->birth_date ?? '') ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?php if (isset($e->year_level) && $e->year_level): ?>
                                            <?= 'Grade ' . str_pad($e->year_level, 2, '0', STR_PAD_LEFT) ?>
                                        <?php elseif (isset($e->grade_level_name) && $e->grade_level_name): ?>
                                            <?= htmlspecialchars($e->grade_level_name) ?>
                                        <?php elseif ($e->grade_level_id && (int) $e->grade_level_id <= 12): ?>
                                            <?= 'Grade ' . str_pad($e->grade_level_id, 2, '0', STR_PAD_LEFT) ?>
                                        <?php elseif ($e->grade_level_id): ?>
                                            -
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;"><?= isset($e->section_name) ? htmlspecialchars($e->section_name) : '-' ?></td>
                                    <td style="padding:0.75rem 1rem;"><?= isset($e->school_year_name) ? htmlspecialchars($e->school_year_name) : '-' ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?php if ($e->status == 'enrolled'): ?>
                                            <span style="background:#dcfce7;color:#15803d;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:500;">Enrolled</span>
                                        <?php else: ?>
                                            <span style="background:#fee2e2;color:#dc2626;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:500;"><?= ucfirst($e->status) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;text-align:right;">
                                        <div class="dropdown" style="position:relative;">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" style="border-radius:8px;padding:0.4rem 0.8rem;font-size:0.8rem;">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="z-index:9999;">
                                                <li><a class="dropdown-item" href="<?= site_url('enrollment/edit/' . $e->id) ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item text-danger" href="<?= site_url('enrollment/delete/' . $e->id) ?>" onclick="return confirm('Delete this enrollment?');"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($grade_level_counts)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">Enrollment by Grade Level</h5>
            <div class="row g-3">
                <?php foreach ($grade_level_counts as $glc): ?>
                    <?php
                    $grade_level_value = isset($glc->year_level) && $glc->year_level
                        ? (int) $glc->year_level
                        : (isset($glc->grade_level_id) ? (int) $glc->grade_level_id : 0);
                    ?>
                    <div class="col-md-3 col-sm-6">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem;text-align:center;position:relative;">
                            <?php if ($grade_level_value > 0): ?>
                                <a href="<?= site_url('enrollment/grade_level_sections/' . $grade_level_value) ?>" title="View enrollees" style="position:absolute;top:0.9rem;right:0.9rem;width:34px;height:34px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;color:#2563eb;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            <?php endif; ?>
                            <div style="font-size:2rem;font-weight:700;color:#6366f1;margin-bottom:0.5rem;"><?= $glc->count ?></div>
                            <div style="font-size:0.85rem;color:#64748b;font-weight:500;">
                                <?php if (isset($glc->year_level) && $glc->year_level): ?>
                                    Grade <?= str_pad($glc->year_level, 2, '0', STR_PAD_LEFT) ?>
                                <?php elseif (isset($glc->grade_level_id) && $glc->grade_level_id && (int) $glc->grade_level_id <= 12): ?>
                                    Grade <?= str_pad($glc->grade_level_id, 2, '0', STR_PAD_LEFT) ?>
                                <?php else: ?>
                                    Unassigned
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="modal fade" id="enrollStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:18px;border:0;overflow:hidden;">
            <form action="<?= site_url('enrollment/create') ?>" method="post" id="enrollmentCreateForm">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-header" style="background:linear-gradient(135deg, #13367a 0%, #2563eb 100%);color:#fff;border:0;">
                    <div>
                        <h5 class="modal-title mb-1">Enroll Student</h5>
                        <p class="mb-0" style="opacity:0.85;font-size:0.9rem;">Search a student profile, then assign grade level and section.</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Student Profile <span style="color:red;">*</span></label>
                            <select class="form-select" name="profile_id" id="enrollmentProfileId" required style="width:100%;">
                                <option value="">Search student number or name</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div id="selectedStudentPreview" style="display:none;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:1rem 1.1rem;">
                                <div style="font-size:0.78rem;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#64748b;margin-bottom:0.45rem;">Selected Student</div>
                                <div id="selectedStudentName" style="font-size:1rem;font-weight:700;color:#0f172a;"></div>
                                <div id="selectedStudentMeta" style="font-size:0.9rem;color:#64748b;margin-top:0.2rem;"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Grade Level <span style="color:red;">*</span></label>
                            <select class="form-select" name="grade_level_id" id="enrollmentGradeLevel" required>
                                <option value="">Select Grade Level</option>
                                <?php foreach ($grade_levels as $gl): ?>
                                    <?php
                                    if (isset($gl->year_level) && $gl->year_level) {
                                        $grade_label = 'Grade ' . str_pad($gl->year_level, 2, '0', STR_PAD_LEFT);
                                    } else {
                                        $grade_label = $gl->name;
                                        if (is_numeric($grade_label) && $grade_label >= 1 && $grade_label <= 12) {
                                            $grade_label = 'Grade ' . str_pad($grade_label, 2, '0', STR_PAD_LEFT);
                                        }
                                    }
                                    ?>
                                    <option value="<?= $gl->id ?>"><?= htmlspecialchars($grade_label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Section <span style="color:red;">*</span></label>
                            <select class="form-select" name="section_id" id="enrollmentSection" required>
                                <option value="">Select Section</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?= $section->id ?>"
                                        data-program="<?= isset($section->program_id) ? (int) $section->program_id : '' ?>"
                                        data-adviser="<?= isset($section->adviser_user_id) ? (int) $section->adviser_user_id : '' ?>"
                                        data-adviser-name="<?= isset($section->adviser_name) ? htmlspecialchars($section->adviser_name, ENT_QUOTES, 'UTF-8') : '' ?>">
                                        <?= htmlspecialchars($section->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Adviser</label>
                            <select class="form-select" name="adviser_id" id="enrollmentAdviser">
                                <option value="">Select Adviser</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Save Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    table {
        min-width: 800px;
    }
}

.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 0.75rem;
    color: #212529;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 6px;
}

.select2-dropdown {
    border: 1px solid #dbeafe;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
}
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalElement = document.getElementById('enrollStudentModal');
    var gradeLevelSelect = document.getElementById('enrollmentGradeLevel');
    var sectionSelect = document.getElementById('enrollmentSection');
    var adviserSelect = document.getElementById('enrollmentAdviser');
    var previewElement = document.getElementById('selectedStudentPreview');
    var previewNameElement = document.getElementById('selectedStudentName');
    var previewMetaElement = document.getElementById('selectedStudentMeta');
    var formElement = document.getElementById('enrollmentCreateForm');
    var allSectionOptions = [];

    sectionSelect.querySelectorAll('option').forEach(function(option) {
        allSectionOptions.push({
            value: option.value,
            text: option.text,
            program: option.getAttribute('data-program') || '',
            adviserId: option.getAttribute('data-adviser') || '',
            adviserName: option.getAttribute('data-adviser-name') || ''
        });
    });

    function renderSections(selectedGradeLevel) {
        var currentValue = sectionSelect.value;
        sectionSelect.innerHTML = '<option value="">Select Section</option>';

        allSectionOptions.forEach(function(option) {
            if (!option.value) {
                return;
            }

            if (!selectedGradeLevel || option.program === selectedGradeLevel) {
                var element = document.createElement('option');
                element.value = option.value;
                element.text = option.text;
                element.setAttribute('data-program', option.program);
                element.setAttribute('data-adviser', option.adviserId);
                element.setAttribute('data-adviser-name', option.adviserName);
                if (option.value === currentValue) {
                    element.selected = true;
                }
                sectionSelect.appendChild(element);
            }
        });

        if (sectionSelect.options.length === 1) {
            var emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.text = 'No sections available for this grade level';
            sectionSelect.appendChild(emptyOption);
        }
    }

    function syncAdviserFromSection() {
        var selectedOption = sectionSelect.options[sectionSelect.selectedIndex];
        adviserSelect.innerHTML = '<option value="">Select Adviser</option>';

        if (!selectedOption) {
            return;
        }

        var adviserId = selectedOption.getAttribute('data-adviser');
        var adviserName = selectedOption.getAttribute('data-adviser-name');

        if (!adviserId) {
            return;
        }

        var adviserOption = document.createElement('option');
        adviserOption.value = adviserId;
        adviserOption.text = adviserName || 'Adviser';
        adviserSelect.add(adviserOption);
        adviserSelect.value = adviserId;
    }

    function resetStudentPreview() {
        previewElement.style.display = 'none';
        previewNameElement.textContent = '';
        previewMetaElement.textContent = '';
    }

    function showStudentPreview(data) {
        var metaParts = [];
        if (data.student_number) {
            metaParts.push(data.student_number);
        }
        if (data.birth_date) {
            metaParts.push(data.birth_date);
        }
        if (data.email) {
            metaParts.push(data.email);
        }

        previewNameElement.textContent = data.name || data.text || '';
        previewMetaElement.textContent = metaParts.join(' | ');
        previewElement.style.display = 'block';
    }

    gradeLevelSelect.addEventListener('change', function() {
        renderSections(this.value);
        adviserSelect.value = '';
    });

    sectionSelect.addEventListener('change', syncAdviserFromSection);

    modalElement.addEventListener('hidden.bs.modal', function() {
        formElement.reset();
        renderSections('');
        adviserSelect.value = '';
        resetStudentPreview();
        if (window.jQuery && window.jQuery.fn.select2) {
            window.jQuery('#enrollmentProfileId').val(null).trigger('change');
        }
    });

    if (window.jQuery && window.jQuery.fn.select2) {
        window.jQuery('#enrollmentProfileId').select2({
            dropdownParent: window.jQuery('#enrollStudentModal'),
            width: '100%',
            placeholder: 'Search student number or name',
            minimumInputLength: 1,
            ajax: {
                url: '<?= site_url('enrollment/search_studentprofiles') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || ''
                    };
                },
                processResults: function(data) {
                    return data;
                },
                cache: true
            }
        });

        window.jQuery('#enrollmentProfileId').on('select2:select', function(event) {
            showStudentPreview(event.params.data);
        });

        window.jQuery('#enrollmentProfileId').on('select2:clear', resetStudentPreview);
    }

    renderSections('');
});
</script>
