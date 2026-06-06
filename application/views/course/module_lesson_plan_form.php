<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
if (!function_exists('module_ilaw_form_value')) {
    function module_ilaw_form_value($lesson_plan, $defaults, $field)
    {
        if ($lesson_plan && isset($lesson_plan->$field) && $lesson_plan->$field !== null && $lesson_plan->$field !== '') {
            return (string) $lesson_plan->$field;
        }

        return isset($defaults[$field]) ? (string) $defaults[$field] : '';
    }
}

if (!function_exists('module_ilaw_form_field')) {
    function module_ilaw_form_field($lesson_plan, $defaults, $name, $label, $type = 'textarea', $rows = 3, $placeholder = '')
    {
        $value = module_ilaw_form_value($lesson_plan, $defaults, $name);
        ?>
        <div class="ilaw-form-field">
            <label class="form-label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
            <?php if ($type === 'input'): ?>
                <input type="text" class="form-control" name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') ?>">
            <?php elseif ($type === 'date'): ?>
                <input type="date" class="form-control" name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') ?>">
            <?php elseif ($type === 'number'): ?>
                <input type="number" class="form-control" name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" min="1" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') ?>">
            <?php else: ?>
                <textarea class="form-control" name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" rows="<?= (int) $rows ?>" placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php endif; ?>
        </div>
        <?php
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
$session_form_urls = isset($session_form_urls) && is_array($session_form_urls) ? $session_form_urls : array();
$lesson_plans = isset($lesson_plans) && is_array($lesson_plans) ? $lesson_plans : array();
$lesson_plans_by_day = array();
foreach ($lesson_plans as $plan_item) {
    $lesson_plans_by_day[(int) ($plan_item->plan_day ?? 1)] = $plan_item;
}
$lesson_plan_defaults = isset($lesson_plan_defaults) && is_array($lesson_plan_defaults) ? $lesson_plan_defaults : array();
$current_session_label = isset($session_labels[$selected_plan_day]) ? $session_labels[$selected_plan_day] : 'SESSION';
?>

<div class="ilaw-form-page">
    <div class="ilaw-form-top">
        <a href="<?= htmlspecialchars($back_url, ENT_QUOTES, 'UTF-8') ?>" class="ilaw-back">
            <i class="bi bi-arrow-left-short"></i> Back to Lesson Plan
        </a>
    </div>

    <div class="ilaw-form-hero">
        <div>
            <div class="ilaw-form-meta">
                <span><?= htmlspecialchars($subject->code ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                <span><?= htmlspecialchars($module->title ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <h1><?= $lesson_plan ? 'Edit' : 'Create' ?> ILAW Lesson Plan</h1>
            <p><?= htmlspecialchars($current_session_label, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="ilaw-form-card">
        <?= form_open($form_url, array('id' => 'ilawPlanForm')) ?>
            <div class="ilaw-form-section">
                <h6>Session</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Select Session</label>
                        <select class="form-select" name="plan_day" id="ilawPlanDaySelect">
                            <?php foreach ($session_labels as $day => $label): ?>
                                <?php $has_plan = isset($lesson_plans_by_day[(int) $day]); ?>
                                <option value="<?= (int) $day ?>" data-url="<?= htmlspecialchars($session_form_urls[$day] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= (int) $day === $selected_plan_day ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?><?= $has_plan ? ' - Created' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'session_duration_minutes', 'Minutes', 'number'); ?>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <span class="ilaw-session-status <?= $lesson_plan ? 'done' : '' ?>"><?= $lesson_plan ? 'Existing plan' : 'New plan' ?></span>
                    </div>
                </div>
            </div>

            <div class="ilaw-form-section">
                <h6>Lesson Details</h6>
                <input type="hidden" name="learning_area" value="<?= htmlspecialchars(module_ilaw_form_value($lesson_plan, $lesson_plan_defaults, 'learning_area'), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="grade_section" value="<?= htmlspecialchars(module_ilaw_form_value($lesson_plan, $lesson_plan_defaults, 'grade_section'), ENT_QUOTES, 'UTF-8') ?>">
                <div class="ilaw-auto-meta">
                    <div>
                        <span>Grade Level and Section</span>
                        <strong><?= htmlspecialchars(module_ilaw_form_value($lesson_plan, $lesson_plan_defaults, 'grade_section'), ENT_QUOTES, 'UTF-8') ?></strong>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'lesson_name', 'Name of Lesson', 'input'); ?></div>
                    <div class="col-md-3"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'term_name', 'Term', 'input'); ?></div>
                    <div class="col-md-3"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'week_number', 'Week Number', 'input'); ?></div>
                    <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'teaching_date', 'Teaching Date', 'date'); ?></div>
                    <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'designed_by', 'Designed by Teacher/s', 'input'); ?></div>
                    <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'references_text', 'References', 'textarea', 3); ?></div>
                </div>
            </div>

            <div class="ilaw-form-section">
                <h6>Intention</h6>
                <div class="row g-3">
                    <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learning_competency', 'Learning Competency', 'textarea', 4); ?></div>
                    <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learning_objectives', 'Learning Objectives', 'textarea', 4); ?></div>
                    <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learner_context', 'Learner Context', 'textarea', 4); ?></div>
                </div>
            </div>

            <div class="ilaw-form-section">
                <h6>Learning Experience</h6>
                <div class="row g-3">
                    <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'pre_lesson', 'Pre-Lesson', 'textarea', 4); ?></div>
                    <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'lesson_flow', 'Flow', 'textarea', 6); ?></div>
                    <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learning_resources', 'Learning Resources', 'textarea', 4); ?></div>
                    <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'integration', 'Opportunities for Integration', 'textarea', 4); ?></div>
                </div>
            </div>

            <div class="ilaw-form-section">
                <h6>Assessment</h6>
                <?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'formative_assessment', 'Formative Assessment', 'textarea', 5); ?>
            </div>

            <div class="ilaw-form-section">
                <h6>Ways Forward</h6>
                <div class="row g-3">
                    <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'extended_learning', 'Extended Learning Opportunities', 'textarea', 4); ?></div>
                    <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'reflections', 'Reflections', 'textarea', 4); ?></div>
                    <input type="hidden" name="prepared_by" value="<?= htmlspecialchars(module_ilaw_form_value($lesson_plan, $lesson_plan_defaults, 'prepared_by'), ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="prepared_position" value="<?= htmlspecialchars(module_ilaw_form_value($lesson_plan, $lesson_plan_defaults, 'prepared_position'), ENT_QUOTES, 'UTF-8') ?>">
                    <?php if (false): // Prepared by fields hidden for now. ?>
                        <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'prepared_by', 'Prepared by', 'input'); ?></div>
                        <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'prepared_position', 'Position', 'input'); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="ilaw-form-actions">
                <a href="<?= htmlspecialchars($back_url, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><?= $lesson_plan ? 'Update Session Plan' : 'Create Session Plan' ?></button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
