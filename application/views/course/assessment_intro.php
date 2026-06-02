<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$content_back_url = (isset($original_role_slug) && $original_role_slug === 'student')
    ? site_url('student/content/' . $subject->id)
    : site_url('course/content/' . $subject->id);
$assessment_system_type = strtolower(isset($subject->system_type) ? $subject->system_type : 'general');
$assessment_grade_label = '';

if (isset($subject->year_level) && trim((string) $subject->year_level) !== '') {
    $assessment_grade_value = trim((string) $subject->year_level);
    $assessment_grade_label = is_numeric($assessment_grade_value)
        ? 'Grade ' . str_pad((int) $assessment_grade_value, 2, '0', STR_PAD_LEFT)
        : $assessment_grade_value;
}
?>

<div class="am-page">
    <a href="<?= $content_back_url ?>" class="am-back">
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
                    <div class="am-hero-stat-num"><?= (int) $questions_count ?></div>
                    <div class="am-hero-stat-lbl">Questions</div>
                </div>
                <div class="am-hero-stat">
                    <div class="am-hero-stat-num"><?= number_format((float) $quiz->total_points, 0) ?></div>
                    <div class="am-hero-stat-lbl">Points</div>
                </div>
                <div class="am-hero-stat">
                    <div class="am-hero-stat-num"><?= count($attempts) ?></div>
                    <div class="am-hero-stat-lbl">Attempts</div>
                </div>
            </div>
        </div>
        <div class="am-hero-actions">
            <div class="am-badges">
                <span class="am-badge"><?= (int) $quiz->max_attempts ?> Attempt<?= (int) $quiz->max_attempts === 1 ? '' : 's' ?></span>
                <?php if (!empty($quiz->time_limit_minutes)): ?>
                    <span class="am-badge"><?= (int) $quiz->time_limit_minutes ?> Minutes</span>
                <?php endif; ?>
                <?php if (!empty($quiz->show_results)): ?>
                    <span class="am-badge">Results Visible</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="am-stack">
        <?php if (!empty($quiz->description)): ?>
            <div class="am-panel">
                <div class="am-panel-head">
                    <div class="am-panel-title"><i class="bi bi-card-text"></i> Instructions</div>
                </div>
                <div class="am-panel-body">
                    <div class="am-copy"><?= nl2br(htmlspecialchars(strip_tags($quiz->description), ENT_QUOTES, 'UTF-8')) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($availability_error)): ?>
            <div class="am-alert am-alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <span><?= htmlspecialchars($availability_error) ?></span>
            </div>
        <?php elseif ($questions_count < 1): ?>
            <div class="am-alert am-alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <span>This assessment has no questions yet.</span>
            </div>
        <?php endif; ?>

        <div class="am-panel">
            <div class="am-panel-head">
                <div class="am-panel-title"><i class="bi bi-clock-history"></i> Your Attempts</div>
            </div>
            <div class="am-panel-body">
                <?php if (empty($attempts)): ?>
                    <p class="am-muted mb-0">No attempts yet.</p>
                <?php else: ?>
                    <div class="am-attempt-list">
                        <?php foreach ($attempts as $attempt): ?>
                            <div class="am-attempt-item">
                                <div class="am-attempt-copy">
                                    <strong>Attempt <?= (int) $attempt->attempt_number ?></strong>
                                    <div class="am-attempt-meta">
                                        <?= htmlspecialchars(ucfirst($attempt->status)) ?>
                                        <?php if ($attempt->submitted_at): ?>
                                            · Submitted <?= htmlspecialchars($attempt->submitted_at) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($attempt->status === 'in_progress'): ?>
                                    <a href="<?= site_url('course/assessment_attempt/' . $attempt->id) ?>" class="am-btn am-btn-primary am-btn-sm">Continue</a>
                                <?php else: ?>
                                    <a href="<?= site_url('course/assessment_result/' . $attempt->id) ?>" class="am-btn am-btn-ghost am-btn-sm">Result</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="am-action-row">
            <?php if ($in_progress_attempt): ?>
                <a href="<?= site_url('course/assessment_attempt/' . $in_progress_attempt->id) ?>" class="am-btn am-btn-primary">
                    <i class="bi bi-play-circle"></i> Continue Attempt
                </a>
            <?php else: ?>
                <?php if (!empty($latest_result_attempt)): ?>
                    <a href="<?= site_url('course/assessment_result/' . $latest_result_attempt->id) ?>" class="am-btn am-btn-ghost">
                        <i class="bi bi-bar-chart-line"></i> View Latest Result
                    </a>
                <?php endif; ?>

                <?php if ($can_start): ?>
                    <form action="<?= site_url('course/start_assessment/' . $quiz->id) ?>" method="post">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="am-btn am-btn-primary">
                            <i class="bi bi-pencil-square"></i> Start Assessment
                        </button>
                    </form>
                <?php elseif (empty($latest_result_attempt)): ?>
                    <button type="button" class="am-btn am-btn-disabled" disabled>Cannot Start</button>
                <?php endif; ?>
            <?php endif; ?>
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

.am-copy,
.am-muted {
    color: #475569;
    font-size: 0.9rem;
    line-height: 1.65;
}

.am-alert {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.95rem 1rem;
    border-radius: 14px;
    border: 1px solid transparent;
    font-size: 0.88rem;
    font-weight: 600;
}

.am-alert-warning {
    background: #fffbeb;
    border-color: #fde68a;
    color: #a16207;
}

.am-attempt-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.am-attempt-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #eef2f7;
    border-radius: 14px;
    background: #fff;
}

.am-attempt-copy {
    min-width: 0;
}

.am-attempt-copy strong {
    color: #1e293b;
    font-size: 0.92rem;
}

.am-attempt-meta {
    font-size: 0.82rem;
    color: #64748b;
    margin-top: 0.2rem;
}

.am-action-row {
    display: flex;
    justify-content: flex-end;
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

.am-btn-disabled {
    background: #e2e8f0;
    color: #64748b;
    cursor: not-allowed;
}

.am-btn-sm {
    min-height: 34px;
    padding: 0.45rem 0.75rem;
    font-size: 0.78rem;
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

    .am-attempt-item {
        flex-direction: column;
        align-items: stretch;
    }

    .am-action-row form,
    .am-action-row .am-btn {
        width: 100%;
    }

    .am-action-row form .am-btn {
        width: 100%;
    }
}
</style>
