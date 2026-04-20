<div class="mb-3">
    <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
    </a>
</div>

<!-- Course Header -->
<div class="p-4 mb-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
    <div>
        <h4 style="font-weight:700;margin:0;"><i class="bi bi-graph-up me-2" style="color:#6366f1;"></i>Student Progress: <?= htmlspecialchars($course->title) ?></h4>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;">Monitor enrolled students and their lesson completion status.</p>
    </div>
</div>

<!-- Students Table -->
<div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
    <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
        <h6 style="margin:0;font-weight:700;font-size:0.9rem;">
            <i class="bi bi-people-fill me-2" style="color:#6366f1;"></i>Enrolled Students (<?= count($students) ?>)
        </h6>
    </div>
    <?php if (empty($students)): ?>
    <div class="p-4 text-center" style="color:#94a3b8;">No students enrolled in this course yet.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:0.85rem;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="border:none;padding:0.75rem 1rem;">Student</th>
                    <th style="border:none;padding:0.75rem 1rem;">Email</th>
                    <th style="border:none;padding:0.75rem 1rem;">Progress</th>
                    <th style="border:none;padding:0.75rem 1rem;">Lessons Completed</th>
                    <th style="border:none;padding:0.75rem 1rem;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td style="padding:0.75rem 1rem;vertical-align:middle;">
                        <div style="font-weight:600;color:#0f172a;"><?= htmlspecialchars($s->name) ?></div>
                    </td>
                    <td style="padding:0.75rem 1rem;vertical-align:middle;color:#64748b;"><?= htmlspecialchars($s->email) ?></td>
                    <td style="padding:0.75rem 1rem;vertical-align:middle;">
                        <div style="width:120px;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:0.75rem;"><?= $s->progress_percent ?>%</span>
                            </div>
                            <div style="height:6px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                <div style="height:100%;width:<?= $s->progress_percent ?>%;background:<?= $s->progress_percent >= 100 ? '#10b981' : '#6366f1' ?>;border-radius:4px;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:0.75rem 1rem;vertical-align:middle;">
                        <span style="font-weight:600;color:#0f172a;"><?= $s->lessons_completed ?></span> / <?= $total_lessons ?>
                    </td>
                    <td style="padding:0.75rem 1rem;vertical-align:middle;">
                        <?php if ($s->progress_percent >= 100): ?>
                            <span class="badge-status badge-active">Completed</span>
                        <?php elseif ($s->progress_percent > 0): ?>
                            <span style="background:#fef3c7;color:#d97706;padding:0.25rem 0.5rem;border-radius:6px;font-size:0.72rem;font-weight:600;">In Progress</span>
                        <?php else: ?>
                            <span style="background:#f1f5f9;color:#64748b;padding:0.25rem 0.5rem;border-radius:6px;font-size:0.72rem;font-weight:600;">Not Started</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Detailed Lesson Matrix -->
<?php if (!empty($students) && !empty($modules)): ?>
<div class="mt-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
    <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
        <h6 style="margin:0;font-weight:700;font-size:0.9rem;">
            <i class="bi bi-check2-square me-2" style="color:#6366f1;"></i>Lesson Completion Matrix
        </h6>
    </div>
    <div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-sm mb-0" style="font-size:0.75rem;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="border:none;padding:0.5rem 0.75rem;min-width:150px;">Student</th>
                    <?php foreach ($modules as $m): ?>
                        <?php foreach ($m->lessons as $l): ?>
                        <th style="border:none;padding:0.5rem 0.75rem;min-width:80px;text-align:center;">
                            <?= htmlspecialchars(substr($l->title, 0, 15)) ?>...
                        </th>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td style="padding:0.5rem 0.75rem;font-weight:600;color:#0f172a;"><?= htmlspecialchars(substr($s->name, 0, 20)) ?></td>
                    <?php foreach ($modules as $m): ?>
                        <?php foreach ($m->lessons as $l): ?>
                        <td style="padding:0.5rem 0.75rem;text-align:center;">
                            <?php if (in_array($l->id, $s->completed_ids)): ?>
                                <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:1rem;"></i>
                            <?php else: ?>
                                <i class="bi bi-circle" style="color:#e2e8f0;font-size:1rem;"></i>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
