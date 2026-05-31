<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$enrolled_subjects = isset($enrolled_subjects) && is_array($enrolled_subjects) ? $enrolled_subjects : array();
$subjects = isset($subjects) && is_array($subjects) ? $subjects : array();
$enrolled_ids = array_map('intval', array_column($enrolled_subjects, 'id'));
$total_subjects = 0;
$enrolled_count = 0;

if (!function_exists('student_subject_grade_label')) {
    function student_subject_grade_label($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        return is_numeric($value) ? 'Grade ' . str_pad((int) $value, 2, '0', STR_PAD_LEFT) : $value;
    }
}

if (!function_exists('student_subject_system_label')) {
    function student_subject_system_label($subject)
    {
        if (!empty($subject->system_type)) {
            return strtoupper((string) $subject->system_type);
        }

        return 'COURSE';
    }
}

if (!function_exists('student_subject_name')) {
    function student_subject_name($subject)
    {
        if (!empty($subject->name)) {
            return (string) $subject->name;
        }

        if (!empty($subject->description)) {
            return (string) $subject->description;
        }

        return 'Subject';
    }
}

foreach ($subjects as $program) {
    if (empty($program['subjects']) || !is_array($program['subjects'])) {
        continue;
    }

    foreach ($program['subjects'] as $subject) {
        $total_subjects++;
        if (in_array((int) $subject->id, $enrolled_ids, true)) {
            $enrolled_count++;
        }
    }
}

$available_count = max(0, $total_subjects - $enrolled_count);
$year_level_label = student_subject_grade_label(isset($year_level) ? $year_level : '');
$filter_label = !empty($filter_type) ? strtoupper((string) $filter_type) : '';
?>

