<div class="row">
    <div class="col-12">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-book-fill me-2"></i>Subjects</h5>
                <div>
                    <select id="programFilter" class="form-select form-select-sm" style="display:inline-block;width:auto;margin-right:10px;">
                        <option value="">All Programs</option>
                        <?php foreach ($programs as $p): ?>
                            <option value="<?= $p->id ?>" <?= ($filter_program == $p->id) ? 'selected' : '' ?>><?= $p->code ?> - <?= $p->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="semesterFilter" class="form-select form-select-sm" style="display:inline-block;width:auto;margin-right:10px;">
                        <option value="">All Semesters</option>
                        <option value="1st_sem" <?= ($filter_semester == '1st_sem') ? 'selected' : '' ?>>First Semester</option>
                        <option value="2nd_sem" <?= ($filter_semester == '2nd_sem') ? 'selected' : '' ?>>Second Semester</option>
                    </select>
                    <a href="<?= site_url('subjects/create') ?>" class="btn-primary-custom btn-sm">
                        <i class="bi bi-plus-lg"></i> Add Subject
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Program/Grade Level</th>
                            <th>Semester</th>
                            <th>Units</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($subjects)): ?>
                            <?php foreach ($subjects as $s): ?>
                                <tr>
                                    <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                                    <td style="font-weight:600;"><?= $s->description ?></td>
                                    <td style="color:#64748b;"><?= $s->program_code ? $s->program_code . ' - ' . $s->program_name : ($s->grade_level_name ? $s->grade_level_name : '-') ?></td>
                                    <td style="color:#64748b;">
                                        <?php if ($s->semester_type == '1st_sem'): ?>First Semester
                                        <?php elseif ($s->semester_type == '2nd_sem'): ?>Second Semester
                                        <?php else: ?>-<?php endif; ?>
                                    </td>
                                    <td style="color:#64748b;"><?= $s->units ?></td>
                                    <td>
                                        <a href="<?= site_url('course/content/' . $s->id) ?>" class="btn-action btn-view" title="Manage Course Content" style="background:#dbeafe;color:#1e40af;border-color:#bfdbfe;">
                                            <i class="bi bi-folder-fill"></i>
                                        </a>
                                        <a href="<?= site_url('subjects/view/' . $s->id) ?>" class="btn-action btn-view" title="View">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="<?= site_url('subjects/edit/' . $s->id) ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="<?= site_url('subjects/delete/' . $s->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this subject?');">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center" style="color:#94a3b8;padding:2rem;">No subjects found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('programFilter').addEventListener('change', function() {
    const programId = this.value;
    const semesterId = document.getElementById('semesterFilter').value;
    window.location.href = '<?= site_url('subjects') ?>?program_id=' + programId + '&semester_type=' + semesterId;
});

document.getElementById('semesterFilter').addEventListener('change', function() {
    const semesterId = this.value;
    const programId = document.getElementById('programFilter').value;
    window.location.href = '<?= site_url('subjects') ?>?program_id=' + programId + '&semester_type=' + semesterId;
});
</script>
