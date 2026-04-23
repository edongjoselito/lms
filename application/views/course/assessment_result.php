<div class="assessment-page">
    <div class="mb-3">
        <a href="<?= site_url('course/assessment/' . $activity->id) ?>" class="assessment-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Assessment
        </a>
    </div>

    <div class="data-table">
        <div class="table-header d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <div class="text-muted small mb-1"><?= htmlspecialchars($subject->code) ?> &middot; Attempt <?= (int) $attempt->attempt_number ?></div>
                <h5 class="mb-2"><?= htmlspecialchars($quiz->title) ?></h5>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border"><?= htmlspecialchars(ucfirst($attempt->status)) ?></span>
                    <?php if ($attempt->submitted_at): ?>
                        <span class="badge bg-light text-dark border">Submitted <?= htmlspecialchars($attempt->submitted_at) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="result-summary mb-4">
                <div>
                    <span class="result-label">Score</span>
                    <strong><?= $attempt->score === null ? '-' : number_format((float) $attempt->score, 2) ?></strong>
                </div>
                <div>
                    <span class="result-label">Total</span>
                    <strong><?= $attempt->total_points === null ? number_format((float) $quiz->total_points, 2) : number_format((float) $attempt->total_points, 2) ?></strong>
                </div>
                <div>
                    <span class="result-label">Percentage</span>
                    <strong><?= $attempt->percentage === null ? '-' : number_format((float) $attempt->percentage, 2) . '%' ?></strong>
                </div>
            </div>

            <?php if (!$show_results): ?>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-1"></i>Your score was submitted. Detailed results are hidden by the course creator.
                </div>
            <?php else: ?>
                <?php foreach ($questions as $idx => $question): ?>
                    <?php
                    $answer = $answer_map[(int) $question->id] ?? null;
                    $selected_choice = null;
                    $correct_choices = array();
                    foreach ($question->choices as $choice) {
                        if ($answer && (int) $answer->choice_id === (int) $choice->id) {
                            $selected_choice = $choice;
                        }
                        if ($choice->is_correct) {
                            $correct_choices[] = $choice->choice_text;
                        }
                    }
                    ?>
                    <div class="assessment-question">
                        <div class="d-flex justify-content-between gap-3 mb-2">
                            <h6 class="mb-0">Question <?= $idx + 1 ?></h6>
                            <span class="badge bg-light text-dark border">
                                <?= $answer && $answer->score !== null ? number_format((float) $answer->score, 2) : '-' ?> / <?= number_format((float) $question->points, 2) ?> pts
                            </span>
                        </div>
                        <p class="question-text"><?= nl2br(htmlspecialchars($question->question_text, ENT_QUOTES, 'UTF-8')) ?></p>
                        <div class="answer-review">
                            <div>
                                <strong>Your Answer:</strong>
                                <?php if ($selected_choice): ?>
                                    <?= htmlspecialchars($selected_choice->choice_text) ?>
                                <?php elseif ($answer && $answer->answer_text !== null && $answer->answer_text !== ''): ?>
                                    <?= nl2br(htmlspecialchars($answer->answer_text, ENT_QUOTES, 'UTF-8')) ?>
                                <?php else: ?>
                                    <span class="text-muted">No answer</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($question->question_type !== 'essay' && !empty($correct_choices)): ?>
                                <div><strong>Correct Answer:</strong> <?= htmlspecialchars(implode(', ', $correct_choices)) ?></div>
                            <?php elseif ($question->question_type === 'essay'): ?>
                                <div class="text-muted">Essay answers require manual review.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
.result-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
}
.result-summary > div,
.assessment-question {
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    background: #fff;
    padding: 1rem;
}
.result-label {
    display: block;
    color: #667085;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}
.result-summary strong {
    font-size: 1.4rem;
    color: #182033;
}
.assessment-question {
    margin-bottom: 1rem;
}
.assessment-question h6 {
    font-weight: 700;
    color: #182033;
}
.question-text {
    color: #344054;
    line-height: 1.6;
}
.answer-review {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    padding: 0.75rem;
    border-radius: 8px;
    background: #f8fafc;
    color: #475467;
}
</style>
