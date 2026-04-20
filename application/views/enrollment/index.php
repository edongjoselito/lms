<?php $sy_label = isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A'; ?>
<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-clipboard-check-fill me-2"></i>Enrollment <small style="color:#94a3b8;font-weight:400;">(S.Y. <?= $sy_label ?>)</small></h5>
        <div class="d-flex gap-2">
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= site_url('enrollment') ?>" class="btn <?= empty($filter_type) ? 'btn-dark' : 'btn-outline-secondary' ?>" style="border-radius:8px 0 0 8px;font-size:0.8rem;">All</a>
                <a href="<?= site_url('enrollment?system_type=deped') ?>" class="btn <?= ($filter_type == 'deped') ? 'btn-dark' : 'btn-outline-secondary' ?>" style="font-size:0.8rem;">DepEd</a>
                <a href="<?= site_url('enrollment?system_type=ched') ?>" class="btn <?= ($filter_type == 'ched') ? 'btn-dark' : 'btn-outline-secondary' ?>" style="border-radius:0 8px 8px 0;font-size:0.8rem;">CHED</a>
            </div>
            <a href="<?= site_url('enrollment/enroll') ?>" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Enroll</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>LRN / Student ID</th>
                    <th>System</th>
                    <th>Level / Program</th>
                    <th>Section</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($enrollments)): ?>
                    <?php foreach ($enrollments as $e): ?>
                        <tr>
                            <td style="font-weight:600;"><?= htmlspecialchars($e->student_name) ?></td>
                            <td style="color:#64748b;font-size:0.85rem;"><?= $e->lrn ?: ($e->stud_id_num ?: '—') ?></td>
                            <td>
                                <span class="badge-role <?= ($e->system_type == 'ched') ? 'badge-admin' : 'badge-user' ?>">
                                    <?= strtoupper($e->system_type) ?>
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?= isset($e->grade_level_name) ? $e->grade_level_name : '' ?>
                                <?= isset($e->program_code) ? $e->program_code : '' ?>
                                <?= $e->year_level ? '/ Year ' . $e->year_level : '' ?>
                            </td>
                            <td style="color:#64748b;"><?= isset($e->section_name) && $e->section_name ? $e->section_name : '—' ?></td>
                            <td>
                                <?php
                                $badge_map = array('enrolled' => 'badge-active', 'pending' => 'badge-inactive', 'dropped' => 'badge-inactive', 'completed' => 'badge-active');
                                ?>
                                <span class="badge-status <?= isset($badge_map[$e->status]) ? $badge_map[$e->status] : 'badge-inactive' ?>">
                                    <?= ucfirst($e->status) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5" style="color:#94a3b8;">No enrollments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
