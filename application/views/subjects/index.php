<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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

<div class="sl-page">
    <div class="sl-header">
        <h1 class="sl-title">Subjects</h1>
        <a href="<?= site_url('subjects/create') ?>" class="sl-btn-primary">
            <i class="bi bi-plus-lg"></i> Add Subject
        </a>
    </div>

    <div class="sl-toolbar">
        <form action="<?= site_url('subjects') ?>" method="get" class="sl-filters">
            <select name="program_id" class="sl-select">
                <option value="">All Programs</option>
                <?php foreach ($programs as $program): ?>
                    <option value="<?= $program->id ?>" <?= ($filter_program == $program->id) ? 'selected' : '' ?>>
                        <?= $program->code ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="grade_level_id" class="sl-select">
                <option value="">All Grade Levels</option>
                <?php foreach ($grade_levels as $grade_level): ?>
                    <option value="<?= $grade_level->id ?>" <?= ($filter_grade_level == $grade_level->id) ? 'selected' : '' ?>>
                        <?= $grade_level->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="semester_type" class="sl-select">
                <option value="">All Semesters</option>
                <option value="1st_sem" <?= ($filter_semester == '1st_sem') ? 'selected' : '' ?>>1st Sem</option>
                <option value="2nd_sem" <?= ($filter_semester == '2nd_sem') ? 'selected' : '' ?>>2nd Sem</option>
            </select>
            <button type="submit" class="sl-btn-filter">Filter</button>
            <a href="<?= site_url('subjects') ?>" class="sl-btn-reset">Reset</a>
        </form>

        <div class="sl-stats">
            <div class="sl-stat">
                <span class="sl-stat-value"><?= $total_subjects ?></span>
                <span class="sl-stat-label">Subjects</span>
            </div>
            <div class="sl-stat">
                <span class="sl-stat-value"><?= rtrim(rtrim(number_format($total_units, 2), '0'), '.') ?></span>
                <span class="sl-stat-label">Units</span>
            </div>
            <div class="sl-stat">
                <span class="sl-stat-value"><?= $program_subjects ?></span>
                <span class="sl-stat-label">College</span>
            </div>
            <div class="sl-stat">
                <span class="sl-stat-value"><?= $grade_subjects ?></span>
                <span class="sl-stat-label">K-12</span>
            </div>
        </div>
    </div>

    <?php if (!empty($subjects)): ?>
        <div class="sl-table-wrap">
            <table class="sl-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Classification</th>
                        <th>Semester</th>
                        <th>Lessons</th>
                        <th class="sl-actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                        <?php
                        $scope_label = $subject->program_code
                            ? $subject->program_code . ' - ' . $subject->program_name
                            : ($subject->grade_level_name ?: 'Unassigned');
                        $semester_label = $subject->semester_type === '1st_sem' ? '1st Sem' : ($subject->semester_type === '2nd_sem' ? '2nd Sem' : '-');
                        $lesson_count = (int) ($subject->lesson_count ?? 0);
                        $has_lessons = $lesson_count > 0;
                        ?>
                        <tr>
                            <td><span class="sl-code"><?= $subject->code ?></span></td>
                            <td><?= htmlspecialchars($subject->description) ?></td>
                            <td><?= $scope_label ?></td>
                            <td><?= $semester_label ?></td>
                            <td>
                                <?php if ($has_lessons): ?>
                                    <span class="sl-badge sl-badge--green"><?= $lesson_count ?></span>
                                <?php else: ?>
                                    <span class="sl-muted">0</span>
                                <?php endif; ?>
                            </td>
                            <td class="sl-actions-col">
                                <a href="<?= site_url('course/content/' . $subject->id) ?>" class="sl-icon-btn" title="Content">
                                    <i class="bi <?= $has_lessons ? 'bi-folder-check' : 'bi-folder' ?>"></i>
                                </a>
                                <a href="<?= site_url('subjects/edit/' . $subject->id) ?>" class="sl-icon-btn" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= site_url('subjects/delete/' . $subject->id) ?>" class="sl-icon-btn sl-icon-btn--danger" title="Delete" onclick="return confirm('Delete this subject?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="sl-empty">
            <div class="sl-empty-icon"><i class="bi bi-book"></i></div>
            <h3>No subjects found</h3>
            <p>Adjust the filters or add your first subject.</p>
            <a href="<?= site_url('subjects/create') ?>" class="sl-btn-primary">
                <i class="bi bi-plus-lg"></i> Add Subject
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    /* ── Apple-Level Design System ──────────────────────────────────── */
    .sl-page {
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
        padding: 2rem;
        color: #1d1d1f;
    }

    /* Header */
    .sl-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .sl-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1d1d1f;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .sl-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #0071e3;
        color: #fff;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .sl-btn-primary:hover {
        background: #0077ed;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 113, 227, 0.3);
    }

    /* Toolbar */
    .sl-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: nowrap;
        gap: 2rem;
        margin-bottom: 1.5rem;
        padding: 1rem 0;
    }

    /* Filters */
    .sl-filters {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .sl-select {
        padding: 0.5rem 0.75rem;
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        font-size: 0.875rem;
        color: #1d1d1f;
        background: #fff;
        min-width: 140px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2386868b' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.6rem center;
        padding-right: 2rem;
        transition: all 0.2s;
    }

    .sl-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .sl-btn-filter {
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .sl-btn-filter:hover {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    }

    .sl-btn-reset {
        padding: 0.5rem 1rem;
        background: #f8fafc;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s;
    }

    .sl-btn-reset:hover {
        background: #fff;
        border-color: #cbd5e1;
    }

    /* Stats */
    .sl-stats {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 2rem;
        flex-shrink: 0;
    }

    .sl-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        min-width: 60px;
    }

    .sl-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .sl-stat-label {
        font-size: 0.75rem;
        color: #86868b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Table Card */
    .sl-table-wrap {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .sl-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .sl-table thead th {
        background: #fafafa;
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #86868b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .sl-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        color: #1d1d1f;
        vertical-align: middle;
    }

    .sl-table tbody tr:last-child td {
        border-bottom: none;
    }

    .sl-table tbody tr:hover {
        background: #fafafa;
    }

    .sl-code {
        display: inline-block;
        padding: 0.375rem 0.625rem;
        background: #dbeafe;
        color: #3b82f6;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .sl-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .sl-badge--green {
        background: #e6f9e6;
        color: #34c759;
    }

    .sl-muted {
        color: #c4c4c4;
    }

    /* Actions */
    .sl-actions-col {
        width: 100px;
        text-align: right;
        white-space: nowrap;
    }

    .sl-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        color: #86868b;
        text-decoration: none;
        transition: all 0.15s;
        vertical-align: middle;
    }

    .sl-icon-btn+.sl-icon-btn {
        margin-left: 0.125rem;
    }

    .sl-icon-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .sl-icon-btn--danger {
        color: #ff3b30;
    }

    .sl-icon-btn--danger:hover {
        background: #fff2f2;
        color: #ff3b30;
    }

    /* Empty State */
    .sl-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: #fafafa;
        border-radius: 16px;
        border: 2px dashed rgba(0, 0, 0, 0.08);
    }

    .sl-empty-icon {
        width: 72px;
        height: 72px;
        background: #f5f5f7;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .sl-empty-icon i {
        font-size: 2rem;
        color: #86868b;
    }

    .sl-empty h3 {
        font-size: 1.25rem;
        color: #1e293b;
        margin: 0 0 0.5rem 0;
        font-weight: 600;
    }

    .sl-empty p {
        color: #86868b;
        margin: 0 0 1.5rem 0;
        font-size: 0.9375rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .sl-toolbar {
            flex-wrap: wrap;
        }

        .sl-stats {
            width: 100%;
            justify-content: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
        }
    }

    @media (max-width: 768px) {
        .sl-page {
            padding: 1rem;
        }

        .sl-header {
            flex-direction: column;
            align-items: stretch;
        }

        .sl-btn-primary {
            justify-content: center;
        }

        .sl-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .sl-select {
            width: 100%;
        }

        .sl-stats {
            gap: 1.5rem;
        }

        .sl-table thead th,
        .sl-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .sl-actions-col {
            width: auto;
        }
    }

    @media (max-width: 480px) {
        .sl-stats {
            gap: 1rem;
        }

        .sl-stat-value {
            font-size: 1.25rem;
        }
    }
</style>