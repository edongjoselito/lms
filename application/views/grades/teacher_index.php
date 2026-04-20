<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-journal-check me-2"></i>My Classes</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Section</th>
                    <th>Level</th>
                    <th>Schedule</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($classes)): ?>
                    <?php foreach ($classes as $c): ?>
                        <tr>
                            <td style="font-weight:600;">
                                <span class="badge-role badge-user me-1"><?= $c->subject_code ?></span>
                                <?= htmlspecialchars($c->subject_name) ?>
                            </td>
                            <td style="color:#64748b;"><?= $c->section_name ?></td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?= isset($c->grade_level_name) ? $c->grade_level_name : '' ?>
                                <?= isset($c->program_code) ? $c->program_code : '' ?>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?= $c->schedule_day ?: '' ?> <?= $c->schedule_time_start ? date('g:ia', strtotime($c->schedule_time_start)) : '' ?>
                            </td>
                            <td>
                                <a href="<?= site_url('grades/class_record/' . $c->id) ?>" class="btn-primary-custom btn-sm">
                                    <i class="bi bi-journal-check"></i> Grades
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-5" style="color:#94a3b8;">No classes assigned.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
