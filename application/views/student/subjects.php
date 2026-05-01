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

$available_count = max(0, $total_subjects - $enrolled_count);
?>
<div class="subjects-page">
    <div class="page-header subjects-hero">
        <div class="hero-copy">
            <span class="page-eyebrow"><i class="bi bi-compass"></i> Course catalog</span>
            <h1 class="page-title">Find Your Courses</h1>
            <p class="page-subtitle">Browse published subjects, continue enrolled courses, or join a new class.</p>
        </div>
        <div class="subjects-summary">
            <div class="summary-tile">
                <i class="bi bi-journal-bookmark"></i>
                <div>
                    <span><?= (int) $total_subjects ?></span>
                    <small>Courses</small>
                </div>
            </div>
            <div class="summary-tile">
                <i class="bi bi-check2-circle"></i>
                <div>
                    <span><?= (int) $enrolled_count ?></span>
                    <small>Enrolled</small>
                </div>
            </div>
            <div class="summary-tile">
                <i class="bi bi-plus-circle"></i>
                <div>
                    <span><?= (int) $available_count ?></span>
                    <small>Available</small>
                </div>
            </div>
        </div>
    </div>

    <div class="subjects-toolbar">
        <div class="filter-tabs" aria-label="Subject filters">
            <a href="<?= site_url('student/subjects') ?>" class="filter-tab <?= empty($filter_type) ? 'active' : '' ?>"><i class="bi bi-grid"></i> All</a>
            <a href="<?= site_url('student/subjects?system_type=deped') ?>" class="filter-tab <?= ($filter_type == 'deped') ? 'active' : '' ?>">DepEd</a>
            <a href="<?= site_url('student/subjects?system_type=ched') ?>" class="filter-tab <?= ($filter_type == 'ched') ? 'active' : '' ?>">CHED</a>
            <a href="<?= site_url('student/subjects?system_type=tesda') ?>" class="filter-tab <?= ($filter_type == 'tesda') ? 'active' : '' ?>">TESDA</a>
        </div>
        <span class="result-count"><?= (int) $total_subjects ?> <?= $total_subjects === 1 ? 'course' : 'courses' ?> shown</span>
    </div>

    <?php if ($total_subjects > 0): ?>
        <?php foreach ($subjects as $program_code => $program): ?>
            <div class="program-section">
                <div class="program-heading">
                    <div>
                        <h2 class="program-title">
                            <i class="bi bi-mortarboard"></i>
                            <?= htmlspecialchars($program['program_name'] ?: $program_code) ?>
                        </h2>
                        <p class="program-subtitle">Published subjects available to your account</p>
                    </div>
                    <span class="program-count"><?= count($program['subjects']) ?> <?= count($program['subjects']) === 1 ? 'course' : 'courses' ?></span>
                </div>
                <div class="subjects-grid">
                    <?php foreach ($program['subjects'] as $s): ?>
                        <?php
                        $is_enrolled = in_array((int) $s->id, $enrolled_ids, true);
                        ?>
                        <div class="subject-card">
                            <div class="subject-cover <?= empty($s->cover_photo) ? 'subject-cover-fallback' : '' ?>">
                                <?php if (!empty($s->cover_photo)): ?>
                                    <img src="<?= base_url('uploads/covers/' . $s->cover_photo) ?>" alt="<?= htmlspecialchars($s->name) ?>">
                                <?php else: ?>
                                    <span><?= htmlspecialchars($s->code ?: 'Course') ?></span>
                                <?php endif; ?>
                                <?php if ($is_enrolled): ?>
                                    <span class="enrolled-badge"><i class="bi bi-check2"></i> Enrolled</span>
                                <?php endif; ?>
                            </div>
                            <div class="subject-card-body">
                                <div class="subject-code-row">
                                    <span class="subject-code"><?= htmlspecialchars($s->code) ?></span>
                                    <span class="subject-units"><?= htmlspecialchars($s->units ?: '-') ?> Units</span>
                                </div>
                                <h3 class="subject-name"><?= htmlspecialchars($s->name) ?></h3>
                                <p class="subject-description"><?= htmlspecialchars(($s->description ?? '') ?: 'Course materials and activities are available for this subject.') ?></p>
                                <?php if ($is_enrolled): ?>
                                    <a href="<?= site_url('student/content/' . $s->id) ?>" class="subject-action primary">
                                        <i class="bi bi-arrow-right-circle"></i> Open Course
                                    </a>
                                <?php else: ?>
                                    <a href="<?= site_url('student/enroll/' . $s->id) ?>" class="subject-action">
                                        <i class="bi bi-plus-lg"></i> Enroll
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
    padding: 0.5rem 0 2rem;
}

