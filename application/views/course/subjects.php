<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('course') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-book-fill me-2"></i>Manage Subjects & Content</h5>
                <a href="<?= site_url('academic/subjects') ?>" class="btn-primary-custom" style="padding:0.4rem 0.8rem;font-size:0.8rem;">
                    <i class="bi bi-plus-lg me-1"></i>Add Subject
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="data-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Grade Level/Program</th>
                            <th>Semester</th>
                            <th style="width:150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($subjects)): ?>
                            <?php foreach ($subjects as $s): ?>
                                <tr>
                                    <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                                    <td style="font-weight:600;"><?= $s->description ?></td>
                                    <td style="color:#64748b;">
                                        <?php if ($s->grade_level_id): ?>
                                            <?php foreach ($grade_levels as $gl): ?>
                                                <?php if ($gl->id == $s->grade_level_id): ?>
                                                    <?= $gl->name ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php elseif ($s->program_id): ?>
                                            <?php foreach ($programs as $p): ?>
                                                <?php if ($p->id == $s->program_id): ?>
                                                    <?= $p->code ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td style="color:#64748b;">
                                        <?php if ($s->semester_type == '1st_sem'): ?>First Semester
                                        <?php elseif ($s->semester_type == '2nd_sem'): ?>Second Semester
                                        <?php else: ?>-<?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('course/content/' . $s->id) ?>" 
                                           class="btn-action btn-edit" title="Manage Content">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="<?= site_url('academic/edit_subject/' . $s->id) ?>" 
                                           class="btn-action btn-view" title="Edit Subject">
                                            <i class="bi bi-gear-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color:#94a3b8;">No subjects found. <a href="<?= site_url('academic/subjects') ?>">Add a subject</a>.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
