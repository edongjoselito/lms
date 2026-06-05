<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$lc_program_label = '';
if (isset($program) && $program) {
    $lc_program_label = isset($program->name) && $program->name !== ''
        ? $program->name
        : (isset($program->year_level) ? 'Grade ' . str_pad($program->year_level, 2, '0', STR_PAD_LEFT) : 'Program');
} elseif (isset($subject->program_name) && trim((string) $subject->program_name) !== '') {
    $lc_program_label = $subject->program_name;
} elseif (isset($subject->program_year_level) && trim((string) $subject->program_year_level) !== '') {
    $lc_program_label = 'Grade ' . str_pad((int) $subject->program_year_level, 2, '0', STR_PAD_LEFT);
}

$lc_back_url = isset($back_url) && $back_url ? $back_url : site_url('course/content/' . (int) $subject->id);
$lc_back_label = isset($back_label) && $back_label ? $back_label : 'Back to Course';
$lc_can_create = !empty($can_create_learning_competency);
$lc_create_url = isset($create_url) && $create_url ? $create_url : site_url('course/create_learning_competency/' . (int) $subject->id);
$lc_update_base_url = isset($update_base_url) && $update_base_url ? $update_base_url : site_url('course/update_learning_competency/' . (int) $subject->id);
$lc_delete_base_url = isset($delete_base_url) && $delete_base_url ? $delete_base_url : site_url('course/delete_learning_competency/' . (int) $subject->id);
$lc_route_query_suffix = isset($route_query_suffix) ? (string) $route_query_suffix : '';
$lc_completed_count = isset($completed_competency_count) ? (int) $completed_competency_count : 0;
$lc_tracked_count = isset($tracked_competency_count) ? (int) $tracked_competency_count : 0;
$lc_completion_percent = isset($competency_completion_percent) ? (int) $competency_completion_percent : 0;
?>