.page-header {
    margin-bottom: 1.25rem;
}

.subjects-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 1.25rem;
    padding: 1.35rem;
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.hero-copy {
    min-width: 0;
}

.page-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.45rem;
    color: #2f6fed;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.35rem 0;
    line-height: 1.2;
}

.page-subtitle {
    color: #667085;
    margin: 0;
    font-size: 0.95rem;
    max-width: 620px;
    line-height: 1.55;
}

.subjects-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(96px, 1fr));
    gap: 0.75rem;
    min-width: min(430px, 100%);
}

.summary-tile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
    padding: 0.85rem;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    background: #f8fafc;
}

.summary-tile i {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    background: #edf4ff;
    color: #2f6fed;
}

.summary-tile span {
    display: block;
    color: #182033;
    font-size: 1.1rem;
    font-weight: 700;
    line-height: 1;
}

.summary-tile small {
    display: block;
    margin-top: 0.3rem;
    color: #667085;
    font-size: 0.78rem;
}

.subjects-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.filter-tabs {
    display: flex;
    gap: 0.35rem;
    background: #eef2f7;
    padding: 0.25rem;
    border-radius: 8px;
    max-width: 100%;
    overflow-x: auto;
}

.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 0.9rem;
    border-radius: 7px;
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

.result-count {
    color: #667085;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
}

.program-section {
    margin-bottom: 2rem;
}

.program-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
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

.program-subtitle {
    color: #667085;
    font-size: 0.875rem;
    margin: 0.3rem 0 0;
}

.program-count {
    flex-shrink: 0;
    padding: 0.35rem 0.65rem;
    border-radius: 8px;
    background: #f8fafc;
    color: #667085;
    border: 1px solid #e4e7ec;
    font-size: 0.78rem;
    font-weight: 700;
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1rem;
}

.subject-card {
    display: flex;
    flex-direction: column;
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
    border-color: #d7e6ff;
}

.subject-cover {
    position: relative;
    height: 146px;
    overflow: hidden;
    background: #eef6f2;
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
    box-shadow: 0 10px 20px rgba(24, 32, 51, 0.06);
}

.subject-card-body {
    display: flex;
    flex: 1;
    flex-direction: column;
    padding: 1.1rem;
}

.enrolled-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.6rem;
    border-radius: 8px;
    background: #e9f8f0;
    color: #0f8b5f;
    font-size: 0.7rem;
    font-weight: 700;
    box-shadow: 0 8px 18px rgba(24, 32, 51, 0.08);
}

.subject-code-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.65rem;
}

.subject-code {
    min-width: 0;
    color: #2f6fed;
    font-size: 0.75rem;
    font-weight: 700;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.subject-units {
    flex-shrink: 0;
    padding: 0.2rem 0.5rem;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e4e7ec;
    color: #667085;
    font-size: 0.72rem;
    font-weight: 700;
}

.subject-name {
    min-height: 2.7rem;
    font-size: 1.03rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.55rem 0;
    line-height: 1.35;
    overflow-wrap: anywhere;
}

.subject-description {
    color: #667085;
    font-size: 0.85rem;
    line-height: 1.5;
    margin: 0 0 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
    margin-top: auto;
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
    padding: 3.5rem 2rem;
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
        grid-template-columns: 1fr;
        padding: 1.1rem;
    }

    .subjects-summary {
        width: 100%;
        min-width: 0;
    }

    .summary-tile {
        flex: 1;
    }

    .subjects-toolbar {
        align-items: stretch;
        flex-direction: column;
        gap: 0.75rem;
    }

    .filter-tabs {
        display: flex;
    }

    .result-count {
        white-space: normal;
    }

    .program-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .subjects-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 520px) {
    .subjects-summary {
        grid-template-columns: 1fr;
    }

    .summary-tile {
        min-height: 64px;
    }
}
</style>
