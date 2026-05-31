<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
if (!function_exists('assessment_datetime_value')) {
    function assessment_datetime_value($value)
    {
        if (empty($value)) return '';
        $time = strtotime($value);
        return $time ? date('Y-m-d\TH:i', $time) : '';
    }
}

$assessment_system_type = strtolower(isset($subject->system_type) ? $subject->system_type : 'general');
$assessment_grade_label = '';

if (isset($subject->year_level) && trim((string) $subject->year_level) !== '') {
    $assessment_grade_value = trim((string) $subject->year_level);
    $assessment_grade_label = is_numeric($assessment_grade_value)
        ? 'Grade ' . str_pad((int) $assessment_grade_value, 2, '0', STR_PAD_LEFT)
        : $assessment_grade_value;
}

$assessment_back_url = site_url('course/content/' . $subject->id . '?edit=1#module-' . $module->id);
$assessment_question_count = count($questions);
$assessment_attempt_count = count($attempts);
$assessment_status_label = $quiz->is_published ? 'Published' : 'Hidden';
?>

<div class="am-page">
    <a href="<?= $assessment_back_url ?>" class="am-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Course Content
    </a>

    <div class="am-hero">
        <div class="am-hero-bg"></div>
        <div class="am-hero-content">
            <div class="am-hero-left">
                <div class="am-hero-avatar">AS</div>
                <div class="am-hero-copy">
                    <div class="am-hero-meta">
                        <span class="am-tag am-tag-main"><?= htmlspecialchars(strtoupper($assessment_system_type)) ?></span>
                        <span class="am-tag am-tag-soft"><?= htmlspecialchars(ucfirst($quiz->quiz_type)) ?></span>
                        <?php if ($assessment_grade_label !== ''): ?>
                            <span class="am-tag am-tag-soft"><?= htmlspecialchars($assessment_grade_label) ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="am-hero-title"><?= htmlspecialchars($quiz->title) ?></h1>
                    <p class="am-hero-sub"><?= htmlspecialchars($subject->code) ?> · <?= htmlspecialchars($module->title) ?></p>
                </div>
            </div>
            <div class="am-hero-stats">
                <div class="am-hero-stat">
                    <div class="am-hero-stat-num"><?= (int) $assessment_question_count ?></div>
                    <div class="am-hero-stat-lbl">Questions</div>
                </div>
                <div class="am-hero-stat">
                    <div class="am-hero-stat-num"><?= number_format((float) $quiz->total_points, 0) ?></div>
                    <div class="am-hero-stat-lbl">Points</div>
                </div>
                <div class="am-hero-stat">
                    <div class="am-hero-stat-num"><?= (int) $assessment_attempt_count ?></div>
                    <div class="am-hero-stat-lbl">Attempts</div>
                </div>
            </div>
        </div>
        <div class="am-hero-actions">
            <div class="am-badges">
                <span class="am-badge"><?= $assessment_status_label ?></span>
                <span class="am-badge"><?= (int) max(1, (int) $quiz->max_attempts) ?> Attempt<?= (int) max(1, (int) $quiz->max_attempts) === 1 ? '' : 's' ?></span>
                <?php if (!empty($quiz->time_limit_minutes)): ?>
                    <span class="am-badge"><?= (int) $quiz->time_limit_minutes ?> Minutes</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="am-stack">
        <?php if (!empty($can_edit_assessment)): ?>
        <form action="<?= site_url('course/edit_assessment/' . $quiz->id) ?>" method="post" class="am-panel">
            <div class="am-panel-head">
                <div class="am-panel-title"><i class="bi bi-sliders"></i> Assessment Settings</div>
            </div>
            <div class="am-panel-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label am-label">Title</label>
                        <input type="text" class="form-control am-input" name="title" value="<?= htmlspecialchars($quiz->title, ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label am-label">Type</label>
                        <select class="form-select am-input" name="quiz_type">
                            <option value="quiz" <?= $quiz->quiz_type === 'quiz' ? 'selected' : '' ?>>Quiz</option>
                            <option value="exam" <?= $quiz->quiz_type === 'exam' ? 'selected' : '' ?>>Exam</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label am-label">Max Attempts</label>
                        <input type="number" class="form-control am-input" name="max_attempts" min="1" value="<?= (int) $quiz->max_attempts ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label am-label">Time Limit</label>
                        <input type="number" class="form-control am-input" name="time_limit_minutes" min="0" value="<?= (int) $quiz->time_limit_minutes ?>" placeholder="Minutes">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label am-label">Available From</label>
                        <input type="datetime-local" class="form-control am-input" name="available_from" value="<?= assessment_datetime_value($quiz->available_from) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label am-label">Available Until</label>
                        <input type="datetime-local" class="form-control am-input" name="available_until" value="<?= assessment_datetime_value($quiz->available_until) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label am-label">Description / Instructions</label>
                        <textarea class="form-control am-input am-textarea" name="description" rows="3"><?= htmlspecialchars($quiz->description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="col-12 am-form-footer">
                        <div class="am-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="assessmentPublished" <?= $quiz->is_published ? 'checked' : '' ?>>
                                <label class="form-check-label" for="assessmentPublished">Published</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shuffle_questions" value="1" id="assessmentShuffle" <?= $quiz->shuffle_questions ? 'checked' : '' ?>>
                                <label class="form-check-label" for="assessmentShuffle">Shuffle questions</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="show_results" value="1" id="assessmentShowResults" <?= $quiz->show_results ? 'checked' : '' ?>>
                                <label class="form-check-label" for="assessmentShowResults">Show results</label>
                            </div>
                        </div>
                        <button type="submit" class="am-btn am-btn-primary">
                            <i class="bi bi-check2"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <form action="<?= site_url('course/upload_assessment_questions/' . $quiz->id) ?>" method="post" enctype="multipart/form-data" class="am-panel">
            <div class="am-panel-head">
                <div class="am-panel-title"><i class="bi bi-upload"></i> Import Question Bank</div>
            </div>
            <div class="am-panel-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label am-label">Format</label>
                        <select class="form-select am-input" name="import_format">
                            <option value="gift">GIFT</option>
                            <option value="xml">Moodle XML</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label am-label">Question File</label>
                        <input type="file" class="form-control am-input" name="question_file" accept=".gift,.txt,.xml,text/plain,text/xml,application/xml" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="am-btn am-btn-primary am-btn-block">
                            <i class="bi bi-cloud-arrow-up"></i> Import
                        </button>
                    </div>
                </div>
                <div class="am-help-text">Supported: multiple choice, true/false, identification, essay.</div>
            </div>
        </form>
        <?php endif; ?>

        <div class="am-panel">
            <div class="am-panel-head">
                <div class="am-panel-title"><i class="bi bi-list-check"></i> Questions</div>
            </div>
            <div class="am-panel-body">
                <?php if (empty($questions)): ?>
                    <div class="am-empty">
                        <i class="bi bi-inbox"></i>
                        <p>No questions imported yet.</p>
                    </div>
                <?php else: ?>
                    <div class="am-question-list">
                        <?php foreach ($questions as $idx => $question): ?>
                            <div class="am-question-item">
                                <div class="am-question-main">
                                    <div class="am-question-meta">
                                        #<?= $idx + 1 ?> · <?= htmlspecialchars(str_replace('_', ' ', ucfirst($question->question_type))) ?> · <?= number_format((float) $question->points, 2) ?> pts
                                    </div>
                                    <div class="am-question-text"><?= htmlspecialchars($question->question_text) ?></div>
                                    <?php if (!empty($question->choices)): ?>
                                        <ul class="am-choice-list">
                                            <?php foreach ($question->choices as $choice): ?>
                                                <li>
                                                    <?= $choice->is_correct ? '<strong>[Correct]</strong> ' : '' ?>
                                                    <?= htmlspecialchars($choice->choice_text) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($can_edit_assessment)): ?>
                                <a href="<?= site_url('course/delete_assessment_question/' . $question->id) ?>" class="am-icon-btn am-icon-btn-danger" onclick="return confirm('Delete this question?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="am-panel">
            <div class="am-panel-head">
                <div class="am-panel-title"><i class="bi bi-people"></i> Attempts</div>
            </div>
            <div class="am-panel-body">
                <?php if (empty($attempts)): ?>
                    <p class="am-muted mb-0">No student attempts yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table am-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Attempt</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Submitted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attempts as $attempt): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($attempt->student_name ?? $attempt->email ?? 'Student') ?></td>
                                        <td><?= (int) $attempt->attempt_number ?></td>
                                        <td><span class="am-mini-badge"><?= htmlspecialchars($attempt->status) ?></span></td>
                                        <td><?= $attempt->score === null ? '-' : number_format((float) $attempt->score, 2) . ' / ' . number_format((float) $attempt->total_points, 2) ?></td>
                                        <td><?= $attempt->submitted_at ? htmlspecialchars($attempt->submitted_at) : '-' ?></td>
                                        <td class="text-end">
                                            <a href="<?= site_url('course/assessment_result/' . $attempt->id) ?>" class="am-btn am-btn-ghost am-btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.am-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0 3rem;
}