(function () {
    var select = document.getElementById('ilawPlanDaySelect');
    if (!select) {
        return;
    }

    select.addEventListener('change', function () {
        var option = select.options[select.selectedIndex];
        var targetUrl = option ? option.getAttribute('data-url') : '';
        if (targetUrl) {
            window.location.href = targetUrl;
        }
    });
})();
</script>

<style>
.ilaw-form-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
}
.ilaw-form-top { margin-bottom: 1rem; }
.ilaw-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
}
.ilaw-form-hero {
    padding: 1.25rem;
    margin-bottom: 1rem;
    border-radius: 10px;
    color: #fff;
    background: linear-gradient(135deg, #0f766e 0%, #2563eb 58%, #7c3aed 100%);
}
.ilaw-form-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.35rem;
}
.ilaw-form-meta span {
    padding: 0.22rem 0.5rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.16);
    font-size: 0.74rem;
    font-weight: 800;
}
.ilaw-form-hero h1 {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 900;
}
.ilaw-form-hero p {
    margin: 0.25rem 0 0;
    opacity: 0.9;
}
.ilaw-form-card {
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 14px rgba(15, 23, 42, 0.06);
}
.ilaw-form-section {
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 1rem;
}
.ilaw-form-section h6 {
    margin: 0 0 0.85rem;
    color: #1e293b;
    font-weight: 900;
}
.ilaw-form-field textarea { resize: vertical; }
.ilaw-auto-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.ilaw-auto-meta div {
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #f8fafc;
}
.ilaw-auto-meta span {
    display: block;
    margin-bottom: 0.25rem;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
}
.ilaw-auto-meta strong {
    display: block;
    min-height: 1.25rem;
    color: #0f172a;
    font-size: 0.9rem;
    font-weight: 800;
    overflow-wrap: anywhere;
}
.ilaw-session-status {
    display: inline-flex;
    align-items: center;
    min-height: 38px;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 0.8rem;
    font-weight: 800;
}
.ilaw-session-status.done {
    background: #dcfce7;
    color: #166534;
}
.ilaw-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}
@media (max-width: 576px) {
    .ilaw-form-card,
    .ilaw-form-section {
        padding: 0.85rem;
    }
    .ilaw-form-actions {
        flex-direction: column;
    }
    .ilaw-form-actions .btn {
        width: 100%;
    }
    .ilaw-auto-meta {
        grid-template-columns: 1fr;
    }
}
</style>