<div class="lc-page">

    <!-- Breadcrumb -->
    <div class="lc-top-actions">
        <a href="<?= $lc_back_url ?>" class="lc-back">
            <i class="bi bi-arrow-left-short"></i> <?= htmlspecialchars($lc_back_label) ?>
        </a>
    </div>

    <!-- Hero header -->
    <div class="lc-hero">
        <div class="lc-hero-bg"></div>
        <div class="lc-hero-content">
            <div class="lc-hero-left">
                <div class="lc-hero-avatar"><?= strtoupper(substr($subject->code, 0, 2)) ?></div>
                <div class="lc-hero-info">
                    <div class="lc-hero-meta">
                        <span class="lc-tag lc-tag-code"><?= htmlspecialchars($subject->code) ?></span>
                        <?php if ($lc_program_label !== ''): ?>
                            <span class="lc-tag lc-tag-program"><?= htmlspecialchars($lc_program_label) ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="lc-hero-title"><?= htmlspecialchars($subject->description) ?></h1>
                    <p class="lc-hero-desc">Learning Competencies</p>
                </div>
            </div>
            <div class="lc-hero-stats">
                <div class="lc-hero-stat">
                    <div class="lc-hero-stat-num"><?= count($competencies) ?></div>
                    <div class="lc-hero-stat-lbl">Competencies</div>
                </div>
                <div class="lc-hero-stat">
                    <div class="lc-hero-stat-num"><?= $lc_completed_count ?></div>
                    <div class="lc-hero-stat-lbl">Completed</div>
                </div>
                <div class="lc-hero-stat">
                    <div class="lc-hero-stat-num"><?= $lc_completion_percent ?>%</div>
                    <div class="lc-hero-stat-lbl">Checklist</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="lc-layout">
        <div class="lc-card">
            <div class="lc-card-head">
                <div class="lc-card-title">
                    <i class="bi bi-list-check"></i>
                    <span>Learning Competencies</span>
                    <span class="lc-count-pill"><?= count($competencies) ?></span>
                </div>
                <div class="lc-head-actions">
                    <span class="lc-helper-note">
                        Checklist updates automatically when your linked lessons are marked as taught.
                        <?php if (!$lc_can_create): ?>
                            Read only. Only the author can add, edit, or delete entries.
                        <?php endif; ?>
                    </span>
                    <?php if ($lc_can_create): ?>
                        <button type="button" class="lc-add-btn" data-bs-toggle="modal" data-bs-target="#lcModal">
                            <i class="bi bi-plus-lg"></i> Add Competency
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($competencies)): ?>
                <div class="lc-summary-strip">
                    <div class="lc-summary-copy">
                        <strong><?= $lc_completed_count ?></strong> of <strong><?= count($competencies) ?></strong> competencies are fully covered for you.
                        <?php if ($lc_tracked_count > 0): ?>
                            <?= $lc_tracked_count ?> competencies currently have linked lessons for tracking.
                        <?php else: ?>
                            Link competencies to lessons to start tracking completion.
                        <?php endif; ?>
                    </div>
                </div>
                <div class="lc-list">
                    <?php foreach ($competencies as $lc): ?>
                        <div class="lc-item">
                            <div class="lc-item-main">
                                <span class="lc-check lc-check--<?= htmlspecialchars($lc->checklist_state ?? 'pending', ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi <?= htmlspecialchars($lc->checklist_icon ?? '', ENT_QUOTES, 'UTF-8') ?>"></i>
                                </span>
                                <?php if ($lc->code): ?>
                                    <span class="lc-code"><?= htmlspecialchars($lc->code) ?></span>
                                <?php endif; ?>
                                <div class="lc-copy">
                                    <div class="lc-row-top">
                                        <span class="lc-desc"><?= htmlspecialchars($lc->description) ?></span>
                                        <span class="lc-status lc-status--<?= htmlspecialchars($lc->checklist_state ?? 'pending', ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($lc->checklist_label ?? 'Not Started') ?>
                                        </span>
                                    </div>
                                    <span class="lc-owner">
                                        Added by
                                        <?= !empty(trim((string) ($lc->creator_name ?? ''))) ? htmlspecialchars(trim((string) $lc->creator_name)) : 'Unknown User' ?>
                                        <?= !empty($lc->can_manage) ? '• You can manage this entry' : '' ?>
                                    </span>
                                    <div class="lc-progress-meta">
                                        <span><?= htmlspecialchars($lc->checklist_detail ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php if ((int) ($lc->total_lessons ?? 0) > 0): ?>
                                            <span><?= (int) ($lc->taught_lessons ?? 0) ?> / <?= (int) ($lc->total_lessons ?? 0) ?> lessons taught by you</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="lc-progress-bar">
                                        <span style="width: <?= (int) ($lc->completion_percent ?? 0) ?>%;"></span>
                                    </div>
                                    <?php if (!empty($lc->latest_taught_at_label)): ?>
                                        <span class="lc-progress-date"><i class="bi bi-calendar-event"></i> Last taught by you <?= htmlspecialchars($lc->latest_taught_at_label) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($lc->can_manage)): ?>
                                <div class="lc-item-actions">
                                    <button type="button" class="lc-action-btn lc-action-edit" onclick="editLC(<?= (int) $lc->id ?>, '<?= htmlspecialchars($lc->code ?? '', ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($lc->description, ENT_QUOTES, 'UTF-8') ?>', <?= (int) $lc->sort_order ?>)">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <a href="<?= $lc_delete_base_url . '/' . (int) $lc->id . $lc_route_query_suffix ?>" class="lc-action-btn lc-action-del" onclick="return confirm('Delete this learning competency?');">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="lc-empty">
                    <div class="lc-empty-icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div class="lc-empty-title">No learning competencies yet</div>
                    <div class="lc-empty-sub"><?= $lc_can_create ? 'Click "Add Competency" to create the first learning competency for this subject.' : 'No entries have been added for this subject yet.' ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<?php if ($lc_can_create): ?>
    <div class="modal fade" id="lcModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lcModalTitle">Add Learning Competency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open($lc_create_url, ['id' => 'lcForm']) ?>
                    <div class="modal-body">
                        <input type="hidden" name="lc_id" id="lc_id" value="">
                        <div class="mb-3">
                            <label class="form-label">Code (Optional)</label>
                            <input type="text" class="form-control" name="code" id="lc_code" placeholder="e.g., LC1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="lc_description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" id="lc_sort_order" value="0" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="lcSubmitBtn">Add Competency</button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
<?php if ($lc_can_create): ?>
function editLC(id, code, description, sortOrder) {
    document.getElementById('lc_id').value = id;
    document.getElementById('lc_code').value = code;
    document.getElementById('lc_description').value = description;
    document.getElementById('lc_sort_order').value = sortOrder || 0;
    document.getElementById('lcModalTitle').textContent = 'Edit Learning Competency';
    document.getElementById('lcSubmitBtn').textContent = 'Update Competency';
    document.getElementById('lcForm').action = '<?= $lc_update_base_url ?>/' + id + '<?= $lc_route_query_suffix ?>';
    
    var modal = new bootstrap.Modal(document.getElementById('lcModal'));
    modal.show();
}

document.getElementById('lcModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('lc_id').value = '';
    document.getElementById('lc_code').value = '';
    document.getElementById('lc_description').value = '';
    document.getElementById('lc_sort_order').value = '0';
    document.getElementById('lcModalTitle').textContent = 'Add Learning Competency';
    document.getElementById('lcSubmitBtn').textContent = 'Add Competency';
    document.getElementById('lcForm').action = '<?= $lc_create_url ?>';
});
<?php endif; ?>
</script>

