<div class="mb-3">
    <a href="javascript:history.back()" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;margin:0;"><i class="bi bi-clipboard-check me-2" style="color:#6366f1;"></i>Assessments: <?= htmlspecialchars($class_program->subject_name) ?></h5>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;"><?= $class_program->section_name ?></p>
    </div>
    <?php if ($can_edit): ?>
    <a href="<?= site_url('quizzes/create/' . $class_program->id) ?>" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Create Assessment
    </a>
    <?php endif; ?>
</div>

<?php if (empty($quizzes)): ?>
<div class="text-center py-5">
    <i class="bi bi-clipboard" style="font-size:3rem;color:#cbd5e1;"></i>
    <p style="color:#94a3b8;margin-top:0.75rem;">No assessments yet.</p>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($quizzes as $q): ?>
    <div class="col-md-6 col-lg-4">
        <div class="p-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;height:100%;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <?php
                $type_colors = array('quiz' => '#6366f1', 'exam' => '#ef4444', 'assignment' => '#f59e0b', 'activity' => '#10b981');
                $color = isset($type_colors[$q->quiz_type]) ? $type_colors[$q->quiz_type] : '#6366f1';
                ?>
                <span style="background:<?= $color ?>15;color:<?= $color ?>;padding:0.25rem 0.75rem;border-radius:8px;font-size:0.75rem;font-weight:600;text-transform:uppercase;">
                    <?= $q->quiz_type ?>
                </span>
                <?php if (!$q->is_published): ?>
                    <span class="badge-status badge-inactive">Draft</span>
                <?php else: ?>
                    <span class="badge-status badge-active">Published</span>
                <?php endif; ?>
            </div>
            <h6 style="font-weight:700;margin-bottom:0.5rem;"><?= htmlspecialchars($q->title) ?></h6>
            <?php if ($q->description): ?>
                <p style="color:#64748b;font-size:0.82rem;margin-bottom:0.75rem;"><?= character_limiter(strip_tags($q->description), 80) ?></p>
            <?php endif; ?>
            <div style="font-size:0.78rem;color:#94a3b8;margin-bottom:1rem;">
                <span><i class="bi bi-question-circle me-1"></i><?= $q->question_count ?> questions</span>
                <span class="ms-2"><i class="bi bi-star me-1"></i><?= $q->total_points ?: 0 ?> pts</span>
                <?php if ($q->time_limit_minutes): ?>
                    <span class="ms-2"><i class="bi bi-clock me-1"></i><?= $q->time_limit_minutes ?> min</span>
                <?php endif; ?>
            </div>
            <div class="d-flex gap-2">
                <?php if ($can_edit): ?>
                    <a href="<?= site_url('quizzes/questions/' . $q->id) ?>" class="btn-primary-custom btn-sm"><i class="bi bi-list-ol"></i> Questions</a>
                    <a href="<?= site_url('quizzes/attempts/' . $q->id) ?>" class="btn btn-light btn-sm" style="border-radius:8px;font-size:0.8rem;"><i class="bi bi-people"></i> Submissions</a>
                    <a href="<?= site_url('quizzes/edit/' . $q->id) ?>" class="btn-action" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="<?= site_url('quizzes/delete/' . $q->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this assessment?');"><i class="bi bi-trash"></i></a>
                <?php else: ?>
                    <?php if ($q->is_published): ?>
                        <a href="<?= site_url('quizzes/take/' . $q->id) ?>" class="btn-primary-custom btn-sm"><i class="bi bi-pencil-square"></i> Take</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
