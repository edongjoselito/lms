<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$sy_label = isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A';
$grade_groups = array();
$total_sections = 0;
$sections_with_adviser = 0;

if (!empty($grade_levels)) {
    foreach ($grade_levels as $gl) {
        $gl_sections = array();

        if (!empty($sections)) {
            foreach ($sections as $sec) {
                if (isset($sec->program_id) && (int) $sec->program_id === (int) $gl->id) {
                    $gl_sections[] = $sec;
                    $total_sections++;

                    if (!empty($sec->adviser_name)) {
                        $sections_with_adviser++;
                    }
                }
            }
        }

        $grade_name = isset($gl->name) && trim((string) $gl->name) !== ''
            ? trim((string) $gl->name)
            : (isset($gl->year_level) ? 'Grade ' . str_pad((int) $gl->year_level, 2, '0', STR_PAD_LEFT) : '-');

        $grade_code = isset($gl->code) && trim((string) $gl->code) !== ''
            ? trim((string) $gl->code)
            : (isset($gl->year_level) ? 'G' . str_pad((int) $gl->year_level, 2, '0', STR_PAD_LEFT) : 'GL');

        $grade_groups[] = (object) array(
            'id' => $gl->id,
            'name' => $grade_name,
            'code' => $grade_code,
            'year_level' => isset($gl->year_level) ? $gl->year_level : '',
            'sections' => $gl_sections,
        );
    }
}

$grade_level_count = count($grade_groups);
?>

<div class="ps-page">
    <a href="<?= site_url('academic/programs') ?>" class="ps-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Programs
    </a>

    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">SC</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Academic</span>
                        <span class="ps-tag ps-tag-code">S.Y. <?= htmlspecialchars($sy_label) ?></span>
                    </div>
                    <h1 class="ps-hero-title">Sections</h1>
                    <p class="ps-hero-desc">Manage sections by grade level and keep adviser assignments organized for the active school year.</p>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $grade_level_count ?></div>
                    <div class="ps-hero-stat-lbl">Grade Levels</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $total_sections ?></div>
                    <div class="ps-hero-stat-lbl">Sections</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $sections_with_adviser ?></div>
                    <div class="ps-hero-stat-lbl">With Adviser</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-people-fill"></i>
                    <span>Sections by Grade Level</span>
                    <span class="ps-count-pill"><?= (int) $total_sections ?></span>
                </div>
                <div class="ps-card-tools">
                    <?php if (!empty($grade_groups)): ?>
                    <div class="ps-search-wrap">
                        <i class="bi bi-search ps-search-icon"></i>
                        <input type="text" class="ps-search" id="sectionSearch" placeholder="Search sections or advisers...">
                    </div>
                    <?php endif; ?>
                    <a href="<?= site_url('academic/create_section') ?>" class="ps-submit-btn ps-submit-btn-inline">
                        <i class="bi bi-plus-lg"></i> Add Section
                    </a>
                </div>
            </div>

            <?php if (!empty($grade_groups)): ?>
                <div class="ps-grade-list" id="gradeList">
                    <?php foreach ($grade_groups as $group_index => $group): ?>
                        <div class="ps-grade-card" data-grade-card>
                            <div class="ps-grade-head">
                                <div class="ps-grade-head-left">
                                    <div class="ps-grade-code"><?= htmlspecialchars($group->code) ?></div>
                                    <div class="ps-grade-copy">
                                        <h3 class="ps-grade-title"><?= htmlspecialchars($group->name) ?></h3>
                                        <div class="ps-grade-sub">
                                            <?= count($group->sections) ?> Section<?= count($group->sections) != 1 ? 's' : '' ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= site_url('academic/create_section_for_grade/' . $group->id) ?>" class="ps-action-btn ps-action-view">
                                    <i class="bi bi-plus-lg"></i> Add Section
                                </a>
                            </div>

                            <?php if (!empty($group->sections)): ?>
                                <div class="ps-table-head ps-section-table-head">
                                    <div class="ps-th ps-th-num">#</div>
                                    <div class="ps-th ps-th-subject">Section</div>
                                    <div class="ps-th ps-th-teachers">Adviser</div>
                                    <div class="ps-th ps-th-actions">Actions</div>
                                </div>
                                <div class="ps-subject-list">
                                    <?php foreach ($group->sections as $section_index => $sec): ?>
                                        <?php
                                        $adviser_name = !empty($sec->adviser_name) ? trim((string) $sec->adviser_name) : '';
                                        $search_text = strtolower(trim($group->name . ' ' . $sec->name . ' ' . $adviser_name));
                                        ?>
                                        <div class="ps-subject-item ps-section-item" data-search="<?= htmlspecialchars($search_text, ENT_QUOTES, 'UTF-8') ?>">
                                            <div class="ps-col-num">
                                                <span class="ps-subject-index"><?= $section_index + 1 ?></span>
                                            </div>

                                            <div class="ps-col-subject">
                                                <div class="ps-subject-top">
                                                    <span class="ps-subject-code">SEC</span>
                                                    <span class="ps-subject-name"><?= htmlspecialchars($sec->name) ?></span>
                                                </div>
                                            </div>

                                            <div class="ps-col-teachers">
                                                <?php if ($adviser_name !== ''): ?>
                                                    <span class="ps-adviser-badge">
                                                        <span class="ps-adviser-avatar"><?= htmlspecialchars(strtoupper(substr($adviser_name, 0, 1))) ?></span>
                                                        <?= htmlspecialchars($adviser_name) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="ps-teacher-empty">— No adviser assigned</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="ps-col-actions">
                                                <a href="<?= site_url('academic/edit_section/' . $sec->id) ?>" class="ps-action-btn ps-action-edit" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                                <a href="<?= site_url('academic/delete_section/' . $sec->id) ?>" class="ps-action-btn ps-action-del" title="Delete" onclick="return confirm('Delete this section?');">
                                                    <i class="bi bi-trash3-fill"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="ps-empty-inline">
                                    <p>No sections created for this grade level yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ps-no-results" id="noResults" style="display:none;">
                    <i class="bi bi-search" style="font-size:1.8rem;opacity:0.3;"></i>
                    <p>No sections match your search.</p>
                </div>
            <?php else: ?>
                <div class="ps-empty">
                    <div class="ps-empty-icon">
                        <i class="bi bi-layers"></i>
                    </div>
                    <div class="ps-empty-title">No grade levels found</div>
                    <div class="ps-empty-sub">Create grade levels first to manage sections.</div>
                    <a href="<?= site_url('academic/programs') ?>" class="ps-submit-btn ps-empty-btn">
                        <i class="bi bi-plus-lg"></i> Add Grade Level
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('sectionSearch') && document.getElementById('sectionSearch').addEventListener('input', function () {
    var q = this.value.toLowerCase().trim();
    var cards = document.querySelectorAll('[data-grade-card]');
    var visibleCards = 0;

    cards.forEach(function (card) {
        var rows = card.querySelectorAll('.ps-section-item');
        var head = card.querySelector('.ps-section-table-head');
        var visibleRows = 0;

        if (rows.length === 0) {
            card.style.display = q ? 'none' : '';
            if (!q) visibleCards++;
            return;
        }

        rows.forEach(function (row) {
            var match = !q || row.dataset.search.indexOf(q) !== -1;
            row.style.display = match ? '' : 'none';
            if (match) visibleRows++;
        });

        if (head) {
            head.style.display = visibleRows > 0 ? 'grid' : 'none';
        }

        card.style.display = visibleRows > 0 ? '' : 'none';
        if (visibleRows > 0) {
            visibleCards++;
        }
    });

    document.getElementById('noResults').style.display = visibleCards === 0 ? 'flex' : 'none';
});
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
    width: 250px;
    font-family: inherit;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.ps-search:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
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

