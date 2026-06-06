<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
if (!function_exists('module_ilaw_report_value')) {
    function module_ilaw_report_value($lesson_plan, $defaults, $field)
    {
        if ($lesson_plan && isset($lesson_plan->$field) && $lesson_plan->$field !== null && $lesson_plan->$field !== '') {
            return (string) $lesson_plan->$field;
        }

        return isset($defaults[$field]) ? (string) $defaults[$field] : '';
    }
}

if (!function_exists('module_ilaw_report_display')) {
    function module_ilaw_report_display($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '<span class="ilaw-report-muted">Not encoded</span>';
        }

        return nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
    }
}

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
?>

<div class="ilaw-report-page">
    <div class="ilaw-report-actions">
        <a href="<?= htmlspecialchars($back_url, ENT_QUOTES, 'UTF-8') ?>" class="ilaw-report-back">
            <i class="bi bi-arrow-left-short"></i> <?= htmlspecialchars($back_label, ENT_QUOTES, 'UTF-8') ?>
        </a>
        <button type="button" class="ilaw-report-print" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>

    <div class="ilaw-report-header">
        <div>
            <span><?= htmlspecialchars($subject->code ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <h1>ILAW Lesson Plan Report</h1>
            <p><?= htmlspecialchars($module->title ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </div>

    <?php foreach ($session_labels as $day => $session_label): ?>
        <?php
            $lesson_plan = isset($lesson_plans_by_day[(int) $day]) ? $lesson_plans_by_day[(int) $day] : null;
            $defaults = isset($lesson_plan_defaults[(int) $day]) ? $lesson_plan_defaults[(int) $day] : array();
            $duration = module_ilaw_report_value($lesson_plan, $defaults, 'session_duration_minutes');
        ?>
        <section class="ilaw-report-session">
            <div class="ilaw-report-session-head">
                <div>
                    <span>Day <?= (int) $day ?></span>
                    <h2><?= htmlspecialchars(module_ilaw_report_value($lesson_plan, array('session_label' => $session_label), 'session_label'), ENT_QUOTES, 'UTF-8') ?></h2>
                </div>
                <strong><?= $lesson_plan ? 'Encoded' : 'No plan encoded' ?></strong>
            </div>

            <?php if ($lesson_plan): ?>
                <div class="ilaw-report-meta">
                    <div><span>Name of Lesson</span><strong><?= module_ilaw_report_display(module_ilaw_report_value($lesson_plan, $defaults, 'lesson_name')) ?></strong></div>
                    <div><span>Term</span><strong><?= module_ilaw_report_display(module_ilaw_report_value($lesson_plan, $defaults, 'term_name')) ?></strong></div>
                    <div><span>Week Number</span><strong><?= module_ilaw_report_display(module_ilaw_report_value($lesson_plan, $defaults, 'week_number')) ?></strong></div>
                    <div><span>Grade Level and Section</span><strong><?= module_ilaw_report_display(module_ilaw_report_value($lesson_plan, $defaults, 'grade_section')) ?></strong></div>
                    <div><span>Teaching Date</span><strong><?= module_ilaw_report_display(module_ilaw_report_value($lesson_plan, $defaults, 'teaching_date')) ?></strong></div>
                    <div><span>Minutes</span><strong><?= module_ilaw_report_display($duration) ?></strong></div>
                    <div><span>Designed by Teacher/s</span><strong><?= module_ilaw_report_display(module_ilaw_report_value($lesson_plan, $defaults, 'designed_by')) ?></strong></div>
                    <div><span>References</span><strong><?= module_ilaw_report_display(module_ilaw_report_value($lesson_plan, $defaults, 'references_text')) ?></strong></div>
                </div>

                <div class="ilaw-report-band">Intention</div>
                <div class="ilaw-report-field">
                    <h3>Learning Competency</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->learning_competency ?? '') ?></div>
                </div>
                <div class="ilaw-report-field">
                    <h3>Learning Objectives</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->learning_objectives ?? '') ?></div>
                </div>
                <div class="ilaw-report-field">
                    <h3>Learner Context</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->learner_context ?? '') ?></div>
                </div>

                <div class="ilaw-report-band">Learning Experience</div>
                <div class="ilaw-report-field">
                    <h3>Pre-Lesson</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->pre_lesson ?? '') ?></div>
                </div>
                <div class="ilaw-report-field">
                    <h3>Flow</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->lesson_flow ?? '') ?></div>
                </div>
                <div class="ilaw-report-field">
                    <h3>Learning Resources</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->learning_resources ?? '') ?></div>
                </div>
                <div class="ilaw-report-field">
                    <h3>Opportunities for Integration</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->integration ?? '') ?></div>
                </div>

                <div class="ilaw-report-band">Assessment</div>
                <div class="ilaw-report-field">
                    <h3>Formative Assessment</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->formative_assessment ?? '') ?></div>
                </div>

                <div class="ilaw-report-band">Ways Forward</div>
                <div class="ilaw-report-field">
                    <h3>Extended Learning Opportunities</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->extended_learning ?? '') ?></div>
                </div>
                <div class="ilaw-report-field">
                    <h3>Reflections</h3>
                    <div><?= module_ilaw_report_display($lesson_plan->reflections ?? '') ?></div>
                </div>
            <?php else: ?>
                <div class="ilaw-report-empty">No lesson plan has been encoded for this day.</div>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