<div class="ss-page">
    <a href="<?= site_url('student') ?>" class="ss-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Dashboard
    </a>

    <div class="ss-hero">
        <div class="ss-hero-bg"></div>
        <div class="ss-hero-content">
            <div class="ss-hero-left">
                <div class="ss-hero-avatar">SC</div>
                <div class="ss-hero-copy">
                    <div class="ss-hero-meta">
                        <span class="ss-tag ss-tag-main">Student</span>
                        <span class="ss-tag ss-tag-soft">Subject Catalog</span>
                        <?php if ($year_level_label !== ''): ?>
                            <span class="ss-tag ss-tag-soft"><?= htmlspecialchars($year_level_label) ?></span>
                        <?php endif; ?>
                        <?php if ($filter_label !== ''): ?>
                            <span class="ss-tag ss-tag-soft"><?= htmlspecialchars($filter_label) ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="ss-hero-title">My Subjects</h1>
                    <p class="ss-hero-desc">Browse your available subjects, continue enrolled courses, and open learning content from one place.</p>
                </div>
            </div>
            <div class="ss-hero-stats">
                <div class="ss-hero-stat">
                    <div class="ss-hero-stat-num"><?= (int) $total_subjects ?></div>
                    <div class="ss-hero-stat-lbl">Total Subjects</div>
                </div>
                <div class="ss-hero-stat">
                    <div class="ss-hero-stat-num"><?= (int) $enrolled_count ?></div>
                    <div class="ss-hero-stat-lbl">Enrolled</div>
                </div>
                <div class="ss-hero-stat">
                    <div class="ss-hero-stat-num"><?= (int) $available_count ?></div>
                    <div class="ss-hero-stat-lbl">Available</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ss-card">
        <div class="ss-card-head">
            <div class="ss-card-title">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Subjects</span>
                <span class="ss-count-pill"><?= (int) $total_subjects ?></span>
            </div>
            <?php if ($total_subjects > 0): ?>
                <div class="ss-search-wrap">
                    <i class="bi bi-search ss-search-icon"></i>
                    <input type="text" class="ss-search" id="subjectSearch" placeholder="Search subjects...">
                </div>
            <?php endif; ?>
        </div>

        <?php if ($total_subjects > 0): ?>
            <div class="ss-grid" id="subjectGrid">
                <?php foreach ($subjects as $program): ?>
                    <?php if (empty($program['subjects']) || !is_array($program['subjects'])) continue; ?>
                    <?php foreach ($program['subjects'] as $subject): ?>
                        <?php
                        $is_enrolled = in_array((int) $subject->id, $enrolled_ids, true);
                        $subject_name = student_subject_name($subject);
                        $subject_description = !empty($subject->description) ? (string) $subject->description : 'Course materials and activities are available for this subject.';
                        $subject_grade_label = student_subject_grade_label(isset($subject->year_level) ? $subject->year_level : '');
                        $subject_code = !empty($subject->code) ? (string) $subject->code : 'SUBJECT';
                        $subject_system_label = student_subject_system_label($subject);
                        $subject_cover_label = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $subject_code), 0, 3));
                        if ($subject_cover_label === '') {
                            $subject_cover_label = 'SUB';
                        }
                        $search_text = strtolower(trim($subject_code . ' ' . $subject_name . ' ' . $subject_description . ' ' . $subject_grade_label . ' ' . $subject_system_label));
                        ?>
                        <div class="ss-subject-card" data-search="<?= htmlspecialchars($search_text, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="ss-cover <?= empty($subject->cover_photo) ? 'ss-cover--fallback' : '' ?>">
                                <?php if (!empty($subject->cover_photo)): ?>
                                    <img src="<?= base_url('uploads/covers/' . $subject->cover_photo) ?>" alt="<?= htmlspecialchars($subject_name) ?>">
                                <?php else: ?>
                                    <div class="ss-cover-fallback">
                                        <span><?= htmlspecialchars($subject_cover_label) ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="ss-cover-top">
                                    <span class="ss-system-pill"><?= htmlspecialchars($subject_system_label) ?></span>
                                    <?php if ($is_enrolled): ?>
                                        <span class="ss-enrolled-pill"><i class="bi bi-check2"></i> Enrolled</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="ss-subject-body">
                                <div class="ss-subject-meta">
                                    <span class="ss-subject-code"><?= htmlspecialchars($subject_code) ?></span>
                                    <?php if (!empty($subject->units)): ?>
                                        <span class="ss-subject-units"><?= htmlspecialchars($subject->units) ?> Units</span>
                                    <?php endif; ?>
                                </div>

                                <h3 class="ss-subject-title"><?= htmlspecialchars($subject_code . ' - ' . $subject_name) ?></h3>
                                <p class="ss-subject-desc"><?= htmlspecialchars($subject_description) ?></p>

                                <div class="ss-subject-footer">
                                    <?php if ($subject_grade_label !== ''): ?>
                                        <span class="ss-level-badge"><?= htmlspecialchars($subject_grade_label) ?></span>
                                    <?php else: ?>
                                        <span class="ss-level-badge">Open Subject</span>
                                    <?php endif; ?>

                                    <a href="<?= site_url('student/content/' . $subject->id) ?>" class="ss-action-btn <?= $is_enrolled ? 'ss-action-btn--primary' : 'ss-action-btn--ghost' ?>">
                                        <i class="bi <?= $is_enrolled ? 'bi-arrow-right-circle-fill' : 'bi-play-circle-fill' ?>"></i>
                                        <?= $is_enrolled ? 'Continue' : 'Open' ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <div class="ss-empty ss-empty-search" id="noResults" style="display:none;">
                <div class="ss-empty-icon">
                    <i class="bi bi-search"></i>
                </div>
                <div class="ss-empty-title">No subjects match your search</div>
                <div class="ss-empty-sub">Try a different subject code, title, or grade level.</div>
            </div>
        <?php else: ?>
            <div class="ss-empty">
                <div class="ss-empty-icon">
                    <i class="bi bi-journal-x"></i>
                </div>
                <div class="ss-empty-title">No subjects available</div>
                <div class="ss-empty-sub">Check back later for new subjects or contact your administrator.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    var searchInput = document.getElementById('subjectSearch');
    if (!searchInput) {
        return;
    }

    var cards = document.querySelectorAll('.ss-subject-card');
    var noResults = document.getElementById('noResults');

    searchInput.addEventListener('input', function () {
        var query = this.value.toLowerCase().trim();
        var visible = 0;

        cards.forEach(function (card) {
            var match = !query || (card.dataset.search || '').indexOf(query) !== -1;
            card.style.display = match ? '' : 'none';
            if (match) {
                visible++;
            }
        });

        if (noResults) {
            noResults.style.display = visible === 0 ? 'flex' : 'none';
        }
    });
})();
</script>

<style>
.ss-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0 2.5rem;
}

.ss-back {
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

.ss-back:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
}

.ss-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}

.ss-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.ss-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.ss-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}

.ss-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.ss-hero-avatar {
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

.ss-hero-copy {
    min-width: 0;
}

.ss-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.55rem;
}

.ss-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.ss-tag-main {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.ss-tag-soft {
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.92);
    border: 1px solid rgba(255,255,255,0.22);
}

.ss-hero-title {
    font-size: 1.65rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.35rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.ss-hero-desc {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.78);
    margin: 0;
    line-height: 1.55;
    max-width: 640px;
}

.ss-hero-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.ss-hero-stat {
    min-width: 110px;
    padding: 0.95rem 1rem;
    border-radius: 16px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.16);
    backdrop-filter: blur(12px);
}

.ss-hero-stat-num {
    font-size: 1.35rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 0.35rem;
}

.ss-hero-stat-lbl {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.76);
}

.ss-card {
    background: #fff;
    border: 1px solid #dbe7ff;
    border-radius: 22px;
    box-shadow: 0 18px 40px rgba(15,23,42,0.05);
    overflow: hidden;
}

