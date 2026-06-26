<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
if (!function_exists('module_ilaw_value')) {
    function module_ilaw_value($lesson_plan, $defaults, $field)
    {
        if ($lesson_plan && isset($lesson_plan->$field) && $lesson_plan->$field !== null && $lesson_plan->$field !== '') {
            return (string) $lesson_plan->$field;
        }

        return isset($defaults[$field]) ? (string) $defaults[$field] : '';
    }
}

if (!function_exists('module_ilaw_display')) {
    function module_ilaw_display($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '<span class="ps-muted">Not encoded</span>';
        }

        return nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
    }
}

$selected_plan_day = isset($selected_plan_day) ? max(1, min(5, (int) $selected_plan_day)) : 1;
$session_labels = isset($session_labels) && is_array($session_labels) ? $session_labels : array(
    1 => 'FIRST SESSION',
    2 => 'SECOND SESSION',
    3 => 'THIRD SESSION',
    4 => 'FOURTH SESSION',
    5 => 'FIFTH SESSION',
);
$lesson_plans = isset($lesson_plans) && is_array($lesson_plans) ? $lesson_plans : array();
$lesson_plans_by_day = array();
foreach ($lesson_plans as $plan_item) {
    $lesson_plans_by_day[(int) ($plan_item->plan_day ?? 1)] = $plan_item;
}

$lesson_plan_defaults = isset($lesson_plan_defaults) && is_array($lesson_plan_defaults) ? $lesson_plan_defaults : array();
$display_lesson_name = '';
if ($lesson_plan) {
    $display_lesson_name = trim((string) ($lesson_plan->lesson_name ?? ''));
}
$current_session_label = module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'session_label');
if ($current_session_label === '' && isset($session_labels[$selected_plan_day])) {
    $current_session_label = $session_labels[$selected_plan_day];
}
$current_duration = module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'session_duration_minutes');
$current_duration = $current_duration !== '' ? $current_duration : '0';

$total_sessions = count($session_labels);
$completed_sessions = count($lesson_plans_by_day);
?>

