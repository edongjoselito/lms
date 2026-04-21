<div class="mb-3">
    <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
    </a>
</div>

<?php if (isset($has_attempts) && $has_attempts): ?>
<div class="mb-4 p-3" style="background:#fef3c7;border-radius:10px;border:1px solid #f59e0b;">
    <div style="color:#b45309;font-size:0.85rem;font-weight:600;">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> This assessment has <?= $attempt_count ?> student attempt(s). Questions cannot be modified after students have taken the quiz.
    </div>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;margin:0;"><i class="bi bi-list-ol me-2" style="color:#6366f1;"></i><?= htmlspecialchars($quiz->title) ?></h5>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;">
            <?= count($questions) ?> question<?= count($questions) != 1 ? 's' : '' ?> &middot; <?= $quiz->total_points ?: 0 ?> total points
        </p>
    </div>
    <?php if (!isset($has_attempts) || !$has_attempts): ?>
    <a href="<?= site_url('quizzes/add_question/' . $quiz->id) ?>" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Add Question
    </a>
    <?php endif; ?>
</div>

<?php if (empty($questions)): ?>
<div class="text-center py-5">
    <i class="bi bi-question-circle" style="font-size:3rem;color:#cbd5e1;"></i>
    <p style="color:#94a3b8;margin-top:0.75rem;">No questions yet. Add your first question.</p>
</div>
<?php else: ?>
    <?php foreach ($questions as $i => $q): ?>
    <div class="mb-3 p-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex align-items-center gap-2">
                <span style="background:#6366f1;color:white;width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;"><?= $i + 1 ?></span>
                <?php
                $type_labels = array('multiple_choice' => 'Multiple Choice', 'true_false' => 'True/False', 'identification' => 'Identification', 'essay' => 'Essay');
                ?>
                <span style="background:#f1f5f9;color:#475569;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.72rem;font-weight:600;">
                    <?= isset($type_labels[$q->question_type]) ? $type_labels[$q->question_type] : $q->question_type ?>
                </span>
                <span style="color:#94a3b8;font-size:0.78rem;"><?= $q->points ?> pt<?= $q->points != 1 ? 's' : '' ?></span>
            </div>
            <?php if (!isset($has_attempts) || !$has_attempts): ?>
            <div class="d-flex gap-1">
                <a href="<?= site_url('quizzes/edit_question/' . $q->id) ?>" class="btn-action" title="Edit"><i class="bi bi-pencil"></i></a>
                <a href="<?= site_url('quizzes/delete_question/' . $q->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this question?');"><i class="bi bi-trash"></i></a>
            </div>
            <?php endif; ?>
        </div>
        <div style="font-size:0.95rem;color:#0f172a;line-height:1.6;margin-bottom:0.75rem;"><?= nl2br(htmlspecialchars($q->question_text)) ?></div>

        <?php if (!empty($q->choices)): ?>
        <div class="ms-3">
            <?php foreach ($q->choices as $c): ?>
            <div class="d-flex align-items-center gap-2 mb-1" style="font-size:0.85rem;">
                <?php if ($c->is_correct): ?>
                    <i class="bi bi-check-circle-fill" style="color:#10b981;"></i>
                <?php else: ?>
                    <i class="bi bi-circle" style="color:#cbd5e1;"></i>
                <?php endif; ?>
                <span style="color:<?= $c->is_correct ? '#10b981' : '#64748b' ?>;<?= $c->is_correct ? 'font-weight:600;' : '' ?>"><?= htmlspecialchars($c->choice_text) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
