<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-3">
            <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Course
            </a>
        </div>

        <!-- Score Card -->
        <div class="text-center p-4 mb-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
            <?php
            $pct = $attempt->percentage ?: 0;
            $color = $pct >= 75 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
            ?>
            <div style="width:100px;height:100px;border-radius:50%;border:6px solid <?= $color ?>;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <span style="font-size:1.5rem;font-weight:800;color:<?= $color ?>;"><?= $pct ?>%</span>
            </div>
            <h4 style="font-weight:700;"><?= htmlspecialchars($quiz->title) ?></h4>
            <div style="color:#64748b;font-size:0.9rem;">
                Score: <strong><?= $attempt->score ?: 0 ?> / <?= $attempt->total_points ?></strong>
                &middot; Attempt #<?= $attempt->attempt_number ?>
                &middot;
                <?php
                $status_colors = array('in_progress' => '#f59e0b', 'submitted' => '#6366f1', 'graded' => '#10b981');
                $sc = isset($status_colors[$attempt->status]) ? $status_colors[$attempt->status] : '#94a3b8';
                ?>
                <span style="color:<?= $sc ?>;font-weight:600;"><?= ucfirst(str_replace('_', ' ', $attempt->status)) ?></span>
            </div>
        </div>

        <!-- Question Review -->
        <?php if ($quiz->show_results || $role_slug !== 'student'): ?>
        <?php foreach ($questions as $qi => $q): ?>
        <div class="mb-3 p-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
            <div class="d-flex align-items-start gap-3 mb-3">
                <span style="background:#6366f1;color:white;min-width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;"><?= $qi + 1 ?></span>
                <div style="flex:1;">
                    <div style="font-weight:600;font-size:0.95rem;"><?= nl2br(htmlspecialchars($q->question_text)) ?></div>
                    <div style="color:#94a3b8;font-size:0.75rem;"><?= $q->points ?> pt<?= $q->points != 1 ? 's' : '' ?>
                        <?php if ($q->student_answer): ?>
                            &middot; Scored: <strong><?= $q->student_answer->score !== null ? $q->student_answer->score : 'Pending' ?></strong>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($q->student_answer && $q->student_answer->is_correct !== null): ?>
                    <?php if ($q->student_answer->is_correct): ?>
                        <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:1.25rem;"></i>
                    <?php else: ?>
                        <i class="bi bi-x-circle-fill" style="color:#ef4444;font-size:1.25rem;"></i>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if ($q->question_type === 'multiple_choice' || $q->question_type === 'true_false'): ?>
                <div class="ms-5">
                    <?php foreach ($q->choices as $c): ?>
                    <?php
                    $is_selected = ($q->student_answer && $q->student_answer->choice_id == $c->id);
                    $bg = '#f8fafc'; $border = '#e2e8f0';
                    if ($c->is_correct) { $bg = '#ecfdf5'; $border = '#10b981'; }
                    elseif ($is_selected) { $bg = '#fef2f2'; $border = '#ef4444'; }
                    ?>
                    <div class="d-flex align-items-center gap-2 p-2 mb-1" style="background:<?= $bg ?>;border-radius:8px;border:1px solid <?= $border ?>;font-size:0.85rem;">
                        <?php if ($c->is_correct): ?>
                            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i>
                        <?php elseif ($is_selected): ?>
                            <i class="bi bi-x-circle-fill" style="color:#ef4444;"></i>
                        <?php else: ?>
                            <i class="bi bi-circle" style="color:#cbd5e1;"></i>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($c->choice_text) ?></span>
                        <?php if ($is_selected): ?><span style="color:#94a3b8;font-size:0.72rem;margin-left:auto;">Your answer</span><?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($q->question_type === 'identification'): ?>
                <div class="ms-5">
                    <div style="font-size:0.85rem;color:#64748b;">Your answer: <strong><?= $q->student_answer ? htmlspecialchars($q->student_answer->answer_text) : '<em>No answer</em>' ?></strong></div>
                    <div style="font-size:0.85rem;color:#10b981;margin-top:0.25rem;">Correct: <?php foreach ($q->choices as $c) if ($c->is_correct) echo htmlspecialchars($c->choice_text) . ' '; ?></div>
                </div>

            <?php elseif ($q->question_type === 'essay'): ?>
                <div class="ms-5 p-3" style="background:#f8fafc;border-radius:10px;font-size:0.85rem;color:#334155;">
                    <?= $q->student_answer ? nl2br(htmlspecialchars($q->student_answer->answer_text)) : '<em>No answer</em>' ?>
                </div>
                <?php if ($q->student_answer && $q->student_answer->feedback): ?>
                    <div class="ms-5 mt-2 p-2" style="background:#eff6ff;border-radius:8px;font-size:0.82rem;color:#1d4ed8;">
                        <i class="bi bi-chat-left-text me-1"></i> <strong>Feedback:</strong> <?= htmlspecialchars($q->student_answer->feedback) ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="text-center py-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
            <i class="bi bi-eye-slash" style="font-size:2rem;color:#cbd5e1;"></i>
            <p style="color:#94a3b8;margin-top:0.5rem;">Detailed results are hidden by the teacher.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