.ss-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem 1.3rem;
    border-bottom: 1px solid #e5eefc;
    flex-wrap: wrap;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.ss-card-title {
    display: inline-flex;
    align-items: center;
    gap: 0.7rem;
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
}

.ss-card-title i {
    color: #2563eb;
    font-size: 1rem;
}

.ss-count-pill {
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

.ss-search-wrap {
    position: relative;
    min-width: min(100%, 320px);
}

.ss-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.95rem;
}

.ss-search {
    width: 100%;
    height: 44px;
    border-radius: 12px;
    border: 1px solid #d7e3f8;
    background: #f8fbff;
    padding: 0 1rem 0 2.7rem;
    font-size: 0.9rem;
    color: #0f172a;
    transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
}

.ss-search:focus {
    outline: none;
    border-color: #60a5fa;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.12);
}

.ss-grid {
    padding: 1.3rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.2rem;
}

.ss-subject-card {
    background: #fff;
    border: 1px solid #dbe7ff;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 24px rgba(15,23,42,0.05);
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.ss-subject-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 36px rgba(37,99,235,0.14);
    border-color: #93c5fd;
}

.ss-cover {
    position: relative;
    height: 170px;
    overflow: hidden;
    background: linear-gradient(135deg, #0f2f6b 0%, #1d4ed8 100%);
}

.ss-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.ss-cover--fallback {
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.ss-cover-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.03) 100%);
}

.ss-cover-fallback span {
    font-size: 2.7rem;
    font-weight: 800;
    color: rgba(255,255,255,0.92);
    letter-spacing: 0.08em;
}

.ss-cover-top {
    position: absolute;
    top: 0.9rem;
    left: 0.9rem;
    right: 0.9rem;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.6rem;
}

.ss-system-pill,
.ss-enrolled-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.38rem 0.72rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    backdrop-filter: blur(10px);
}

.ss-system-pill {
    background: rgba(255,255,255,0.18);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
}

.ss-enrolled-pill {
    background: rgba(16,185,129,0.92);
    color: #fff;
    box-shadow: 0 6px 14px rgba(16,185,129,0.28);
}

.ss-subject-body {
    padding: 1.15rem 1.15rem 1.2rem;
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    flex: 1;
}

.ss-subject-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.ss-subject-code {
    font-size: 0.76rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #2563eb;
}

.ss-subject-units {
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 0.72rem;
    font-weight: 700;
}

.ss-subject-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.ss-subject-desc {
    margin: 0;
    color: #64748b;
    font-size: 0.88rem;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 4.15rem;
}

.ss-subject-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: auto;
    flex-wrap: wrap;
}

.ss-level-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.38rem 0.72rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #334155;
    font-size: 0.76rem;
    font-weight: 700;
}

.ss-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    min-height: 40px;
    padding: 0.65rem 0.95rem;
    border-radius: 12px;
    font-size: 0.86rem;
    font-weight: 800;
    text-decoration: none;
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, color 0.15s ease;
}

.ss-action-btn:hover {
    text-decoration: none;
    transform: translateY(-1px);
}

.ss-action-btn--primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #fff;
    box-shadow: 0 10px 20px rgba(37,99,235,0.18);
}

.ss-action-btn--primary:hover {
    color: #fff;
    box-shadow: 0 14px 28px rgba(37,99,235,0.24);
}

.ss-action-btn--ghost {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.ss-action-btn--ghost:hover {
    color: #1d4ed8;
    background: #dbeafe;
}

.ss-empty {
    padding: 3rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #64748b;
}

.ss-empty-search {
    border-top: 1px solid #e5eefc;
}

.ss-empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1d4ed8;
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

.ss-empty-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.45rem;
}

.ss-empty-sub {
    font-size: 0.9rem;
    line-height: 1.6;
    max-width: 460px;
}

@media (max-width: 991px) {
    .ss-hero-content {
        padding: 1.7rem;
    }

    .ss-hero-left {
        align-items: flex-start;
    }

    .ss-hero-stats {
        width: 100%;
    }

    .ss-hero-stat {
        flex: 1 1 120px;
    }
}

@media (max-width: 640px) {
    .ss-page {
        padding-top: 0.85rem;
    }

    .ss-hero-content,
    .ss-card-head,
    .ss-grid {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .ss-hero-content {
        padding-top: 1.45rem;
        padding-bottom: 1.45rem;
    }

    .ss-hero-left {
        flex-direction: column;
        align-items: flex-start;
    }

    .ss-hero-title {
        font-size: 1.4rem;
    }

    .ss-search-wrap {
        min-width: 100%;
    }

    .ss-grid {
        grid-template-columns: 1fr;
    }

    .ss-subject-footer {
        align-items: stretch;
    }

    .ss-action-btn {
        width: 100%;
    }
}
</style>
