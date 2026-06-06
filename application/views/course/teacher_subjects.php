<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$subject_count = count($subjects);
$grade_level_set = array();

if (!function_exists('teacher_grade_level_label')) {
    function teacher_grade_level_label($subject)
    {
        $value = '';
        if (isset($subject->program_year_level) && trim((string) $subject->program_year_level) !== '') {
            $value = trim((string) $subject->program_year_level);
        } elseif (isset($subject->year_level) && trim((string) $subject->year_level) !== '') {
            $value = trim((string) $subject->year_level);
        }

        if ($value === '') {
            return 'General';
        }

        return is_numeric($value) ? 'Grade ' . str_pad((int) $value, 2, '0', STR_PAD_LEFT) : $value;
    }
}

foreach ($subjects as $subject_item) {
    $grade_level_set[teacher_grade_level_label($subject_item)] = true;
}
$grade_level_count = count($grade_level_set);
?>

<div class="ps-page">

    <a href="<?= site_url('dashboard') ?>" class="ps-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Dashboard
    </a>

    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">TS</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Teacher</span>
                        <span class="ps-tag ps-tag-code">Assigned Subjects</span>
                    </div>
                    <h1 class="ps-hero-title">My Subjects</h1>
                    <p class="ps-hero-desc">Subjects assigned to you for content and section management.</p>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= $subject_count ?></div>
                    <div class="ps-hero-stat-lbl">Total Subjects</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= $grade_level_count ?></div>
                    <div class="ps-hero-stat-lbl">Grade Levels</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Subjects</span>
                    <span class="ps-count-pill"><?= $subject_count ?></span>
                </div>
                <?php if (!empty($subjects)): ?>
                <div class="ps-search-wrap">
                    <i class="bi bi-search ps-search-icon"></i>
                    <input type="text" class="ps-search" id="subjectSearch" placeholder="Search subjects...">
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($subjects)): ?>
                <div class="ps-table-head ps-table-head-teacher">
                    <div class="ps-th ps-th-num">#</div>
                    <div class="ps-th ps-th-subject">Subject</div>
                    <div class="ps-th ps-th-level">Grade Level</div>
                    <div class="ps-th ps-th-actions">Actions</div>
                </div>
                <div class="ps-subject-list" id="subjectList">
                    <?php foreach ($subjects as $i => $s): ?>
                        <?php
                        $grade_level_label = teacher_grade_level_label($s);
                        $search_text = strtolower(trim($s->code . ' ' . $s->description . ' ' . $grade_level_label));
                        ?>
                        <div class="ps-subject-item ps-subject-item-teacher" data-search="<?= htmlspecialchars($search_text, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="ps-col-num">
                                <span class="ps-subject-index"><?= $i + 1 ?></span>
                            </div>

                            <div class="ps-col-subject">
                                <div class="ps-subject-top">
                                    <span class="ps-subject-code"><?= htmlspecialchars($s->code) ?></span>
                                    <span class="ps-subject-name"><?= htmlspecialchars($s->description) ?></span>
                                </div>
                            </div>

                            <div class="ps-col-level">
                                <span class="ps-level-badge"><?= htmlspecialchars($grade_level_label) ?></span>
                            </div>

                            <?php $back_param = urlencode('course/teacher_subjects'); ?>
                            <div class="ps-col-actions">
                                <a href="<?= site_url('course/content/' . $s->id . '?back=' . $back_param) ?>" class="ps-action-btn ps-action-view">
                                    <i class="bi bi-eye-fill"></i> Open
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ps-no-results" id="noResults" style="display:none;">
                    <i class="bi bi-search" style="font-size:1.8rem;opacity:0.3;"></i>
                    <p>No subjects match your search.</p>
                </div>
            <?php else: ?>
                <div class="ps-empty">
                    <div class="ps-empty-icon">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <div class="ps-empty-title">No subjects assigned yet</div>
                    <div class="ps-empty-sub">Your Course Creator will assign subjects to you. Check back later or contact your administrator.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('subjectSearch') && document.getElementById('subjectSearch').addEventListener('input', function () {
    var q = this.value.toLowerCase().trim();
    var items = document.querySelectorAll('.ps-subject-item');
    var visible = 0;
    items.forEach(function (item) {
        var match = !q || item.dataset.search.includes(q);
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
.ps-back:hover { background: #dbeafe; color: #1d4ed8; text-decoration: none; }

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
.ps-hero-info { min-width: 0; }
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
.ps-tag-degree { background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); }
.ps-tag-code { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.25); }
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
}
.ps-hero-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
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

.ps-layout { display: grid; gap: 1.5rem; align-items: start; }
.ps-layout-full { grid-template-columns: 1fr; }

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
.ps-card-title i { color: #2563eb; font-size: 1rem; }
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
.ps-search:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }

.ps-table-head {
    display: grid;
    padding: 0.55rem 1.5rem;
    background: #f8faff;
    border-bottom: 1px solid #eaecf0;
}
.ps-table-head-teacher {
    grid-template-columns: 44px minmax(0, 1fr) 180px auto;
}
.ps-th {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}
.ps-th-actions { text-align: right; min-width: 170px; }

.ps-subject-list { position: relative; overflow: visible; padding: 0; }
.ps-subject-item {
    position: relative;
    display: grid;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.14s;
    animation: ps-fadein 0.22s ease forwards;
}
.ps-subject-item-teacher {
    grid-template-columns: 44px minmax(0, 1fr) 180px auto;
}
@keyframes ps-fadein { from { opacity: 0; transform: translateY(3px); } to { opacity: 1; transform: translateY(0); } }
.ps-subject-item:last-child { border-bottom: none; }
.ps-subject-item:hover { background: #f8f9ff; }

.ps-col-num { display: flex; align-items: center; }
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
.ps-col-subject { min-width: 0; padding-right: 1.5rem; }
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
.ps-col-level {
    min-width: 0;
    padding-right: 1rem;
}
.ps-level-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 999px;
    padding: 0.32rem 0.8rem;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.02em;
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
.ps-action-view { background: #dbeafe; color: #1d4ed8; }
.ps-action-view:hover { background: #bfdbfe; color: #1e40af; text-decoration: none; transform: translateY(-1px); }
.ps-action-edit { background: #fef9c3; color: #a16207; }
.ps-action-edit:hover { background: #fef08a; color: #854d0e; text-decoration: none; transform: translateY(-1px); }

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
.ps-no-results p { margin: 0; }

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

@media (max-width: 900px) {
    .ps-table-head-teacher { display: none; }
    .ps-subject-item-teacher {
        grid-template-columns: 36px minmax(0, 1fr);
        grid-template-rows: auto auto auto;
        row-gap: 0.55rem;
    }
    .ps-col-subject {
        padding-right: 0;
    }
    .ps-col-level {
        grid-column: 2;
        padding-right: 0;
    }
    .ps-col-actions {
        grid-column: 2;
        justify-content: flex-start;
        min-width: 0;
    }
}

@media (max-width: 640px) {
    .ps-page {
        padding: 0.75rem 0;
    }
    .ps-hero-content {
        padding: 1.5rem 1.2rem;
    }
    .ps-hero-left {
        align-items: flex-start;
    }
    .ps-hero-avatar {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        font-size: 1.2rem;
    }
    .ps-hero-title {
        font-size: 1.25rem;
    }
    .ps-hero-stats {
        width: 100%;
    }
    .ps-hero-stat {
        flex: 1;
        min-width: 0;
        padding: 0.9rem 1rem;
    }
    .ps-card-head,
    .ps-subject-item {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    .ps-search {
        width: 100%;
    }
}
</style>
