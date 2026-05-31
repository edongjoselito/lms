<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$CI =& get_instance();

$program_items = array();
$total_entries = 0;
$total_grade_levels = 0;
$total_subjects = 0;

if (!empty($programs)) {
    foreach ($programs as $p) {
        $type = isset($p->type) ? trim((string) $p->type) : 'program';
        $year_level = isset($p->year_level) ? trim((string) $p->year_level) : '';
        $display_code = isset($p->code) && trim((string) $p->code) !== ''
            ? trim((string) $p->code)
            : ($year_level !== '' ? 'G' . str_pad((int) $year_level, 2, '0', STR_PAD_LEFT) : '-');
        $display_name = isset($p->name) && trim((string) $p->name) !== ''
            ? trim((string) $p->name)
            : ($year_level !== '' ? 'Grade ' . str_pad((int) $year_level, 2, '0', STR_PAD_LEFT) : 'Program');
        $description = isset($p->description) ? trim((string) $p->description) : '';
        $category = isset($p->category) ? trim((string) $p->category) : '';
        $level_order = isset($p->level_order) ? trim((string) $p->level_order) : '';
        $subject_count = $type === 'program' ? count($CI->Academic_model->get_subjects_by_program($p->id)) : 0;
        $is_grade_level = $year_level !== '' || $type === 'grade_level';
        $detail_search = '';

        $total_entries++;
        if ($is_grade_level) {
            $total_grade_levels++;
        }
        $total_subjects += (int) $subject_count;

        if ($type === 'program') {
            $detail_search = $subject_count . ' subjects';
        } else {
            if ($category !== '') {
                $detail_search .= ' ' . ucfirst($category);
            }
            if ($level_order !== '') {
                $detail_search .= ' Level ' . $level_order;
            }
            if ($detail_search === '' && $is_grade_level) {
                $detail_search = 'Grade Level';
            }
        }

        $program_items[] = (object) array(
            'id' => $p->id,
            'type' => $type,
            'display_code' => $display_code,
            'display_name' => $display_name,
            'description' => $description,
            'category' => $category,
            'level_order' => $level_order,
            'year_level' => $year_level,
            'subject_count' => $subject_count,
            'is_grade_level' => $is_grade_level,
            'search_text' => strtolower(trim($display_code . ' ' . $display_name . ' ' . $description . ' ' . $detail_search)),
        );
    }
}
?>

