<div class="mb-3">
    <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to Course
    </a>
</div>

<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-people me-2"></i>Submissions: <?= htmlspecialchars($quiz->title) ?></h5>
        <div style="color:#64748b;font-size:0.85rem;"><?= $quiz->total_points ?> total points</div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th class="text-center">Attempt</th>
                    <th class="text-center">Score</th>
                    <th class="text-center">Percentage</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th style="width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attempts)): ?>
                    <?php foreach ($attempts as $a): ?>
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($a->student_name) ?></div>
                            <div style="color:#94a3b8;font-size:0.75rem;"><?= $a->lrn ?: $a->stud_id_num ?></div>
                        </td>
                        <td class="text-center">#<?= $a->attempt_number ?></td>
                        <td class="text-center" style="font-weight:700;"><?= $a->score !== null ? $a->score : '-' ?> / <?= $a->total_points ?></td>
                        <td class="text-center">
                            <?php if ($a->percentage !== null): ?>
                                <?php $color = $a->percentage >= 75 ? '#10b981' : ($a->percentage >= 50 ? '#f59e0b' : '#ef4444'); ?>
                                <span style="color:<?= $color ?>;font-weight:700;"><?= $a->percentage ?>%</span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $status_badges = array('in_progress' => 'badge-inactive', 'submitted' => 'badge-user', 'graded' => 'badge-active');
                            $badge = isset($status_badges[$a->status]) ? $status_badges[$a->status] : 'badge-inactive';
                            ?>
                            <span class="badge-status <?= $badge ?>"><?= ucfirst(str_replace('_', ' ', $a->status)) ?></span>
                        </td>
                        <td style="color:#64748b;font-size:0.82rem;"><?= $a->submitted_at ? date('M d, Y g:i A', strtotime($a->submitted_at)) : '-' ?></td>
                        <td>
                            <a href="<?= site_url('quizzes/result/' . $a->id) ?>" class="btn-action" title="Review"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-5" style="color:#94a3b8;">No submissions yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