<style>
.lc-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

.lc-top-actions {
    margin-bottom: 1.5rem;
}

.lc-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}
.lc-back:hover { background: #dbeafe; color: #1d4ed8; text-decoration: none; }

.lc-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}
.lc-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}
.lc-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.lc-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}
.lc-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}
.lc-hero-avatar {
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
.lc-hero-info { min-width: 0; }
.lc-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}
.lc-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}
.lc-tag-code { background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); }
.lc-tag-program { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.25); }
.lc-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}
.lc-hero-desc {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.72);
    margin: 0;
}
.lc-hero-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
}
.lc-hero-stat {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 100px;
}
.lc-hero-stat-num {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}
.lc-hero-stat-lbl {
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255,255,255,0.75);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-top: 0.3rem;
}

.lc-layout { display: grid; gap: 1.5rem; }
.lc-card {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}
.lc-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    flex-wrap: wrap;
}
.lc-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}
.lc-card-title i { color: #059669; font-size: 1rem; }
.lc-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #d1fae5;
    color: #059669;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.6rem;
    letter-spacing: 0.02em;
}
.lc-helper-note {
    font-size: 0.82rem;
    color: #64748b;
    font-weight: 500;
}
.lc-head-actions {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}
.lc-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
}
.lc-add-btn:hover {
    background: linear-gradient(135deg, #047857 0%, #059669 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

.lc-list { padding: 0; }
.lc-summary-strip {
    padding: 0.95rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
}
.lc-summary-copy {
    font-size: 0.84rem;
    color: #334155;
    line-height: 1.5;
}
.lc-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.14s;
}
.lc-item:last-child { border-bottom: none; }
.lc-item:hover { background: #f8f9ff; }
.lc-item-main {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    flex: 1;
    min-width: 0;
}
.lc-check {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background: #fff;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 0.1rem;
    font-size: 1rem;
}
.lc-check--complete {
    background: #dcfce7;
    border-color: #86efac;
    color: #15803d;
}
.lc-check--in_progress {
    background: #fef3c7;
    border-color: #fde68a;
    color: #b45309;
}
.lc-check--pending {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #94a3b8;
}
.lc-check--unlinked {
    background: #ede9fe;
    border-color: #c4b5fd;
    color: #7c3aed;
}
.lc-copy {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    min-width: 0;
    flex: 1;
}
.lc-row-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.lc-code {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.65rem;
    background: #d1fae5;
    color: #059669;
    border-radius: 7px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    flex-shrink: 0;
}
.lc-desc {
    font-size: 0.9rem;
    font-weight: 500;
    color: #1e293b;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.lc-owner {
    font-size: 0.76rem;
    color: #64748b;
    white-space: normal;
}
.lc-status {
    display: inline-flex;
    align-items: center;
    padding: 0.28rem 0.7rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    white-space: nowrap;
}
.lc-status--complete {
    background: #dcfce7;
    color: #166534;
}
.lc-status--in_progress {
    background: #fef3c7;
    color: #92400e;
}
.lc-status--pending {
    background: #e2e8f0;
    color: #475569;
}
.lc-status--unlinked {
    background: #ede9fe;
    color: #6d28d9;
}
.lc-progress-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
    font-size: 0.77rem;
    color: #64748b;
}
.lc-progress-bar {
    width: 100%;
    height: 8px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}
.lc-progress-bar span {
    display: block;
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}
.lc-progress-date {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.75rem;
    color: #475569;
}
.lc-item-actions {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    flex-shrink: 0;
}
.lc-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.14s ease;
    text-decoration: none;
}
.lc-action-edit {
    background: #fef9c3;
    color: #a16207;
}
.lc-action-edit:hover {
    background: #fef08a;
    color: #854d0e;
    transform: translateY(-1px);
}
.lc-action-del {
    background: #fee2e2;
    color: #dc2626;
}
.lc-action-del:hover {
    background: #fecaca;
    color: #b91c1c;
    transform: translateY(-1px);
}

.lc-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4rem 2rem;
    text-align: center;
}
.lc-empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #059669;
    font-size: 1.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}
.lc-empty-title { font-size: 1rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; }
.lc-empty-sub { font-size: 0.85rem; color: #94a3b8; max-width: 320px; line-height: 1.5; }

@media (max-width: 768px) {
    .lc-hero-content { padding: 1.5rem; }
    .lc-hero-title { font-size: 1.25rem; }
    .lc-head-actions { justify-content: flex-start; }
    .lc-item { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    .lc-item-main { width: 100%; flex-wrap: wrap; }
    .lc-copy { width: 100%; }
    .lc-desc { width: 100%; white-space: normal; }
    .lc-progress-meta { flex-direction: column; align-items: flex-start; }
    .lc-item-actions { width: 100%; justify-content: flex-end; }
}
</style>