<div class="ps-page">
    <a href="<?= $back_url ?>" class="ps-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> <?= htmlspecialchars($back_label, ENT_QUOTES, 'UTF-8') ?>
    </a>

    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">IL</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree"><?= htmlspecialchars($subject->code ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="ps-tag ps-tag-code"><?= htmlspecialchars($module->title ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <h1 class="ps-hero-title">ILAW Lesson Plan</h1>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $total_sessions ?></div>
                    <div class="ps-hero-stat-lbl">Total Sessions</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $completed_sessions ?></div>
                    <div class="ps-hero-stat-lbl">Completed</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) ($total_sessions - $completed_sessions) ?></div>
                    <div class="ps-hero-stat-lbl">Pending</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $current_duration ?></div>
                    <div class="ps-hero-stat-lbl">Minutes</div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>Session Days</span>
                    <span class="ps-count-pill"><?= (int) $total_sessions ?></span>
                </div>
                <div class="ps-card-tools">
                    <?php if ($can_edit): ?>
                        <a href="<?= htmlspecialchars($form_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="ps-submit-btn ps-submit-btn-inline">
                            <i class="bi bi-plus-lg"></i> <?= $lesson_plan ? 'Edit Day Plan' : 'Create Day Plan' ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="ps-day-tabs">
                <?php foreach ($session_labels as $day => $label): ?>
                    <?php $has_plan = isset($lesson_plans_by_day[(int) $day]); ?>
                    <a href="<?= htmlspecialchars($day_urls[$day] ?? '#', ENT_QUOTES, 'UTF-8') ?>" class="ps-day-tab <?= (int) $day === $selected_plan_day ? 'ps-day-tab-active' : '' ?>">
                        <span class="ps-day-number">Day <?= (int) $day ?></span>
                        <span class="ps-day-label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="ps-day-status <?= $has_plan ? 'ps-day-status-done' : '' ?>"><?= $has_plan ? 'Created' : 'Pending' ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full" style="margin-top: 1.5rem;">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span><?= htmlspecialchars($current_session_label, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="ps-card-tools">
                    <?php if ($can_edit): ?>
                        <?php if ($lesson_plan): ?>
                            <a href="<?= $delete_url ?>" class="ps-action-btn ps-action-del" onclick="return confirm('Delete this day lesson plan?');">
                                <i class="bi bi-trash3-fill"></i> Delete
                            </a>
                        <?php endif; ?>
                        <a href="<?= htmlspecialchars($form_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="ps-action-btn ps-action-edit">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($lesson_plan): ?>
                <div class="ps-content">
                    <div class="ps-grid ps-grid-meta">
                        <div><strong>Term</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'term_name')) ?></span></div>
                        <div><strong>Week Number</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'week_number')) ?></span></div>
                        <div><strong>Grade Level and Section</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'grade_section')) ?></span></div>
                        <div><strong>Designed by Teacher/s</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'designed_by')) ?></span></div>
                        <div><strong>Teaching Date</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'teaching_date')) ?></span></div>
                        <div><strong>References</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'references_text')) ?></span></div>
                    </div>

                    <div class="ps-section">
                        <h3>Name of Lesson</h3>
                        <div class="ps-text"><?= module_ilaw_display($display_lesson_name) ?></div>
                    </div>

                    <div class="ps-band">
                        <h3>INTENTION</h3>
                        <p>Meaningful learning experiences are anchored in how we frame them.</p>
                    </div>
                    <div class="ps-section">
                        <h4>Learning Competency</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->learning_competency ?? '') ?></div>
                    </div>
                    <div class="ps-section">
                        <h4>Learning Objectives</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->learning_objectives ?? '') ?></div>
                    </div>
                    <div class="ps-section">
                        <h4>Learner Context</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->learner_context ?? '') ?></div>
                    </div>

                    <div class="ps-band">
                        <h3>LEARNING EXPERIENCE</h3>
                        <p>Each activity and interaction builds towards meaningful understanding and growth.</p>
                    </div>
                    <div class="ps-section">
                        <h4>Pre-Lesson</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->pre_lesson ?? '') ?></div>
                    </div>
                    <div class="ps-section">
                        <h4>Flow</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->lesson_flow ?? '') ?></div>
                    </div>
                    <div class="ps-section">
                        <h4>Learning Resources</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->learning_resources ?? '') ?></div>
                    </div>
                    <div class="ps-section">
                        <h4>Opportunities for Integration</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->integration ?? '') ?></div>
                    </div>

                    <div class="ps-band">
                        <h3>ASSESSMENT</h3>
                        <p>Assessments reveal what learners have gained and what they still need help with.</p>
                    </div>
                    <div class="ps-section">
                        <h4>Formative Assessment</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->formative_assessment ?? '') ?></div>
                    </div>

                    <div class="ps-band">
                        <h3>WAYS FORWARD</h3>
                        <p>Pause and reflect on what happened today.</p>
                    </div>
                    <div class="ps-section">
                        <h4>Extended Learning Opportunities</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->extended_learning ?? '') ?></div>
                    </div>
                    <div class="ps-section">
                        <h4>Reflections</h4>
                        <div class="ps-text"><?= module_ilaw_display($lesson_plan->reflections ?? '') ?></div>
                    </div>

                    <?php if (false): // Prepared by hidden for now. ?>
                        <div class="ps-prepared">
                            <span>Prepared by</span>
                            <strong><?= module_ilaw_display($lesson_plan->prepared_by ?? '') ?></strong>
                            <small><?= module_ilaw_display($lesson_plan->prepared_position ?? '') ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="ps-empty">
                    <div class="ps-empty-icon">
                        <i class="bi bi-file-earmark-plus"></i>
                    </div>
                    <div class="ps-empty-title">No day plan yet</div>
                    <div class="ps-empty-sub">Day <?= (int) $selected_plan_day ?> is ready for encoding.</div>
                    <?php if ($can_edit): ?>
                        <a href="<?= htmlspecialchars($form_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="ps-submit-btn ps-empty-btn">
                            <i class="bi bi-plus-lg"></i> Create Day Plan
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.ps-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

.ps-back {
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

.ps-back:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
}

.ps-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}

.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.ps-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.ps-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}

.ps-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.ps-hero-avatar {
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

.ps-hero-info {
    min-width: 0;
}

.ps-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.ps-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.ps-tag-degree {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.ps-tag-code {
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.9);
    border: 1px solid rgba(255,255,255,0.25);
}

.ps-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.ps-hero-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
    flex-wrap: wrap;
}

.ps-hero-stat {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 100px;
}

.ps-hero-stat-num {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.ps-hero-stat-lbl {
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255,255,255,0.75);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-top: 0.3rem;
}

.ps-layout {
    display: grid;
    gap: 1.5rem;
    align-items: start;
}

.ps-layout-full {
    grid-template-columns: 1fr;
}

.ps-card {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}

.ps-subject-card {
    overflow: visible;
}

.ps-subject-card > .ps-card-head {
    border-radius: 20px 20px 0 0;
}

.ps-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    flex-wrap: wrap;
}

.ps-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.ps-card-title i {
    color: #2563eb;
    font-size: 1rem;
}

.ps-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.6rem;
    letter-spacing: 0.02em;
}