.am-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 1.5rem;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}

.am-back:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
}

.am-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}

.am-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.am-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.am-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem 1.5rem;
    flex-wrap: wrap;
}

.am-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.am-hero-avatar {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.3);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 1px;
}

.am-hero-copy {
    min-width: 0;
}

.am-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.am-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.am-tag-main {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.am-tag-soft {
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.9);
    border: 1px solid rgba(255,255,255,0.25);
}

.am-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.am-hero-sub {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.74);
    margin: 0;
    line-height: 1.5;
}

.am-hero-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
    flex-wrap: wrap;
}

.am-hero-stat {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 100px;
}

.am-hero-stat-num {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.am-hero-stat-lbl {
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255,255,255,0.75);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-top: 0.3rem;
}

.am-hero-actions {
    position: relative;
    padding: 0 2.25rem 1.5rem;
}

.am-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
}

.am-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.75rem;
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 10px;
    background: rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.9);
    font-size: 0.78rem;
    font-weight: 600;
}

.am-stack {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.am-panel {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}

.am-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
}

.am-panel-title {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.am-panel-title i {
    color: #2563eb;
}

.am-panel-body {
    padding: 1.35rem 1.5rem 1.5rem;
}

.am-label {
    font-size: 0.82rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.4rem;
}

.am-input {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    min-height: 42px;
    color: #1e293b;
}

.am-input:focus,
.form-check-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}

