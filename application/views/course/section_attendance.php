<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="javascript:history.back()" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-calendar-check me-2"></i>Section Attendance: <?= $section->section_name ?></h5>
            </div>
            <?php if (empty($students)): ?>
                <div class="p-5 text-center" style="color:#94a3b8;">
                    <i class="bi bi-calendar-check" style="font-size:4rem;display:block;margin-bottom:1.5rem;"></i>
                    <h5 style="color:#64748b;margin-bottom:1rem;">No Students Enrolled</h5>
                    <p style="max-width:400px;margin:0 auto;">There are no students enrolled in this section yet.</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Attendance Rate</th>
                            <th>Days Present</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student->name ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student->email ?? 'N/A') ?></td>
                                <td>
                                    <div class="progress" style="height:10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width:<?= $student->attendance_percent ?? 0 ?>%;" aria-valuenow="<?= $student->attendance_percent ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted"><?= $student->attendance_percent ?? 0 ?>%</small>
                                </td>
                                <td><?= $student->days_present ?? 0 ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