<div class="ps-page">
    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">AP</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Academic</span>
                        <span class="ps-tag ps-tag-code">Program Directory</span>
                    </div>
                    <h1 class="ps-hero-title">Academic Programs</h1>
                    <p class="ps-hero-desc">Manage grade levels, programs, and their subject assignments in one place.</p>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $total_entries ?></div>
                    <div class="ps-hero-stat-lbl">Entries</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $total_grade_levels ?></div>
                    <div class="ps-hero-stat-lbl">Grade Levels</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $total_subjects ?></div>
                    <div class="ps-hero-stat-lbl">Subjects</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Programs &amp; Grade Levels</span>
                    <span class="ps-count-pill"><?= count($program_items) ?></span>
                </div>
                <div class="ps-card-tools">
                    <?php if (!empty($program_items)): ?>
                    <div class="ps-search-wrap">
                        <i class="bi bi-search ps-search-icon"></i>
                        <input type="text" class="ps-search" id="programSearch" placeholder="Search programs...">
                    </div>
                    <?php endif; ?>
                    <a href="<?= site_url('academic/create_program') ?>" class="ps-submit-btn ps-submit-btn-inline">
                        <i class="bi bi-plus-lg"></i> Add Program
                    </a>
                </div>
            </div>

            <?php if (!empty($program_items)): ?>
                <div class="ps-table-head ps-program-table-head">
                    <div class="ps-th ps-th-num">#</div>
                    <div class="ps-th ps-th-subject">Program</div>
                    <div class="ps-th ps-th-details">Details</div>
                    <div class="ps-th ps-th-actions">Actions</div>
                </div>
                <div class="ps-subject-list" id="programList">
                    <?php foreach ($program_items as $i => $item): ?>
                        <div class="ps-subject-item ps-program-item" data-search="<?= htmlspecialchars($item->search_text, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="ps-col-num">
                                <span class="ps-subject-index"><?= $i + 1 ?></span>
                            </div>

                            <div class="ps-col-subject">
                                <div class="ps-subject-top">
                                    <span class="ps-subject-code"><?= htmlspecialchars($item->display_code) ?></span>
                                    <span class="ps-subject-name"><?= htmlspecialchars($item->display_name) ?></span>
                                    <?php if ($item->is_grade_level): ?>
                                        <span class="ps-inline-tag">Grade Level</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($item->description !== ''): ?>
                                    <div class="ps-program-desc"><?= htmlspecialchars($item->description) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="ps-col-details">
                                <?php if ($item->type === 'program'): ?>
                                    <span class="ps-detail-badge">
                                        <i class="bi bi-journal-bookmark-fill"></i>
                                        <?= (int) $item->subject_count ?> <?= $item->subject_count == 1 ? 'subject' : 'subjects' ?>
                                    </span>
                                <?php else: ?>
                                    <?php if ($item->category !== ''): ?>
                                        <span class="ps-detail-badge">
                                            <i class="bi bi-layers-fill"></i>
                                            <?= htmlspecialchars(ucfirst($item->category)) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($item->level_order !== ''): ?>
                                        <span class="ps-detail-badge">
                                            <i class="bi bi-sort-numeric-down"></i>
                                            Level <?= htmlspecialchars($item->level_order) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($item->category === '' && $item->level_order === '' && $item->year_level !== ''): ?>
                                        <span class="ps-detail-badge">
                                            <i class="bi bi-mortarboard-fill"></i>
                                            Grade <?= str_pad((int) $item->year_level, 2, '0', STR_PAD_LEFT) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div class="ps-col-actions">
                                <?php if ($item->type === 'program'): ?>
                                    <a href="<?= site_url('academic/program_subjects/' . $item->id) ?>" class="ps-action-btn ps-action-view">
                                        <i class="bi bi-book-half"></i> Subjects
                                    </a>
                                <?php else: ?>
                                    <a href="<?= site_url('academic/create_section_for_grade/' . $item->id) ?>" class="ps-action-btn ps-action-view">
                                        <i class="bi bi-people-fill"></i> Add Section
                                    </a>
                                <?php endif; ?>
                                <a href="<?= site_url('academic/edit_program/' . $item->id) ?>" class="ps-action-btn ps-action-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="<?= site_url('academic/delete_program/' . $item->id) ?>" class="ps-action-btn ps-action-del" title="Delete" onclick="return confirm('Delete this program?');">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ps-no-results" id="noResults" style="display:none;">
                    <i class="bi bi-search" style="font-size:1.8rem;opacity:0.3;"></i>
                    <p>No programs match your search.</p>
                </div>
            <?php else: ?>
                <div class="ps-empty">
                    <div class="ps-empty-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div class="ps-empty-title">No programs yet</div>
                    <div class="ps-empty-sub">Create your first academic program or grade level to get started.</div>
                    <a href="<?= site_url('academic/create_program') ?>" class="ps-submit-btn ps-empty-btn">
                        <i class="bi bi-plus-lg"></i> Add Program
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('programSearch') && document.getElementById('programSearch').addEventListener('input', function () {
    var q = this.value.toLowerCase().trim();
    var items = document.querySelectorAll('.ps-program-item');
    var visible = 0;

    items.forEach(function (item) {
        var match = !q || item.dataset.search.indexOf(q) !== -1;
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('noResults').style.display = visible === 0 ? 'flex' : 'none';
});
</script>

<style>
.ps-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
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

.ps-hero-desc {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.72);
    margin: 0;
    line-height: 1.5;
    max-width: 560px;
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

.ps-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.ps-search-icon {
    position: absolute;
    left: 0.7rem;
    color: #94a3b8;
    font-size: 0.8rem;
    pointer-events: none;
}

.ps-search {
    padding: 0.45rem 0.75rem 0.45rem 2rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.83rem;
    color: #334155;
    background: #fff;
    outline: none;
    width: 220px;
    font-family: inherit;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.ps-search:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}

.ps-program-table-head {
    display: grid;
    grid-template-columns: 44px minmax(0, 1.2fr) minmax(220px, 0.9fr) auto;
    padding: 0.55rem 1.5rem;
    background: #f8faff;
    border-bottom: 1px solid #eaecf0;
}

.ps-th {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.ps-th-actions {
    text-align: right;
    min-width: 170px;
}

.ps-subject-list {
    position: relative;
    overflow: visible;
    padding: 0;
}

.ps-program-item {
    position: relative;
    display: grid;
    grid-template-columns: 44px minmax(0, 1.2fr) minmax(220px, 0.9fr) auto;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.14s;
    animation: ps-fadein 0.22s ease forwards;
}

@keyframes ps-fadein {
    from { opacity: 0; transform: translateY(3px); }
    to { opacity: 1; transform: translateY(0); }
}

.ps-program-item:last-child {
    border-bottom: none;
}

.ps-program-item:hover {
    background: #f8f9ff;
}

.ps-col-num {
    display: flex;
    align-items: center;
}

.ps-subject-index {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ps-col-subject {
    min-width: 0;
    padding-right: 1.25rem;
}

.ps-subject-top {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-wrap: wrap;
}

.ps-subject-code {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1d4ed8;
    border-radius: 7px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    white-space: nowrap;
    flex-shrink: 0;
}

.ps-subject-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
}

.ps-inline-tag {
    display: inline-flex;
    align-items: center;
    padding: 0.18rem 0.55rem;
    border-radius: 999px;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.ps-program-desc {
    margin-top: 0.35rem;
    font-size: 0.83rem;
    color: #64748b;
    line-height: 1.45;
}

.ps-col-details {
    min-width: 0;
    padding-right: 1rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
}

.ps-detail-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 600;
}

.ps-detail-badge i {
    color: #2563eb;
}

.ps-col-actions {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    justify-content: flex-end;
    min-width: 170px;
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

.ps-no-results {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 3rem 2rem;
    color: #94a3b8;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
}

.ps-no-results p {
    margin: 0;
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
    max-width: 280px;
    line-height: 1.5;
}

.ps-submit-btn {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(59,130,246,0.35);
    font-family: inherit;
    margin-top: 0.25rem;
    letter-spacing: 0.01em;
    text-decoration: none;
}

.ps-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(59,130,246,0.45);
    color: #fff;
    text-decoration: none;
}

.ps-submit-btn-inline {
    width: auto;
    margin-top: 0;
    padding: 0.65rem 1rem;
    border-radius: 10px;
}

.ps-empty-btn {
    width: auto;
    margin-top: 1.25rem;
    padding: 0.75rem 1rem;
}

@media (max-width: 860px) {
    .ps-program-table-head {
        display: none;
    }

    .ps-program-item {
        grid-template-columns: 36px 1fr auto;
        grid-template-rows: auto auto;
    }

    .ps-col-details {
        grid-column: 2;
        grid-row: 2;
        padding-top: 0.55rem;
    }

    .ps-col-actions {
        grid-row: 1 / 3;
        align-self: start;
    }
}

@media (max-width: 768px) {
    .ps-hero-content {
        padding: 1.5rem;
    }

    .ps-hero-left {
        align-items: flex-start;
    }

    .ps-hero-title {
        font-size: 1.3rem;
    }

    .ps-card-head {
        padding: 1rem;
    }

    .ps-card-tools {
        width: 100%;
        justify-content: stretch;
    }

    .ps-search-wrap,
    .ps-search,
    .ps-submit-btn-inline {
        width: 100%;
    }

    .ps-program-item {
        padding: 1rem;
    }

    .ps-col-actions {
        min-width: 0;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
}

@media (max-width: 520px) {
    .ps-program-item {
        grid-template-columns: 1fr;
        grid-template-rows: none;
        gap: 0.85rem;
    }

    .ps-col-num {
        display: none;
    }

    .ps-col-subject,
    .ps-col-details,
    .ps-col-actions {
        grid-column: auto;
        grid-row: auto;
        padding-right: 0;
    }

    .ps-col-actions {
        justify-content: flex-start;
    }

    .ps-action-btn {
        flex: 0 0 auto;
    }
}
</style>
