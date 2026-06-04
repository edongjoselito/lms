<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$lesson_records = isset($lesson_records) && is_array($lesson_records) ? $lesson_records : array();
$assessment_records = isset($assessment_records) && is_array($assessment_records) ? $assessment_records : array();
$grade_label = '';
$subject_label = !empty($subject->description) ? $subject->description : (!empty($subject->name) ? $subject->name : 'Subject Records');
$student_records_back = isset($back) ? trim((string) $back) : '';
$student_records_url = 'academic/section_students/' . $section->id . '?subject_id=' . (int) $subject_id;
if ($student_records_back !== '') {
    $student_records_url .= '&back=' . urlencode($student_records_back);
}

if (isset($subject->year_level) && trim((string) $subject->year_level) !== '') {
    $grade_value = trim((string) $subject->year_level);
    $grade_label = is_numeric($grade_value) ? 'Grade ' . str_pad((int) $grade_value, 2, '0', STR_PAD_LEFT) : $grade_value;
}
?>

<div class="sr-page">
    <a href="<?= site_url($student_records_url) ?>" class="sr-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Section Students
    </a>

    <div class="sr-hero">
        <div class="sr-hero-bg"></div>
        <div class="sr-hero-content">
            <div class="sr-hero-left">
                <div class="sr-hero-avatar"><?= htmlspecialchars(strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', (string) $student->name), 0, 2) ?: 'SR')) ?></div>
                <div class="sr-hero-copy">
                    <div class="sr-hero-meta">
                        <span class="sr-tag sr-tag-main"><?= htmlspecialchars($section->name) ?></span>
                        <span class="sr-tag sr-tag-soft"><?= htmlspecialchars($subject->code) ?></span>
                        <?php if ($grade_label !== ''): ?>
                            <span class="sr-tag sr-tag-soft"><?= htmlspecialchars($grade_label) ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="sr-hero-title"><?= htmlspecialchars($student->name) ?></h1>
                    <p class="sr-hero-desc"><?= htmlspecialchars($student->email) ?></p>
                    <p class="sr-hero-sub"><?= htmlspecialchars($subject_label) ?></p>
                </div>
            </div>
            <div class="sr-hero-stats">
                <div class="sr-hero-stat">
                    <div class="sr-hero-stat-num"><?= (int) $progress_percent ?>%</div>
                    <div class="sr-hero-stat-lbl">Progress</div>
                </div>
                <div class="sr-hero-stat">
                    <div class="sr-hero-stat-num"><?= (int) $completed_items ?> / <?= (int) $total_items ?></div>
                    <div class="sr-hero-stat-lbl">Completed Items</div>
                </div>
                <div class="sr-hero-stat">
                    <div class="sr-hero-stat-num"><?= (int) $attempt_count ?></div>
                    <div class="sr-hero-stat-lbl">Assessment Attempts</div>
                </div>
            </div>
        </div>
    </div>

    <div class="sr-grid">
        <div class="sr-panel">
            <div class="sr-panel-head">
                <div class="sr-panel-title">
                    <i class="bi bi-journal-check"></i>
                    <span>Lesson Records</span>
                    <span class="sr-count-pill"><?= (int) $completed_lesson_count ?> / <?= count($lesson_records) ?></span>
                </div>
            </div>

            <?php if (empty($lesson_records)): ?>
                <div class="sr-empty">
                    <i class="bi bi-journal-x"></i>
                    <p>No published lessons found for this subject.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table sr-table mb-0">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Lesson</th>
                                <th>Status</th>
                                <th>Completed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lesson_records as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars($record->module_title) ?></td>
                                    <td><?= htmlspecialchars($record->title) ?></td>
                                    <td>
                                        <?php if (!empty($record->completed_at)): ?>
                                            <span class="sr-badge sr-badge-success">Completed</span>
                                        <?php else: ?>
                                            <span class="sr-badge sr-badge-muted">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= !empty($record->completed_at) ? htmlspecialchars($record->completed_at) : '<span class="text-muted">-</span>' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="sr-panel">
            <div class="sr-panel-head">
                <div class="sr-panel-title">
                    <i class="bi bi-ui-checks-grid"></i>
                    <span>Assessment Results</span>
                    <span class="sr-count-pill"><?= (int) $completed_assessment_count ?> / <?= count($assessment_records) ?></span>
                </div>
            </div>

            <?php if (empty($assessment_records)): ?>
                <div class="sr-empty">
                    <i class="bi bi-ui-checks"></i>
                    <p>No published assessments found for this subject.</p>
                </div>
            <?php else: ?>
                <div class="sr-assessment-list">
                    <?php foreach ($assessment_records as $assessment): ?>
                        <div class="sr-assessment-card">
                            <div class="sr-assessment-head">
                                <div>
                                    <div class="sr-assessment-module"><?= htmlspecialchars($assessment->module_title) ?></div>
                                    <h3 class="sr-assessment-title"><?= htmlspecialchars($assessment->activity_title ?: $assessment->quiz_title) ?></h3>
                                </div>
                                <div class="sr-assessment-meta">
                                    <span class="sr-badge sr-badge-soft"><?= htmlspecialchars(ucfirst($assessment->quiz_type)) ?></span>
                                    <span class="sr-badge sr-badge-soft"><?= count($assessment->attempts) ?> Attempt<?= count($assessment->attempts) === 1 ? '' : 's' ?></span>
                                </div>
                            </div>

                            <?php if (empty($assessment->attempts)): ?>
                                <div class="sr-inline-empty">No attempts yet.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table sr-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Attempt</th>
                                                <th>Status</th>
                                                <th>Score</th>
                                                <th>Percentage</th>
                                                <th>Submitted</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($assessment->attempts as $attempt): ?>
                                                <tr>
                                                    <td>#<?= (int) $attempt->attempt_number ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = 'sr-badge-muted';
                                                        if ($attempt->status === 'graded') {
                                                            $status_class = 'sr-badge-success';
                                                        } elseif ($attempt->status === 'submitted') {
                                                            $status_class = 'sr-badge-warning';
                                                        } elseif ($attempt->status === 'in_progress') {
                                                            $status_class = 'sr-badge-info';
                                                        }
                                                        ?>
                                                        <span class="sr-badge <?= $status_class ?>"><?= htmlspecialchars(ucfirst($attempt->status)) ?></span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $score_value = $attempt->score === null ? '-' : number_format((float) $attempt->score, 2);
                                                        $total_value = $attempt->total_points === null ? number_format((float) $assessment->quiz_total_points, 2) : number_format((float) $attempt->total_points, 2);
                                                        ?>
                                                        <?= $score_value ?> / <?= $total_value ?>
                                                    </td>
                                                    <td><?= $attempt->percentage === null ? '-' : number_format((float) $attempt->percentage, 2) . '%' ?></td>
                                                    <td><?= !empty($attempt->submitted_at) ? htmlspecialchars($attempt->submitted_at) : '<span class="text-muted">-</span>' ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.sr-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0 2.5rem;
}