</div>

<style>
.ilaw-report-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    color: #0f172a;
}
.ilaw-report-actions {
    display: flex;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.ilaw-report-back,
.ilaw-report-print {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border: 0;
    background: transparent;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 800;
    text-decoration: none;
}
.ilaw-report-print {
    padding: 0.45rem 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background: #fff;
}
.ilaw-report-header {
    padding: 1.15rem;
    margin-bottom: 1rem;
    border-radius: 10px;
    background: #0f172a;
    color: #fff;
}
.ilaw-report-header span {
    display: block;
    margin-bottom: 0.25rem;
    color: #bfdbfe;
    font-size: 0.78rem;
    font-weight: 800;
}
.ilaw-report-header h1 {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 900;
}
.ilaw-report-header p {
    margin: 0.25rem 0 0;
    color: #e2e8f0;
}
.ilaw-report-session {
    margin-bottom: 1rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
    break-inside: avoid;
}
.ilaw-report-session-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    padding-bottom: 0.75rem;
    margin-bottom: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}
.ilaw-report-session-head span {
    color: #64748b;
    font-size: 0.76rem;
    font-weight: 800;
    text-transform: uppercase;
}
.ilaw-report-session-head h2 {
    margin: 0.15rem 0 0;
    font-size: 1.05rem;
    font-weight: 900;
}
.ilaw-report-session-head strong {
    padding: 0.25rem 0.55rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #475569;
    font-size: 0.74rem;
    white-space: nowrap;
}
.ilaw-report-meta {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.6rem;
    margin-bottom: 0.85rem;
}
.ilaw-report-meta div {
    padding: 0.65rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #f8fafc;
}
.ilaw-report-meta span {
    display: block;
    margin-bottom: 0.2rem;
    color: #64748b;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
}
.ilaw-report-meta strong {
    display: block;
    color: #0f172a;
    font-size: 0.88rem;
    font-weight: 700;
    overflow-wrap: anywhere;
}
.ilaw-report-band {
    margin: 0.9rem 0 0.5rem;
    padding: 0.45rem 0.6rem;
    border-radius: 8px;
    background: #e0f2fe;
    color: #075985;
    font-size: 0.78rem;
    font-weight: 900;
    text-transform: uppercase;
}
.ilaw-report-field {
    margin-bottom: 0.65rem;
}
.ilaw-report-field h3 {
    margin: 0 0 0.2rem;
    color: #1e293b;
    font-size: 0.9rem;
    font-weight: 900;
}
.ilaw-report-field div {
    min-height: 1.25rem;
    color: #334155;
    font-size: 0.9rem;
    line-height: 1.55;
}
.ilaw-report-empty {
    padding: 1rem;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    color: #64748b;
    background: #f8fafc;
    font-weight: 700;
}
.ilaw-report-muted {
    color: #94a3b8;
    font-style: italic;
    font-weight: 500;
}
@media (max-width: 992px) {
    .ilaw-report-meta {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 576px) {
    .ilaw-report-actions,
    .ilaw-report-session-head {
        flex-direction: column;
    }
    .ilaw-report-meta {
        grid-template-columns: 1fr;
    }
}
@media print {
    @page {
        size: A4;
        margin: 12mm;
    }
    .navbar,
    .sidebar,
    .ilaw-report-actions {
        display: none !important;
    }
    .ilaw-report-page {
        padding: 0;
    }
    .ilaw-report-header {
        background: #fff !important;
        color: #000 !important;
        border: 1px solid #000;
    }
    .ilaw-report-header span,
    .ilaw-report-header p,
    .ilaw-report-meta span,
    .ilaw-report-field div {
        color: #000 !important;
    }
    .ilaw-report-session {
        border-color: #000;
        box-shadow: none;
    }
    .ilaw-report-band,
    .ilaw-report-meta div,
    .ilaw-report-empty {
        background: #fff !important;
    }
}
</style>
