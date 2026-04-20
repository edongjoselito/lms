<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-calendar-check-fill me-2"></i>Attendance</h5>
    </div>
    <?php if (!empty($classes)): ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Section</th>
                    <th>Schedule</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $c): ?>
                    <tr>
                        <td style="font-weight:600;">
                            <span class="badge-role badge-user me-1"><?= $c->subject_code ?></span>
                            <?= htmlspecialchars($c->subject_name) ?>
                        </td>
                        <td style="color:#64748b;"><?= $c->section_name ?></td>
                        <td style="color:#64748b;font-size:0.85rem;"><?= $c->schedule_day ?: '' ?> <?= $c->schedule_time_start ? date('g:ia', strtotime($c->schedule_time_start)) : '' ?></td>
                        <td>
                            <a href="<?= site_url('attendance/record/' . $c->id) ?>" class="btn-primary-custom btn-sm">
                                <i class="bi bi-calendar-check"></i> Record
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php elseif (!empty($sections)): ?>
    <div class="p-4">
        <p style="color:#64748b;font-size:0.9rem;">Select a section to manage attendance.</p>
        <div class="row g-3">
            <?php foreach ($sections as $sec): ?>
                <div class="col-md-4">
                    <div class="p-3" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
                        <div style="font-weight:700;"><?= htmlspecialchars($sec->name) ?></div>
                        <div style="color:#64748b;font-size:0.8rem;">
                            <?= isset($sec->grade_level_name) ? $sec->grade_level_name : '' ?>
                            <?= isset($sec->program_code) ? $sec->program_code : '' ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="p-5 text-center" style="color:#94a3b8;">No classes available.</div>
    <?php endif; ?>
</div>
