<div class="assessment-page">
    <div class="mb-3">
        <a href="<?= site_url('course/assessment/' . $activity->id) ?>" class="assessment-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Assessment
        </a>
    </div>

    <?php if (isset($remaining_seconds) && $remaining_seconds !== null): ?>
        <div class="timer-bar" id="timerBar">
            <div class="timer-container">
                <i class="bi bi-clock"></i>
                <span class="timer-label">Time Remaining:</span>
                <span class="timer-display" id="timerDisplay">--:--</span>
            </div>
        </div>
        <div class="timer-bar-compact" id="timerBarCompact">
            <div class="timer-container-compact">
                <i class="bi bi-clock"></i>
                <span class="timer-display-compact" id="timerDisplayCompact">--:--</span>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('course/submit_assessment/' . $attempt->id) ?>" method="post" class="data-table" id="assessmentForm">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
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
.timer-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    transition: opacity 0.3s ease;
}
.timer-bar.hidden {
    display: none;
}
.timer-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #fff;
}
.timer-container i {
    font-size: 1.25rem;
}
.timer-label {
    font-size: 0.9rem;
    font-weight: 600;
}
.timer-display {
    font-size: 1.5rem;
    font-weight: 800;
    font-family: 'Courier New', monospace;
    min-width: 80px;
    text-align: center;
}
.timer-display.warning {
    color: #ffd700;
}
.timer-display.danger {
    color: #ff6b6b;
    animation: pulse 1s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Compact sticky timer */
.timer-bar-compact {
    position: fixed;
    top: 0;
    right: 20px;
    z-index: 9999;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}
.timer-bar-compact.visible {
    opacity: 1;
    visibility: visible;
}
.timer-container-compact {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
}
.timer-container-compact i {
    font-size: 1rem;
}
.timer-display-compact {
    font-size: 1.1rem;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    min-width: 60px;
    text-align: center;
}
.timer-display-compact.warning {
    color: #ffd700;
}
.timer-display-compact.danger {
    color: #ff6b6b;
    animation: pulse 1s infinite;
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

<?php if (isset($remaining_seconds) && $remaining_seconds !== null): ?>
<script>
let remainingSeconds = <?= (int) $remaining_seconds ?>;
const timerDisplay = document.getElementById('timerDisplay');
const timerDisplayCompact = document.getElementById('timerDisplayCompact');
const timerBar = document.getElementById('timerBar');
const timerBarCompact = document.getElementById('timerBarCompact');
const assessmentForm = document.getElementById('assessmentForm');
let autoSubmitted = false;

function updateTimer() {
    if (remainingSeconds <= 0) {
        timerDisplay.textContent = '00:00';
        timerDisplayCompact.textContent = '00:00';
        timerDisplay.classList.add('danger');
        timerDisplayCompact.classList.add('danger');
        if (!autoSubmitted) {
            autoSubmitted = true;
            assessmentForm.submit();
        }
        return;
    }
    
    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    const display = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    
    timerDisplay.textContent = display;
    timerDisplayCompact.textContent = display;
    
    timerDisplay.classList.remove('warning', 'danger');
    timerDisplayCompact.classList.remove('warning', 'danger');

    if (remainingSeconds <= 60) {
        timerDisplay.classList.add('danger');
        timerDisplayCompact.classList.add('danger');
    } else if (remainingSeconds <= 300) {
        timerDisplay.classList.add('warning');
        timerDisplayCompact.classList.add('warning');
    }

    remainingSeconds--;
}

// Handle scroll to show/hide compact timer
function handleScroll() {
    const timerBarRect = timerBar.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > timerBarRect.bottom) {
        timerBar.classList.add('hidden');
        timerBarCompact.classList.add('visible');
    } else {
        timerBar.classList.remove('hidden');
        timerBarCompact.classList.remove('visible');
    }
}

// Initial update
updateTimer();

// Update every second
setInterval(updateTimer, 1000);

// Handle scroll
window.addEventListener('scroll', handleScroll);
</script>
<?php endif; ?>
