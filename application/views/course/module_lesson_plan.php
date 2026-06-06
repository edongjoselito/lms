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
            return '<span class="ilaw-muted">Not encoded</span>';
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
$current_duration = $current_duration !== '' ? $current_duration : '45';
?>

<div class="ilaw-page">
    <div class="ilaw-top-actions">
        <a href="<?= $back_url ?>" class="ilaw-back">
            <i class="bi bi-arrow-left-short"></i> <?= htmlspecialchars($back_label, ENT_QUOTES, 'UTF-8') ?>
        </a>
    </div>

    <div class="ilaw-hero">
        <div class="ilaw-hero-bg"></div>
        <div class="ilaw-hero-content">
            <div class="ilaw-hero-avatar">ILAW</div>
            <div class="ilaw-hero-info">
                <div class="ilaw-hero-meta">
                    <span><?= htmlspecialchars($subject->code ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                    <span><?= htmlspecialchars($module->title ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                    <span>Day <?= (int) $selected_plan_day ?></span>
                </div>
                <h1>ILAW Lesson Plan</h1>
                <p>Instructional Learning and Assessment Worksheet</p>
            </div>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="ilaw-day-tabs">
        <?php foreach ($session_labels as $day => $label): ?>
            <?php $has_plan = isset($lesson_plans_by_day[(int) $day]); ?>
            <a href="<?= htmlspecialchars($day_urls[$day] ?? '#', ENT_QUOTES, 'UTF-8') ?>" class="ilaw-day-tab <?= (int) $day === $selected_plan_day ? 'active' : '' ?>">
                <span class="ilaw-day-number">Day <?= (int) $day ?></span>
                <span class="ilaw-day-label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="ilaw-day-status <?= $has_plan ? 'done' : '' ?>"><?= $has_plan ? 'Created' : 'Pending' ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="ilaw-card">
        <div class="ilaw-card-head">
            <div>
                <h2><?= htmlspecialchars($current_session_label, ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= (int) $current_duration ?> Minutes</p>
            </div>
            <?php if ($can_edit): ?>
                <div class="ilaw-head-actions">
                    <?php if ($lesson_plan): ?>
                        <a href="<?= $delete_url ?>" class="ilaw-btn ilaw-btn-danger" onclick="return confirm('Delete this day lesson plan?');">
                            <i class="bi bi-trash3"></i> Delete
                        </a>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($form_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="ilaw-btn ilaw-btn-primary">
                        <i class="bi <?= $lesson_plan ? 'bi-pencil-square' : 'bi-plus-lg' ?>"></i>
                        <?= $lesson_plan ? 'Edit Day Plan' : 'Create Day Plan' ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($lesson_plan): ?>
            <div class="ilaw-content">
                <div class="ilaw-grid ilaw-grid-meta">
                    <div><strong>Term</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'term_name')) ?></span></div>
                    <div><strong>Week Number</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'week_number')) ?></span></div>
                    <div><strong>Grade Level and Section</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'grade_section')) ?></span></div>
                    <div><strong>Designed by Teacher/s</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'designed_by')) ?></span></div>
                    <div><strong>Teaching Date</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'teaching_date')) ?></span></div>
                    <div><strong>References</strong><span><?= module_ilaw_display(module_ilaw_value($lesson_plan, $lesson_plan_defaults, 'references_text')) ?></span></div>
                </div>

                <div class="ilaw-section">
                    <h3>Name of Lesson</h3>
                    <div class="ilaw-text"><?= module_ilaw_display($display_lesson_name) ?></div>
                </div>

                <div class="ilaw-band">
                    <h3>INTENTION</h3>
                    <p>Meaningful learning experiences are anchored in how we frame them.</p>
                </div>
                <div class="ilaw-section">
                    <h4>Learning Competency</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->learning_competency ?? '') ?></div>
                </div>
                <div class="ilaw-section">
                    <h4>Learning Objectives</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->learning_objectives ?? '') ?></div>
                </div>
                <div class="ilaw-section">
                    <h4>Learner Context</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->learner_context ?? '') ?></div>
                </div>

                <div class="ilaw-band">
                    <h3>LEARNING EXPERIENCE</h3>
                    <p>Each activity and interaction builds towards meaningful understanding and growth.</p>
                </div>
                <div class="ilaw-section">
                    <h4>Pre-Lesson</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->pre_lesson ?? '') ?></div>
                </div>
                <div class="ilaw-section">
                    <h4>Flow</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->lesson_flow ?? '') ?></div>
                </div>
                <div class="ilaw-section">
                    <h4>Learning Resources</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->learning_resources ?? '') ?></div>
                </div>
                <div class="ilaw-section">
                    <h4>Opportunities for Integration</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->integration ?? '') ?></div>
                </div>

                <div class="ilaw-band">
                    <h3>ASSESSMENT</h3>
                    <p>Assessments reveal what learners have gained and what they still need help with.</p>
                </div>
                <div class="ilaw-section">
                    <h4>Formative Assessment</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->formative_assessment ?? '') ?></div>
                </div>

                <div class="ilaw-band">
                    <h3>WAYS FORWARD</h3>
                    <p>Pause and reflect on what happened today.</p>
                </div>
                <div class="ilaw-section">
                    <h4>Extended Learning Opportunities</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->extended_learning ?? '') ?></div>
                </div>
                <div class="ilaw-section">
                    <h4>Reflections</h4>
                    <div class="ilaw-text"><?= module_ilaw_display($lesson_plan->reflections ?? '') ?></div>
                </div>

                <?php if (false): // Prepared by hidden for now. ?>
                    <div class="ilaw-prepared">
                        <span>Prepared by</span>
                        <strong><?= module_ilaw_display($lesson_plan->prepared_by ?? '') ?></strong>
                        <small><?= module_ilaw_display($lesson_plan->prepared_position ?? '') ?></small>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="ilaw-empty">
                <div class="ilaw-empty-icon"><i class="bi bi-file-earmark-plus"></i></div>
                <h3>No day plan yet</h3>
                <p>Day <?= (int) $selected_plan_day ?> is ready for encoding.</p>
                <?php if ($can_edit): ?>
                    <a href="<?= htmlspecialchars($form_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="ilaw-btn ilaw-btn-primary">
                        <i class="bi bi-plus-lg"></i> Create Day Plan
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.ilaw-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
}
.ilaw-top-actions { margin-bottom: 1rem; }
.ilaw-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
}
.ilaw-hero {
    position: relative;
    overflow: hidden;
    border-radius: 14px;
    margin-bottom: 1rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.12);
}
.ilaw-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0f766e 0%, #2563eb 58%, #7c3aed 100%);
}
.ilaw-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    color: #fff;
}
.ilaw-hero-avatar {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.28);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
}
.ilaw-hero-info h1 {
    font-size: 1.45rem;
    margin: 0.25rem 0;
    font-weight: 800;
}
.ilaw-hero-info p { margin: 0; opacity: 0.88; }
.ilaw-hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    font-size: 0.76rem;
    font-weight: 700;
}
.ilaw-hero-meta span {
    padding: 0.25rem 0.55rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.16);
}
.ilaw-day-tabs {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0.6rem;
    margin-bottom: 1rem;
}
.ilaw-day-tab {
    display: flex;
    min-height: 84px;
    flex-direction: column;
    gap: 0.2rem;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #334155;
    text-decoration: none;
}
.ilaw-day-tab.active {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.ilaw-day-number { font-size: 0.9rem; font-weight: 800; }
.ilaw-day-label { font-size: 0.72rem; color: #64748b; font-weight: 700; }
.ilaw-day-status {
    margin-top: auto;
    align-self: flex-start;
    padding: 0.16rem 0.45rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 0.68rem;
    font-weight: 800;
}
.ilaw-day-status.done { background: #dcfce7; color: #166534; }
.ilaw-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(15, 23, 42, 0.06);
}
.ilaw-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
}
.ilaw-card-head h2 {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
}
.ilaw-card-head p { margin: 0.15rem 0 0; color: #64748b; font-size: 0.82rem; }
.ilaw-head-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.ilaw-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border: 0;
    border-radius: 7px;
    padding: 0.55rem 0.8rem;
    font-size: 0.82rem;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
}
.ilaw-btn-primary { background: #2563eb; color: #fff; }
.ilaw-btn-danger { background: #fee2e2; color: #b91c1c; }
.ilaw-content { padding: 1.25rem; }
.ilaw-grid {
    display: grid;
    gap: 0.75rem;
}
.ilaw-grid-meta {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    margin-bottom: 1rem;
}
.ilaw-grid-meta > div {
    padding: 0.8rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}
.ilaw-grid-meta strong {
    display: block;
    color: #475569;
    font-size: 0.72rem;
    text-transform: uppercase;
    margin-bottom: 0.35rem;
}
.ilaw-grid-meta span { display: block; color: #0f172a; font-size: 0.88rem; line-height: 1.5; }
.ilaw-band {
    padding: 0.85rem 1rem;
    margin: 1rem 0;
    background: #eef2ff;
    border-left: 4px solid #4f46e5;
    border-radius: 8px;
}
.ilaw-band h3 {
    font-size: 0.86rem;
    margin: 0 0 0.25rem;
    font-weight: 900;
    color: #312e81;
}
.ilaw-band p { margin: 0; color: #475569; font-size: 0.82rem; }
.ilaw-section {
    margin-bottom: 0.85rem;
}
.ilaw-section h3,
.ilaw-section h4 {
    font-size: 0.9rem;
    margin: 0 0 0.4rem;
    font-weight: 800;
    color: #0f172a;
}
.ilaw-text {
    min-height: 44px;
    padding: 0.85rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #334155;
    line-height: 1.6;
    white-space: normal;
}
.ilaw-muted { color: #94a3b8; font-style: italic; }
.ilaw-prepared {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    display: grid;
    gap: 0.2rem;
}
.ilaw-prepared span { color: #64748b; font-size: 0.75rem; font-weight: 700; }
.ilaw-prepared strong { color: #0f172a; font-size: 0.95rem; }
.ilaw-prepared small { color: #64748b; }
.ilaw-empty {
    padding: 3rem 1rem;
    text-align: center;
    color: #64748b;
}
.ilaw-empty-icon {
    width: 58px;
    height: 58px;
    margin: 0 auto 1rem;
    border-radius: 14px;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.ilaw-empty h3 { font-size: 1rem; color: #0f172a; font-weight: 800; }
.ilaw-form-section {
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 1rem;
}
.ilaw-form-section h6 {
    margin: 0 0 0.85rem;
    font-weight: 900;
    color: #1e293b;
}
.ilaw-form-field textarea { resize: vertical; }
@media (max-width: 992px) {
    .ilaw-day-tabs { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .ilaw-grid-meta { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
    .ilaw-hero-content,
    .ilaw-card-head {
        align-items: flex-start;
        flex-direction: column;
    }
    .ilaw-day-tabs { grid-template-columns: 1fr; }
    .ilaw-head-actions { width: 100%; }
    .ilaw-btn { justify-content: center; width: 100%; }
}
</style>
