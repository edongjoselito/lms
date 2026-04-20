<div class="row justify-content-center">
    <div class="col-lg-8">
        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-4" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;">
                <h4 style="font-weight:700;margin:0;"><?= htmlspecialchars($quiz->title) ?></h4>
                <div style="opacity:0.85;font-size:0.85rem;margin-top:0.25rem;">
                    <?= count($questions) ?> questions &middot; <?= $quiz->total_points ?> points
                    <?php if ($quiz->time_limit_minutes): ?>
                        &middot; <?= $quiz->time_limit_minutes ?> min time limit
                    <?php endif; ?>
                </div>
                <?php if ($quiz->description): ?>
                    <div style="opacity:0.9;font-size:0.85rem;margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid rgba(255,255,255,0.2);"><?= nl2br(htmlspecialchars($quiz->description)) ?></div>
                <?php endif; ?>
            </div>

            <form action="<?= site_url('quizzes/submit/' . $attempt->id) ?>" method="post" id="quizForm">
                <div class="p-4">
                    <?php foreach ($questions as $qi => $q): ?>
                    <div class="mb-4 pb-4 <?= $qi < count($questions) - 1 ? 'border-bottom' : '' ?>">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <span style="background:#6366f1;color:white;min-width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;"><?= $qi + 1 ?></span>
                            <div style="flex:1;">
                                <div style="font-weight:600;font-size:0.95rem;line-height:1.5;"><?= nl2br(htmlspecialchars($q->question_text)) ?></div>
                                <div style="color:#94a3b8;font-size:0.75rem;margin-top:0.25rem;"><?= $q->points ?> point<?= $q->points != 1 ? 's' : '' ?></div>
                            </div>
                        </div>

                        <?php $prev_answer = isset($answer_map[$q->id]) ? $answer_map[$q->id] : null; ?>

                        <?php if ($q->question_type === 'multiple_choice' || $q->question_type === 'true_false'): ?>
                            <div class="ms-5">
                                <?php foreach ($q->choices as $c): ?>
                                <label class="d-flex align-items-center gap-2 p-3 mb-2" style="background:#f8fafc;border-radius:10px;cursor:pointer;border:2px solid #e2e8f0;transition:all 0.2s;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='#e2e8f0'">
                                    <input type="radio" name="answer_<?= $q->id ?>" value="<?= $c->id ?>" class="form-check-input" <?= ($prev_answer && $prev_answer->choice_id == $c->id) ? 'checked' : '' ?>>
                                    <span style="font-size:0.9rem;"><?= htmlspecialchars($c->choice_text) ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($q->question_type === 'identification'): ?>
                            <div class="ms-5">
                                <input type="text" class="form-control" name="answer_<?= $q->id ?>" value="<?= $prev_answer ? htmlspecialchars($prev_answer->answer_text) : '' ?>" placeholder="Type your answer" style="max-width:400px;">
                            </div>

                        <?php elseif ($q->question_type === 'essay'): ?>
                            <div class="ms-5">
                                <textarea class="form-control" name="answer_<?= $q->id ?>" rows="5" placeholder="Write your answer..."><?= $prev_answer ? htmlspecialchars($prev_answer->answer_text) : '' ?></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="p-4" style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="color:#64748b;font-size:0.85rem;">Review your answers before submitting.</span>
                        <button type="submit" class="btn-primary-custom" onclick="return confirm('Are you sure you want to submit? You cannot change your answers after submission.');">
                            <i class="bi bi-send me-1"></i> Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($quiz->time_limit_minutes): ?>
<script>
(function(){
    var start = new Date('<?= $attempt->started_at ?>').getTime();
    var limit = <?= $quiz->time_limit_minutes ?> * 60 * 1000;
    var timer = document.createElement('div');
    timer.style.cssText = 'position:fixed;top:16px;right:16px;background:#0f172a;color:white;padding:0.5rem 1rem;border-radius:12px;font-weight:700;font-size:0.9rem;z-index:9999;';
    document.body.appendChild(timer);

    setInterval(function(){
        var remaining = limit - (Date.now() - start);
        if (remaining <= 0) {
            document.getElementById('quizForm').submit();
            return;
        }
        var min = Math.floor(remaining / 60000);
        var sec = Math.floor((remaining % 60000) / 1000);
        timer.innerHTML = '<i class="bi bi-clock me-1"></i>' + min + ':' + (sec < 10 ? '0' : '') + sec;
        if (remaining < 60000) timer.style.background = '#ef4444';
    }, 1000);
})();
</script>
<?php endif; ?>
