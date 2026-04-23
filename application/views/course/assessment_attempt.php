<div class="assessment-page">
    <div class="mb-3">
        <a href="<?= site_url('course/assessment/' . $activity->id) ?>" class="assessment-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Assessment
        </a>
    </div>

    <form action="<?= site_url('course/submit_assessment/' . $attempt->id) ?>" method="post" class="data-table">
        <div class="table-header d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <div class="text-muted small mb-1"><?= htmlspecialchars($subject->code) ?> &middot; Attempt <?= (int) $attempt->attempt_number ?></div>
                <h5 class="mb-2"><?= htmlspecialchars($quiz->title) ?></h5>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border"><?= count($questions) ?> Questions</span>
                    <span class="badge bg-light text-dark border"><?= number_format((float) $quiz->total_points, 2) ?> Points</span>
                    <?php if (!empty($quiz->time_limit_minutes)): ?>
                        <span class="badge bg-warning text-dark"><?= (int) $quiz->time_limit_minutes ?> Minutes</span>
                    <?php endif; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Submit this assessment?');">
                <i class="bi bi-check2-circle me-1"></i>Submit
            </button>
        </div>

        <div class="p-4">
            <?php foreach ($questions as $idx => $question): ?>
                <?php $saved_answer = $answer_map[(int) $question->id] ?? null; ?>
                <div class="assessment-question">
                    <div class="d-flex justify-content-between gap-3 mb-2">
                        <h6 class="mb-0">Question <?= $idx + 1 ?></h6>
                        <span class="badge bg-light text-dark border"><?= number_format((float) $question->points, 2) ?> pts</span>
                    </div>
                    <p class="question-text"><?= nl2br(htmlspecialchars($question->question_text, ENT_QUOTES, 'UTF-8')) ?></p>

                    <?php if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false'): ?>
                        <div class="answer-options">
                            <?php foreach ($question->choices as $choice): ?>
                                <label class="answer-option">
                                    <input type="radio" name="answers[<?= $question->id ?>]" value="<?= $choice->id ?>" <?= ($saved_answer && (int) $saved_answer->choice_id === (int) $choice->id) ? 'checked' : '' ?>>
                                    <span><?= htmlspecialchars($choice->choice_text) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($question->question_type === 'identification'): ?>
                        <input type="text" class="form-control" name="answers[<?= $question->id ?>]" value="<?= htmlspecialchars($saved_answer->answer_text ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Type your answer">
                    <?php else: ?>
                        <textarea class="form-control" name="answers[<?= $question->id ?>]" rows="5" placeholder="Type your answer"><?= htmlspecialchars($saved_answer->answer_text ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Submit this assessment?');">
                    <i class="bi bi-check2-circle me-1"></i>Submit Assessment
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.assessment-page .assessment-back {
    color: #2f6fed;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
}
.assessment-question {
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    background: #fff;
    padding: 1rem;
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
.answer-options {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}
.answer-option {
    display: flex;
    gap: 0.65rem;
    align-items: flex-start;
    padding: 0.75rem;
    border: 1px solid #edf0f4;
    border-radius: 8px;
    background: #f8fafc;
    cursor: pointer;
}
.answer-option input {
    margin-top: 0.25rem;
}
</style>
