<div class="assessment-page">
    <div class="mb-3">
        <?php $content_back_url = (isset($original_role_slug) && $original_role_slug === 'student') ? site_url('student/content/' . $subject->id) : site_url('course/content/' . $subject->id); ?>
        <a href="<?= $content_back_url ?>" class="assessment-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Course Content
        </a>
    </div>

    <div class="data-table">
        <div class="table-header">
            <div>
                <div class="text-muted small mb-1"><?= htmlspecialchars($subject->code) ?> &middot; <?= htmlspecialchars($module->title) ?></div>
                <h5 class="mb-2"><?= htmlspecialchars($quiz->title) ?></h5>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border"><?= htmlspecialchars(ucfirst($quiz->quiz_type)) ?></span>
                    <span class="badge bg-light text-dark border"><?= (int) $questions_count ?> Questions</span>
                    <span class="badge bg-light text-dark border"><?= number_format((float) $quiz->total_points, 2) ?> Points</span>
                    <span class="badge bg-light text-dark border"><?= (int) $quiz->max_attempts ?> Attempt<?= (int) $quiz->max_attempts === 1 ? '' : 's' ?></span>
                    <?php if (!empty($quiz->time_limit_minutes)): ?>
                        <span class="badge bg-light text-dark border"><?= (int) $quiz->time_limit_minutes ?> Minutes</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-4">
            <?php if (!empty($quiz->description)): ?>
                <div class="assessment-panel mb-4">
                    <?= nl2br(htmlspecialchars(strip_tags($quiz->description), ENT_QUOTES, 'UTF-8')) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($availability_error)): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-1"></i><?= htmlspecialchars($availability_error) ?>
                </div>
            <?php elseif ($questions_count < 1): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-1"></i>This assessment has no questions yet.
                </div>
            <?php endif; ?>

            <div class="assessment-panel mb-4">
                <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Your Attempts</h6>
                <?php if (empty($attempts)): ?>
                    <p class="text-muted mb-0">No attempts yet.</p>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($attempts as $attempt): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center gap-3">
                                <div>
                                    <strong>Attempt <?= (int) $attempt->attempt_number ?></strong>
                                    <div class="small text-muted">
                                        <?= htmlspecialchars(ucfirst($attempt->status)) ?>
                                        <?php if ($attempt->submitted_at): ?>
                                            &middot; Submitted <?= htmlspecialchars($attempt->submitted_at) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($attempt->status === 'in_progress'): ?>
                                    <a href="<?= site_url('course/assessment_attempt/' . $attempt->id) ?>" class="btn btn-sm btn-primary">Continue</a>
                                <?php else: ?>
                                    <a href="<?= site_url('course/assessment_result/' . $attempt->id) ?>" class="btn btn-sm btn-outline-primary">Result</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end">
                <?php if ($in_progress_attempt): ?>
                    <a href="<?= site_url('course/assessment_attempt/' . $in_progress_attempt->id) ?>" class="btn btn-primary">
                        <i class="bi bi-play-circle me-1"></i>Continue Attempt
                    </a>
                <?php elseif ($can_start): ?>
                    <form action="<?= site_url('course/start_assessment/' . $quiz->id) ?>" method="post">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>Start Assessment
                        </button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary" disabled>Cannot Start</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.assessment-page .assessment-back {
    color: #2f6fed;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
}
.assessment-panel {
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    background: #fff;
    padding: 1rem;
}
.assessment-panel h6 {
    font-weight: 700;
    color: #182033;
}
</style>
