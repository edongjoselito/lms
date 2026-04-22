<?php
$enrolled_ids = array_map('intval', array_column($enrolled_subjects ?? array(), 'id'));
$total_subjects = 0;
$enrolled_count = 0;

foreach ($subjects as $program) {
    foreach ($program['subjects'] as $subject) {
        $total_subjects++;
        if (in_array((int) $subject->id, $enrolled_ids, true)) {
            $enrolled_count++;
        }
    }
}
?>
<div class="subjects-page">
    <div class="page-header subjects-hero">
        <div>
            <span class="page-eyebrow">Course catalog</span>
            <h1 class="page-title">Subjects</h1>
            <p class="page-subtitle">Browse courses with published content</p>
        </div>
        <div class="subjects-summary">
            <div class="summary-tile">
                <span><?= $total_subjects ?></span>
                <small>Courses</small>
            </div>
            <div class="summary-tile">
                <span><?= $enrolled_count ?></span>
                <small>Enrolled</small>
            </div>
        </div>
    </div>

    <div class="filter-tabs" aria-label="Subject filters">
        <a href="<?= site_url('student/subjects') ?>" class="filter-tab <?= empty($filter_type) ? 'active' : '' ?>">All</a>
        <a href="<?= site_url('student/subjects?system_type=deped') ?>" class="filter-tab <?= ($filter_type == 'deped') ? 'active' : '' ?>">DepEd</a>
        <a href="<?= site_url('student/subjects?system_type=ched') ?>" class="filter-tab <?= ($filter_type == 'ched') ? 'active' : '' ?>">CHED</a>
        <a href="<?= site_url('student/subjects?system_type=tesda') ?>" class="filter-tab <?= ($filter_type == 'tesda') ? 'active' : '' ?>">TESDA</a>
    </div>

    <?php if ($total_subjects > 0): ?>
        <?php foreach ($subjects as $program_code => $program): ?>
            <div class="program-section">
                <div class="program-heading">
                    <h2 class="program-title">
                        <i class="bi bi-mortarboard"></i>
                        <?= htmlspecialchars($program['program_name'] ?: $program_code) ?>
                    </h2>
                </div>
                <div class="subjects-grid">
                    <?php foreach ($program['subjects'] as $s): ?>
                        <?php
                        $is_enrolled = in_array((int) $s->id, $enrolled_ids, true);
                        $system_type = strtolower($s->system_type ?: 'general');
                        ?>
                        <div class="subject-card">
                            <div class="subject-cover <?= empty($s->cover_photo) ? 'subject-cover-fallback' : '' ?>">
                                <?php if (!empty($s->cover_photo)): ?>
                                    <img src="<?= base_url('uploads/covers/' . $s->cover_photo) ?>" alt="<?= htmlspecialchars($s->name) ?>">
                                <?php else: ?>
                                    <span><?= htmlspecialchars($s->code ?: 'Course') ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="subject-card-body">
                                <div class="subject-card-top">
                                    <span class="subject-badge <?= htmlspecialchars($system_type) ?>"><?= htmlspecialchars(strtoupper($system_type)) ?></span>
                                    <?php if ($is_enrolled): ?>
                                        <span class="enrolled-badge"><i class="bi bi-check2"></i> Enrolled</span>
                                    <?php endif; ?>
                                </div>
                                <div class="subject-code"><?= htmlspecialchars($s->code) ?></div>
                                <h3 class="subject-name"><?= htmlspecialchars($s->name) ?></h3>
                                <div class="subject-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-mortarboard"></i>
                                        <span><?= htmlspecialchars($s->units ?: '-') ?> Units</span>
                                    </div>
                                </div>
                                <?php if ($is_enrolled): ?>
                                    <a href="<?= site_url('student/content/' . $s->id) ?>" class="subject-action primary">
                                        Open Course <i class="bi bi-arrow-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= site_url('student/enroll/' . $s->id) ?>" class="subject-action">
                                        Enroll <i class="bi bi-plus-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-book"></i>
            </div>
            <h3>No subjects with content found</h3>
            <p>No courses with published content available yet</p>
        </div>
    <?php endif; ?>
</div>

<style>
.subjects-page {
    padding: 0.5rem 0 1.5rem;
}

