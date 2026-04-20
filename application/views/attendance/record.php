<div class="mb-3">
    <a href="<?= site_url('attendance') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>
<div class="data-table">
    <div class="table-header">
        <h5>
            <i class="bi bi-calendar-check-fill me-2"></i>
            <?= htmlspecialchars($course->title) ?>
            <?php if ($course->code): ?>
                <small style="color:#94a3b8;font-weight:400;">— <?= htmlspecialchars($course->code) ?></small>
            <?php endif; ?>
        </h5>
        <form class="d-flex gap-2 align-items-center" method="get" action="<?= site_url('attendance/record/' . $course->id) ?>">
            <input type="date" class="form-control form-control-sm" name="date" value="<?= $date ?>" style="width:160px;border-radius:8px;">
            <button type="submit" class="btn btn-dark btn-sm" style="border-radius:8px;">Go</button>
        </form>
    </div>

    <form action="<?= site_url('attendance/record/' . $course->id) ?>" method="post">
        <input type="hidden" name="attendance_date" value="<?= $date ?>">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">Absent</th>
                        <th class="text-center">Late</th>
                        <th class="text-center">Excused</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php $i = 1; foreach ($students as $s): ?>
                            <?php
                            $att = isset($att_records[$s->user_id]) ? $att_records[$s->user_id] : null;
                            $current_status = $att ? $att->status : 'present';
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td style="font-weight:600;"><?= htmlspecialchars($s->name) ?></td>
                                <td class="text-center"><input type="radio" name="status[<?= $s->user_id ?>]" value="present" <?= ($current_status == 'present') ? 'checked' : '' ?>></td>
                                <td class="text-center"><input type="radio" name="status[<?= $s->user_id ?>]" value="absent" <?= ($current_status == 'absent') ? 'checked' : '' ?>></td>
                                <td class="text-center"><input type="radio" name="status[<?= $s->user_id ?>]" value="late" <?= ($current_status == 'late') ? 'checked' : '' ?>></td>
                                <td class="text-center"><input type="radio" name="status[<?= $s->user_id ?>]" value="excused" <?= ($current_status == 'excused') ? 'checked' : '' ?>></td>
                                <td><input type="text" class="form-control form-control-sm" name="remarks[<?= $s->user_id ?>]" value="<?= $att ? htmlspecialchars($att->remarks) : '' ?>" style="font-size:0.8rem;border-radius:8px;"></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5" style="color:#94a3b8;">No students enrolled in this course.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($students)): ?>
        <div class="p-3 d-flex justify-content-end" style="border-top:1px solid #e2e8f0;">
            <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Attendance</button>
        </div>
        <?php endif; ?>
    </form>
</div>