.sr-back {
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

.sr-back:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
}

.sr-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}

.sr-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.sr-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.sr-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}

.sr-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.sr-hero-avatar {
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
}

.sr-hero-copy {
    min-width: 0;
}

.sr-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.sr-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.sr-tag-main {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.sr-tag-soft {
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.92);
    border: 1px solid rgba(255,255,255,0.22);
}

.sr-hero-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.25rem;
    line-height: 1.2;
}

.sr-hero-desc,
.sr-hero-sub {
    margin: 0;
    color: rgba(255,255,255,0.8);
    line-height: 1.5;
}

.sr-hero-sub {
    margin-top: 0.2rem;
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
}

.sr-hero-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.sr-hero-stat {
    min-width: 120px;
    padding: 0.95rem 1rem;
    border-radius: 16px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.16);
    backdrop-filter: blur(12px);
}

.sr-hero-stat-num {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 0.35rem;
}

.sr-hero-stat-lbl {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.76);
}

.sr-grid {
    display: grid;
    gap: 1.5rem;
}

.sr-panel {
    background: #fff;
    border: 1px solid #dbe7ff;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 18px 40px rgba(15,23,42,0.05);
}

.sr-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.15rem 1.25rem;
    border-bottom: 1px solid #e5eefc;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.sr-panel-title {
    display: inline-flex;
    align-items: center;
    gap: 0.7rem;
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
}

.sr-panel-title i {
    color: #2563eb;
}

.sr-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    border-radius: 999px;
    padding: 0 0.75rem;
    background: #dbeafe;
    color: #1d4ed8;
    font-size: 0.8rem;
    font-weight: 800;
}

.sr-table thead th {
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    background: #f8fbff;
    border-bottom: 1px solid #e5eefc;
}

.sr-table tbody td {
    vertical-align: middle;
    color: #0f172a;
}

.sr-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.28rem 0.6rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
}

.sr-badge-success {
    background: #dcfce7;
    color: #166534;
}

.sr-badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.sr-badge-info {
    background: #dbeafe;
    color: #1d4ed8;
}

.sr-badge-muted {
    background: #f1f5f9;
    color: #475569;
}

.sr-badge-soft {
    background: #eff6ff;
    color: #1d4ed8;
}

.sr-empty {
    padding: 2.5rem 1.5rem;
    text-align: center;
    color: #64748b;
}

.sr-empty i {
    font-size: 1.8rem;
    margin-bottom: 0.75rem;
    display: block;
    color: #94a3b8;
}

.sr-empty p {
    margin: 0;
}

.sr-assessment-list {
    padding: 1rem;
    display: grid;
    gap: 1rem;
}

.sr-assessment-card {
    border: 1px solid #e5eefc;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
}

.sr-assessment-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1rem 0.9rem;
    border-bottom: 1px solid #eef4ff;
    background: #fbfdff;
    flex-wrap: wrap;
}

.sr-assessment-module {
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.sr-assessment-title {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}

.sr-assessment-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.sr-inline-empty {
    padding: 1rem;
    color: #64748b;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .sr-hero-content {
        padding: 1.6rem;
    }

    .sr-hero-left {
        align-items: flex-start;
        flex-direction: column;
    }

    .sr-hero-stats {
        width: 100%;
    }
}
</style>
