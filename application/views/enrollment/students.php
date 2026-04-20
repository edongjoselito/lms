<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-person-badge-fill me-2"></i>Students</h5>
        <a href="<?= site_url('enrollment/create_student') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Register Student
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>LRN / Student ID</th>
                    <th>System</th>
                    <th>Level / Program</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.75rem;flex-shrink:0;">
                                        <?= strtoupper(substr($s->first_name, 0, 1) . substr($s->last_name, 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($s->full_name) ?></div>
                                        <div style="color:#94a3b8;font-size:0.78rem;"><?= $s->email ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;"><?= $s->lrn ?: ($s->student_id ?: '—') ?></td>
                            <td>
                                <span class="badge-role <?= ($s->system_type == 'ched') ? 'badge-admin' : 'badge-user' ?>">
                                    <?= strtoupper($s->system_type) ?>
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?= isset($s->grade_level_name) ? $s->grade_level_name : '' ?>
                                <?= isset($s->program_code) ? $s->program_code : '' ?>
                            </td>
                            <td>
                                <span class="badge-status <?= ($s->status == 'active') ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= ucfirst($s->status) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-5" style="color:#94a3b8;">No students found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