.page-header {
    margin-bottom: 1rem;
}

.subjects-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding: 0.25rem 0 0.5rem;
}

.page-eyebrow {
    display: inline-flex;
    margin-bottom: 0.45rem;
    color: #2f6fed;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0;
}

.page-title {
    font-size: 1.7rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.35rem 0;
}

.page-subtitle {
    color: #667085;
    margin: 0;
    font-size: 0.95rem;
}

.subjects-summary {
    display: flex;
    gap: 0.75rem;
    flex-shrink: 0;
}

.summary-tile {
    min-width: 92px;
    padding: 0.8rem 0.9rem;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    background: #f8fafc;
}

.summary-tile span,
.summary-tile small {
    display: block;
}

.summary-tile span {
    color: #182033;
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1;
}

.summary-tile small {
    margin-top: 0.3rem;
    color: #667085;
    font-size: 0.78rem;
}

.filter-tabs {
    display: inline-flex;
    gap: 0.35rem;
    background: #eef2f7;
    padding: 0.25rem;
    border-radius: 14px;
    margin-bottom: 1.5rem;
    max-width: 100%;
    overflow-x: auto;
}

.filter-tab {
    padding: 0.55rem 1rem;
    border-radius: 12px;
    text-decoration: none;
    color: #667085;
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.filter-tab:hover {
    color: #2f6fed;
}

.filter-tab.active {
    background: #fff;
    color: #182033;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.08);
}

.program-section {
    margin-bottom: 2.25rem;
}

.program-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.program-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #182033;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.program-title i {
    color: #2f6fed;
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.subject-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e4e7ec;
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.subject-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(24, 32, 51, 0.08);
}

.subject-cover {
    height: 132px;
    overflow: hidden;
    background: #edf4ff;
}

.subject-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.subject-cover-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef6f2;
}

.subject-cover-fallback span {
    max-width: 78%;
    padding: 0.55rem 0.8rem;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(47, 111, 237, 0.14);
    color: #182033;
    font-size: 0.9rem;
    font-weight: 700;
    text-align: center;
    overflow-wrap: anywhere;
}

.subject-card-body {
    padding: 1.2rem;
}

.subject-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.9rem;
}

.subject-badge,
.enrolled-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.65rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 700;
}

.subject-badge.deped {
    background: #edf4ff;
    color: #2f6fed;
}

.subject-badge.ched {
    background: #fff6df;
    color: #9a6700;
}

.subject-badge.tesda {
    background: #e9f8f0;
    color: #0f8b5f;
}

.subject-badge.general {
    background: #eef2f7;
    color: #475467;
}

.enrolled-badge {
    background: #e9f8f0;
    color: #0f8b5f;
}

.subject-code {
    color: #2f6fed;
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 0.45rem;
}

.subject-name {
    min-height: 3rem;
    font-size: 1.05rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.9rem 0;
    line-height: 1.4;
}

.subject-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #667085;
    font-size: 0.85rem;
}

.subject-action {
    width: 100%;
    min-height: 42px;
    padding: 0.7rem 1rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: #f7f9fc;
    color: #182033;
    border: 1px solid #e4e7ec;
    transition: all 0.2s ease;
}

.subject-action:hover {
    background: #edf4ff;
    color: #2f6fed;
    border-color: #d7e6ff;
}

.subject-action.primary {
    background: #2f6fed;
    color: #fff;
    border-color: #2f6fed;
}

.subject-action.primary:hover {
    color: #fff;
    box-shadow: 0 10px 20px rgba(47, 111, 237, 0.2);
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: #fff;
    border-radius: 8px;
    border: 1px dashed #cfd6e3;
}

.empty-icon {
    width: 72px;
    height: 72px;
    background: #edf4ff;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.empty-icon i {
    font-size: 2rem;
    color: #2f6fed;
}

.empty-state h3 {
    font-size: 1.25rem;
    color: #182033;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    color: #667085;
    margin: 0;
}

@media (max-width: 768px) {
    .subjects-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .subjects-summary {
        width: 100%;
    }

    .summary-tile {
        flex: 1;
    }

    .filter-tabs {
        display: flex;
    }

    .subjects-grid {
        grid-template-columns: 1fr;
    }
}
</style>