.ps-card-tools {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.ps-day-tabs {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0.6rem;
    padding: 1.25rem;
}

.ps-day-tab {
    display: flex;
    min-height: 84px;
    flex-direction: column;
    gap: 0.2rem;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
    color: #334155;
    text-decoration: none;
    transition: all 0.14s ease;
}

.ps-day-tab:hover {
    border-color: #2563eb;
    box-shadow: 0 2px 8px rgba(37,99,235,0.12);
    text-decoration: none;
}

.ps-day-tab-active {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

.ps-day-number {
    font-size: 0.9rem;
    font-weight: 800;
    color: #1e293b;
}

.ps-day-label {
    font-size: 0.72rem;
    color: #64748b;
    font-weight: 700;
}

.ps-day-status {
    margin-top: auto;
    align-self: flex-start;
    padding: 0.16rem 0.45rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 0.68rem;
    font-weight: 800;
}

.ps-day-status-done {
    background: #dcfce7;
    color: #166534;
}

.ps-grade-list {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.ps-grade-card {
    border: 1px solid #eaecf0;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 1px 8px rgba(0,0,0,0.04);
    transition: all 0.14s ease;
}

.ps-grade-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.ps-grade-active {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

.ps-grade-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #eef2f7;
    background: #fbfcff;
    flex-wrap: wrap;
}

.ps-grade-head-left {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    min-width: 0;
}

.ps-grade-code {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1d4ed8;
    font-size: 0.85rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ps-grade-copy {
    min-width: 0;
}

.ps-grade-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.15rem;
}

.ps-grade-sub {
    font-size: 0.82rem;
    color: #64748b;
}

.ps-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.38rem 0.7rem;
    border-radius: 9px;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.14s ease;
    white-space: nowrap;
    cursor: pointer;
}

.ps-action-view {
    background: #dbeafe;
    color: #1d4ed8;
}

.ps-action-view:hover {
    background: #bfdbfe;
    color: #1e40af;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-action-edit {
    background: #fef9c3;
    color: #a16207;
}

.ps-action-edit:hover {
    background: #fef08a;
    color: #854d0e;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-action-del {
    background: #fee2e2;
    color: #dc2626;
}

.ps-action-del:hover {
    background: #fecaca;
    color: #b91c1c;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    background: #2563eb;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.14s ease;
    border: none;
    cursor: pointer;
}

.ps-submit-btn:hover {
    background: #1d4ed8;
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-submit-btn-inline {
    display: inline-flex;
}

.ps-content {
    padding: 1.25rem;
}

.ps-grid {
    display: grid;
    gap: 0.75rem;
}

.ps-grid-meta {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    margin-bottom: 1rem;
}

.ps-grid-meta > div {
    padding: 0.8rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.ps-grid-meta strong {
    display: block;
    color: #475569;
    font-size: 0.72rem;
    text-transform: uppercase;
    margin-bottom: 0.35rem;
}

.ps-grid-meta span {
    display: block;
    color: #0f172a;
    font-size: 0.88rem;
    line-height: 1.5;
}

.ps-section {
    margin-bottom: 0.85rem;
}

.ps-section h3,
.ps-section h4 {
    font-size: 0.9rem;
    margin: 0 0 0.4rem;
    font-weight: 800;
    color: #0f172a;
}

.ps-text {
    min-height: 44px;
    padding: 0.85rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #334155;
    line-height: 1.6;
    white-space: normal;
}

.ps-muted {
    color: #94a3b8;
    font-style: italic;
}

.ps-band {
    padding: 0.85rem 1rem;
    margin: 1rem 0;
    background: #eef2ff;
    border-left: 4px solid #4f46e5;
    border-radius: 8px;
}

.ps-band h3 {
    font-size: 0.86rem;
    margin: 0 0 0.25rem;
    font-weight: 900;
    color: #312e81;
}

.ps-band p {
    margin: 0;
    color: #475569;
    font-size: 0.82rem;
}

.ps-prepared {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    display: grid;
    gap: 0.2rem;
}

.ps-prepared span {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 700;
}

.ps-prepared strong {
    color: #0f172a;
    font-size: 0.95rem;
}

.ps-prepared small {
    color: #64748b;
}

.ps-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4rem 2rem;
    text-align: center;
}

.ps-empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
    font-size: 1.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.ps-empty-title {
    font-size: 1rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.4rem;
}

.ps-empty-sub {
    font-size: 0.85rem;
    color: #94a3b8;
    max-width: 300px;
    line-height: 1.5;
}

.ps-empty-btn {
    margin-top: 1rem;
}

@media (max-width: 992px) {
    .ps-grid-meta {
        grid-template-columns: 1fr;
    }
    .ps-day-tabs {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 576px) {
    .ps-hero-content {
        align-items: flex-start;
        flex-direction: column;
    }
    .ps-hero-stats {
        width: 100%;
        justify-content: center;
    }
    .ps-card-head {
        align-items: flex-start;
        flex-direction: column;
    }
    .ps-card-tools {
        width: 100%;
        justify-content: flex-start;
    }
    .ps-submit-btn {
        width: 100%;
        justify-content: center;
    }
    .ps-day-tabs {
        grid-template-columns: 1fr;
    }
}
</style>