.am-textarea {
    min-height: 110px;
}

.am-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.am-checks {
    display: flex;
    align-items: center;
    gap: 1rem 1.5rem;
    flex-wrap: wrap;
}

.am-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    min-height: 42px;
    padding: 0.7rem 1rem;
    border: none;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.16s ease;
    cursor: pointer;
}

.am-btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(59,130,246,0.35);
}

.am-btn-primary:hover {
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 8px 22px rgba(59,130,246,0.45);
}

.am-btn-ghost {
    background: #fff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.am-btn-ghost:hover {
    background: #eff6ff;
    color: #1d4ed8;
    text-decoration: none;
}

.am-btn-sm {
    min-height: 34px;
    padding: 0.45rem 0.75rem;
    font-size: 0.78rem;
}

.am-btn-block {
    width: 100%;
}

.am-help-text,
.am-muted {
    font-size: 0.82rem;
    color: #64748b;
}

.am-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 2.5rem 1rem;
    color: #94a3b8;
    text-align: center;
}

.am-empty i {
    font-size: 2rem;
    opacity: 0.45;
}

.am-empty p {
    margin: 0;
}

.am-question-list {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.am-question-item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #eef2f7;
    border-radius: 14px;
    background: #fff;
}

.am-question-main {
    min-width: 0;
}

.am-question-meta {
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 0.35rem;
}

.am-question-text {
    font-size: 0.92rem;
    font-weight: 700;
    color: #1e293b;
}

.am-choice-list {
    margin: 0.75rem 0 0;
    padding-left: 1.2rem;
    color: #475569;
    font-size: 0.84rem;
}

.am-choice-list li + li {
    margin-top: 0.3rem;
}

.am-icon-btn {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    text-decoration: none;
    flex-shrink: 0;
}

.am-icon-btn-danger {
    background: #fee2e2;
    color: #dc2626;
}

.am-icon-btn-danger:hover {
    background: #fecaca;
    color: #b91c1c;
    text-decoration: none;
}

.am-table thead th {
    background: #f8faff;
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #eaecf0;
    padding: 0.85rem 0.9rem;
}

.am-table tbody td {
    border-color: #f1f5f9;
    padding: 0.9rem;
    color: #334155;
}

.am-mini-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.28rem 0.65rem;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-size: 0.74rem;
    font-weight: 700;
}

@media (max-width: 768px) {
    .am-page {
        padding: 1rem 0 2rem;
    }

    .am-hero-content {
        padding: 1.5rem 1.5rem 1.15rem;
    }

    .am-hero-actions {
        padding: 0 1.5rem 1.5rem;
    }

    .am-hero-left {
        align-items: flex-start;
    }

    .am-hero-title {
        font-size: 1.3rem;
    }

    .am-panel-head,
    .am-panel-body {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .am-form-footer {
        align-items: stretch;
    }

    .am-btn-primary {
        width: 100%;
    }

    .am-question-item {
        flex-direction: column;
    }
}
</style>
