<div class="mb-3">
    <a href="<?= site_url('attendance') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to Attendance
    </a>
</div>
<div class="data-table">
    <div class="table-header">
        <h5>
            <i class="bi bi-person-badge-fill me-2"></i>
            <?= htmlspecialchars($student->first_name) ?> <?= htmlspecialchars($student->last_name) ?>
            <small style="color:#94a3b8;font-weight:400;">— Attendance History</small>
        </h5>
    </div>

    <?php if (!empty($records)): ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                    <tr>
                        <td style="font-weight:600;"><?= date('F j, Y', strtotime($r->date)) ?></td>
                        <td><?= $r->login_time ? date('g:ia', strtotime($r->login_time)) : '-' ?></td>
                        <td><?= $r->logout_time ? date('g:ia', strtotime($r->logout_time)) : '<span style="color:#f59e0b;font-weight:600;">No logout</span>' ?></td>
                        <td>
                            <?php if ($r->duration_minutes > 0): ?>
                                <?php
                                $hours = floor($r->duration_minutes / 60);
                                $mins = $r->duration_minutes % 60;
                                echo $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
                                ?>
                            <?php elseif ($r->logout_time): ?>
                                <span style="color:#94a3b8;">-</span>
                            <?php else: ?>
                                <span style="color:#6366f1;font-weight:600;">Active</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="p-5 text-center" style="color:#94a3b8;">
        <i class="bi bi-calendar-x" style="font-size:2.5rem;"></i>
        <p style="margin-top:0.75rem;">No attendance records for this student.</p>
    </div>
    <?php endif; ?>
</div>
