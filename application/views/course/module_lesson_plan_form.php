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
        <div class="ps-form-field">
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

<div class="ps-page">
    <a href="<?= htmlspecialchars($back_url, ENT_QUOTES, 'UTF-8') ?>" class="ps-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Lesson Plan
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
                    <h1 class="ps-hero-title"><?= $lesson_plan ? 'Edit' : 'Create' ?> ILAW Lesson Plan</h1>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $selected_plan_day ?></div>
                    <div class="ps-hero-stat-lbl">Day</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= count($lesson_plans_by_day) ?></div>
                    <div class="ps-hero-stat-lbl">Completed</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) (count($session_labels) - count($lesson_plans_by_day)) ?></div>
                    <div class="ps-hero-stat-lbl">Pending</div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-pencil-square-fill"></i>
                    <span><?= htmlspecialchars($current_session_label, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>

            <div class="ps-content">
                <?= form_open($form_url, array('id' => 'ilawPlanForm')) ?>
                    <div class="ps-form-section">
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
                                <span class="ps-session-status <?= $lesson_plan ? 'done' : '' ?>"><?= $lesson_plan ? 'Existing plan' : 'New plan' ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="ps-form-section">
                        <h6>Lesson Details</h6>
                        <input type="hidden" name="learning_area" value="<?= htmlspecialchars(module_ilaw_form_value($lesson_plan, $lesson_plan_defaults, 'learning_area'), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="grade_section" value="<?= htmlspecialchars(module_ilaw_form_value($lesson_plan, $lesson_plan_defaults, 'grade_section'), ENT_QUOTES, 'UTF-8') ?>">
                        <div class="ps-auto-meta">
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

                    <div class="ps-form-section">
                        <h6>Intention</h6>
                        <div class="row g-3">
                            <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learning_competency', 'Learning Competency', 'textarea', 4); ?></div>
                            <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learning_objectives', 'Learning Objectives', 'textarea', 4); ?></div>
                            <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learner_context', 'Learner Context', 'textarea', 4); ?></div>
                        </div>
                    </div>

                    <div class="ps-form-section">
                        <h6>Learning Experience</h6>
                        <div class="row g-3">
                            <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'pre_lesson', 'Pre-Lesson', 'textarea', 4); ?></div>
                            <div class="col-12"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'lesson_flow', 'Flow', 'textarea', 6); ?></div>
                            <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'learning_resources', 'Learning Resources', 'textarea', 4); ?></div>
                            <div class="col-md-6"><?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'integration', 'Opportunities for Integration', 'textarea', 4); ?></div>
                        </div>
                    </div>

                    <div class="ps-form-section">
                        <h6>Assessment</h6>
                        <?php module_ilaw_form_field($lesson_plan, $lesson_plan_defaults, 'formative_assessment', 'Formative Assessment', 'textarea', 5); ?>
                    </div>

                    <div class="ps-form-section">
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

                    <div class="ps-form-actions">
                        <a href="<?= htmlspecialchars($back_url, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><?= $lesson_plan ? 'Update Session Plan' : 'Create Session Plan' ?></button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
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

.ps-content {
    padding: 1.25rem;
}

.ps-form-section {
    padding: 1.25rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 1rem;
    background: #fafbff;
}

.ps-form-section h6 {
    margin: 0 0 1rem;
    color: #1e293b;
    font-weight: 800;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.ps-form-field {
    margin-bottom: 0;
}

.ps-form-field textarea {
    resize: vertical;
}

.ps-auto-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.ps-auto-meta div {
    padding: 0.85rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
}

.ps-auto-meta span {
    display: block;
    margin-bottom: 0.35rem;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.ps-auto-meta strong {
    display: block;
    min-height: 1.25rem;
    color: #0f172a;
    font-size: 0.9rem;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.ps-session-status {
    display: inline-flex;
    align-items: center;
    min-height: 38px;
    padding: 0.4rem 0.8rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.ps-session-status.done {
    background: #dcfce7;
    color: #166534;
}

.ps-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

@media (max-width: 992px) {
    .ps-auto-meta {
        grid-template-columns: 1fr;
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
    .ps-form-section {
        padding: 1rem;
    }
    .ps-form-actions {
        flex-direction: column;
    }
    .ps-form-actions .btn {
        width: 100%;
    }
}
</style>
