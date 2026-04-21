<?php
$total_subjects = count($subjects);
$total_units = 0;
$program_subjects = 0;
$grade_subjects = 0;

foreach ($subjects as $subject) {
    $total_units += (float) ($subject->units ?: 0);
    if (!empty($subject->program_id)) {
        $program_subjects++;
    }
    if (!empty($subject->grade_level_id)) {
        $grade_subjects++;
    }
}
?>

<div class="subjects-page">
    <div class="subjects-hero">
        <div>
            <div class="subjects-eyebrow">Learning Catalog</div>
            <h4>Subjects</h4>
            <p>Browse, filter, and manage subject content by course/program, grade level, and semester.</p>
        </div>
        <a href="<?= site_url('subjects/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Subject
        </a>
    </div>

    <form action="<?= site_url('subjects') ?>" method="get" class="subject-filter-bar">
        <div class="filter-field">
            <label for="programFilter">Course / Program</label>
            <select id="programFilter" name="program_id" class="form-select">
                <option value="">All Courses</option>
                <?php foreach ($programs as $program): ?>
                    <option value="<?= $program->id ?>" <?= ($filter_program == $program->id) ? 'selected' : '' ?>>
                        <?= $program->code ?> - <?= $program->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-field">
            <label for="gradeLevelFilter">Grade Level</label>
            <select id="gradeLevelFilter" name="grade_level_id" class="form-select">
                <option value="">All Grade Levels</option>
                <?php foreach ($grade_levels as $grade_level): ?>
                    <option value="<?= $grade_level->id ?>" <?= ($filter_grade_level == $grade_level->id) ? 'selected' : '' ?>>
                        <?= $grade_level->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-field">
            <label for="semesterFilter">Semester</label>
            <select id="semesterFilter" name="semester_type" class="form-select">
                <option value="">All Semesters</option>
                <option value="1st_sem" <?= ($filter_semester == '1st_sem') ? 'selected' : '' ?>>First Semester</option>
                <option value="2nd_sem" <?= ($filter_semester == '2nd_sem') ? 'selected' : '' ?>>Second Semester</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel me-1"></i>Apply
            </button>
            <a href="<?= site_url('subjects') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
            </a>
        </div>
    </form>

    <div class="subject-summary-grid">
        <div class="subject-summary">
            <span>Total Subjects</span>
            <strong><?= $total_subjects ?></strong>
        </div>
        <div class="subject-summary">
            <span>Total Units</span>
            <strong><?= rtrim(rtrim(number_format($total_units, 2), '0'), '.') ?></strong>
        </div>
        <div class="subject-summary">
            <span>Course Subjects</span>
            <strong><?= $program_subjects ?></strong>
        </div>
        <div class="subject-summary">
            <span>Grade-Level Subjects</span>
            <strong><?= $grade_subjects ?></strong>
        </div>
    </div>

    <div class="data-table">
        <div class="table-header subjects-table-header">
            <div>
                <h5><i class="bi bi-journal-bookmark-fill me-2"></i>Subject List</h5>
                <small><?= $total_subjects ?> result<?= $total_subjects == 1 ? '' : 's' ?> found</small>
            </div>
        </div>

        <?php if (!empty($subjects)): ?>
            <div class="subject-card-list">
                <?php foreach ($subjects as $subject): ?>
                    <?php
                    $scope_label = $subject->program_code
                        ? $subject->program_code . ' - ' . $subject->program_name
                        : ($subject->grade_level_name ?: 'Unassigned');
                    $semester_label = '-';
                    if ($subject->semester_type === '1st_sem') {
                        $semester_label = 'First Semester';
                    } elseif ($subject->semester_type === '2nd_sem') {
                        $semester_label = 'Second Semester';
                    }
                    $lesson_count = (int) ($subject->lesson_count ?? 0);
                    $has_lessons = $lesson_count > 0;
                    ?>
                    <div class="subject-card">
                        <div class="subject-code-block">
                            <span><?= $subject->code ?></span>
                        </div>
                        <div class="subject-main">
                            <div class="subject-title-row">
                                <h6><?= $subject->description ?></h6>
                                <span class="subject-units"><?= $subject->units ?: 0 ?> unit<?= (float) $subject->units == 1 ? '' : 's' ?></span>
                            </div>
                            <div class="subject-meta">
                                <span><i class="bi bi-mortarboard"></i><?= $scope_label ?></span>
                                <span><i class="bi bi-calendar3"></i><?= $semester_label ?></span>
                                <?php if (!empty($subject->learning_area_name)): ?>
                                    <span><i class="bi bi-collection"></i><?= $subject->learning_area_name ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="subject-actions">
                            <a href="<?= site_url('course/content/' . $subject->id) ?>" class="btn-action subject-folder-action <?= $has_lessons ? 'has-lessons' : 'is-empty' ?>" title="<?= $has_lessons ? $lesson_count . ' lesson' . ($lesson_count === 1 ? '' : 's') . ' added' : 'No lessons added yet' ?>">
                                <i class="bi <?= $has_lessons ? 'bi-folder-check' : 'bi-folder' ?>"></i>
                                <?php if ($has_lessons): ?>
                                    <span><?= $lesson_count ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="<?= site_url('subjects/view/' . $subject->id) ?>" class="btn-action btn-view" title="View">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="<?= site_url('subjects/edit/' . $subject->id) ?>" class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="<?= site_url('subjects/delete/' . $subject->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this subject?');">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="subjects-empty">
                <i class="bi bi-journal-x"></i>
                <h5>No subjects found</h5>
                <p>Adjust the filters or add a new subject to start building course content.</p>
                <a href="<?= site_url('subjects/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Add Subject
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.subjects-page {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.subjects-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
}
.subjects-eyebrow {
    color: #6366f1;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 0.35rem;
}
.subjects-hero h4 {
    margin: 0;
    font-weight: 800;
    color: #1e293b;
}
.subjects-hero p {
    margin: 0.35rem 0 0;
    color: #64748b;
}
.subject-filter-bar {
    display: grid;
    grid-template-columns: minmax(220px, 1.3fr) minmax(180px, 1fr) minmax(170px, 0.8fr) auto;
    gap: 1rem;
    align-items: end;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #f8fafc;
}
.filter-field label {
    display: block;
    margin-bottom: 0.35rem;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 700;
}
.filter-actions {
    display: flex;
    gap: 0.5rem;
}
.subject-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
}
.subject-summary {
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
}
.subject-summary span {
    display: block;
    color: #64748b;
    font-size: 0.8rem;
    font-weight: 700;
}
.subject-summary strong {
    display: block;
    margin-top: 0.25rem;
    color: #1e293b;
    font-size: 1.5rem;
}
.subjects-table-header small {
    color: #64748b;
}
.subject-card-list {
    display: flex;
    flex-direction: column;
}
.subject-card {
    display: grid;
    grid-template-columns: 96px minmax(0, 1fr) auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    border-top: 1px solid #e2e8f0;
}
.subject-card:first-child {
    border-top: 0;
}
.subject-card:hover {
    background: #f8fafc;
}
.subject-code-block {
    min-height: 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eef2ff;
    color: #3730a3;
    font-weight: 800;
    text-align: center;
}
.subject-main {
    min-width: 0;
}
.subject-title-row {
    display: flex;
    align-items: start;
    justify-content: space-between;
    gap: 1rem;
}
.subject-title-row h6 {
    margin: 0;
    color: #1e293b;
    font-weight: 800;
}
.subject-units {
    flex-shrink: 0;
    padding: 0.25rem 0.5rem;
    border-radius: 999px;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.78rem;
    font-weight: 800;
}
.subject-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 0.5rem;
    color: #64748b;
    font-size: 0.85rem;
}
.subject-meta span {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.subject-actions {
    display: flex;
    gap: 0.35rem;
}
.subject-folder-action {
    position: relative;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    color: #64748b;
}
.subject-folder-action.has-lessons {
    border-color: #86efac;
    background: #dcfce7;
    color: #166534;
}
.subject-folder-action.is-empty:hover {
    border-color: #93c5fd;
    background: #dbeafe;
    color: #1d4ed8;
}
.subject-folder-action.has-lessons:hover {
    border-color: #4ade80;
    background: #bbf7d0;
    color: #14532d;
}
.subject-folder-action span {
    position: absolute;
    top: -0.45rem;
    right: -0.4rem;
    min-width: 1.05rem;
    height: 1.05rem;
    padding: 0 0.25rem;
    border-radius: 999px;
    background: #16a34a;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    line-height: 1.05rem;
    text-align: center;
    box-shadow: 0 0 0 2px #fff;
}
.subjects-empty {
    padding: 3rem 1rem;
    text-align: center;
    color: #94a3b8;
}
.subjects-empty i {
    display: block;
    margin-bottom: 1rem;
    font-size: 3rem;
}
.subjects-empty h5 {
    color: #64748b;
    font-weight: 800;
}
.subjects-empty p {
    margin: 0 auto 1.25rem;
    max-width: 420px;
}
@media (max-width: 991.98px) {
    .subject-filter-bar,
    .subject-summary-grid,
    .subject-card {
        grid-template-columns: 1fr;
    }
    .filter-actions,
    .subject-actions,
    .subjects-hero {
        flex-wrap: wrap;
    }
    .subject-title-row {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