.ps-section-table-head {
    display: grid;
    grid-template-columns: 44px 1fr 1fr auto;
    padding: 0.55rem 1.25rem;
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
    min-width: 110px;
}

.ps-subject-list {
    position: relative;
    overflow: visible;
    padding: 0;
}

.ps-subject-item {
    position: relative;
    display: grid;
    grid-template-columns: 44px 1fr 1fr auto;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.14s;
    animation: ps-fadein 0.22s ease forwards;
}

@keyframes ps-fadein {
    from { opacity: 0; transform: translateY(3px); }
    to { opacity: 1; transform: translateY(0); }
}

.ps-subject-item:last-child {
    border-bottom: none;
}

.ps-subject-item:hover {
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
    padding-right: 1.5rem;
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
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.ps-col-teachers {
    min-width: 0;
    padding-right: 1rem;
}

.ps-adviser-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #1d4ed8;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 20px;
    padding: 3px 10px 3px 4px;
}

.ps-adviser-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ps-teacher-empty {
    font-size: 0.8rem;
    color: #cbd5e1;
    font-style: italic;
}

.ps-col-actions {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    justify-content: flex-end;
    min-width: 110px;
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

.ps-empty-inline {
    padding: 1.5rem 1.25rem;
    text-align: center;
    color: #94a3b8;
    font-size: 0.85rem;
}

.ps-empty-inline p {
    margin: 0;
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
    max-width: 300px;
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
    .ps-section-table-head {
        display: none;
    }

    .ps-subject-item {
        grid-template-columns: 36px 1fr auto;
        grid-template-rows: auto auto;
    }

    .ps-col-teachers {
        grid-column: 2;
        grid-row: 2;
        padding-top: 0.4rem;
    }

    .ps-col-actions {
        grid-row: 1 / 3;
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

    .ps-grade-list {
        padding: 1rem;
    }

    .ps-grade-head {
        padding: 1rem;
    }

    .ps-subject-item {
        padding: 1rem;
    }
}

@media (max-width: 520px) {
    .ps-grade-head {
        align-items: stretch;
    }

    .ps-grade-head-left {
        align-items: flex-start;
    }

    .ps-subject-item {
        grid-template-columns: 1fr;
        grid-template-rows: none;
        gap: 0.75rem;
    }

    .ps-col-num {
        display: none;
    }

    .ps-col-subject,
    .ps-col-teachers,
    .ps-col-actions {
        grid-column: auto;
        grid-row: auto;
        padding-right: 0;
        padding-top: 0;
    }

    .ps-col-actions {
        justify-content: flex-start;
        min-width: 0;
    }
}
</style>
