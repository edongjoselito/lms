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

<div class="subjects-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Subjects</h1>
            <p class="page-subtitle">Manage your learning content and subjects</p>
        </div>
        <a href="<?= site_url('subjects/create') ?>" class="btn-add-subject">
            <i class="bi bi-plus-lg"></i> Add Subject
        </a>
    </div>

    <form action="<?= site_url('subjects') ?>" method="get" class="filter-bar-compact">
        <select id="programFilter" name="program_id" class="form-select-sm">
            <option value="">All Programs</option>
            <?php foreach ($programs as $program): ?>
                <option value="<?= $program->id ?>" <?= ($filter_program == $program->id) ? 'selected' : '' ?>>
                    <?= $program->code ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select id="gradeLevelFilter" name="grade_level_id" class="form-select-sm">
            <option value="">All Grade Levels</option>
            <?php foreach ($grade_levels as $grade_level): ?>
                <option value="<?= $grade_level->id ?>" <?= ($filter_grade_level == $grade_level->id) ? 'selected' : '' ?>>
                    <?= $grade_level->name ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select id="semesterFilter" name="semester_type" class="form-select-sm">
            <option value="">All Semesters</option>
            <option value="1st_sem" <?= ($filter_semester == '1st_sem') ? 'selected' : '' ?>>1st Sem</option>
            <option value="2nd_sem" <?= ($filter_semester == '2nd_sem') ? 'selected' : '' ?>>2nd Sem</option>
        </select>
        <button type="submit" class="btn-filter-sm">
            <i class="bi bi-funnel"></i>
        </button>
        <a href="<?= site_url('subjects') ?>" class="btn-reset-sm">
            <i class="bi bi-arrow-counterclockwise"></i>
        </a>
    </form>

    <div class="stats-grid-compact">
        <div class="stat-item-compact">
            <span class="stat-label-compact">Total Subjects</span>
            <strong><?= $total_subjects ?></strong>
        </div>
        <div class="stat-item-compact">
            <span class="stat-label-compact">Total Units</span>
            <strong><?= rtrim(rtrim(number_format($total_units, 2), '0'), '.') ?></strong>
        </div>
        <div class="stat-item-compact">
            <span class="stat-label-compact">Program Subjects</span>
            <strong><?= $program_subjects ?></strong>
        </div>
        <div class="stat-item-compact">
            <span class="stat-label-compact">Grade-Level Subjects</span>
            <strong><?= $grade_subjects ?></strong>
        </div>
    </div>

    <?php if (!empty($subjects)): ?>
        <div class="data-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Program/Grade Level</th>
                        <th>Semester</th>
                        <th>Lessons</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                        <?php
                        $scope_label = $subject->program_code
                            ? $subject->program_code . ' - ' . $subject->program_name
                            : ($subject->grade_level_name ?: 'Unassigned');
                        $semester_label = '-';
                        if ($subject->semester_type === '1st_sem') {
                            $semester_label = '1st Sem';
                        } elseif ($subject->semester_type === '2nd_sem') {
                            $semester_label = '2nd Sem';
                        }
                        $lesson_count = (int) ($subject->lesson_count ?? 0);
                        $has_lessons = $lesson_count > 0;
                        ?>
                        <tr>
                            <td><span class="code-badge"><?= $subject->code ?></span></td>
                            <td><?= htmlspecialchars($subject->description) ?></td>
                            <td><?= $scope_label ?></td>
                            <td><?= $semester_label ?></td>
                            <td>
                                <?php if ($has_lessons): ?>
                                    <span class="lesson-count"><?= $lesson_count ?></span>
                                <?php else: ?>
                                    <span class="text-muted">0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?= site_url('course/content/' . $subject->id) ?>" class="btn-action-sm" title="Manage content">
                                        <i class="bi <?= $has_lessons ? 'bi-folder-check' : 'bi-folder' ?>"></i>
                                    </a>
                                    <a href="<?= site_url('subjects/edit/' . $subject->id) ?>" class="btn-action-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('subjects/delete/' . $subject->id) ?>" class="btn-action-sm btn-delete" title="Delete" onclick="return confirm('Delete this subject?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-book"></i>
            </div>
            <h3>No subjects found</h3>
            <p>Adjust the filters or add your first subject to get started.</p>
            <a href="<?= site_url('subjects/create') ?>" class="btn-add-subject">
                <i class="bi bi-plus-lg"></i> Add Subject
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.subjects-page { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 2rem 1rem; }
.page-header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.page-title { font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0; }
.page-subtitle { font-size: 1rem; color: #64748b; margin: 0.5rem 0 0 0; }
.btn-add-subject { padding: 0.875rem 1.75rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease; }
.btn-add-subject:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }

.filter-bar-compact { display: flex; gap: 0.5rem; align-items: center; padding: 1rem; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 1rem; flex-wrap: wrap; }
.form-select-sm { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.5rem 0.75rem; font-size: 0.85rem; min-width: 150px; }
.btn-filter-sm { padding: 0.5rem 0.75rem; background: #6366f1; color: white; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; }
.btn-filter-sm:hover { background: #4f46e5; }
.btn-reset-sm { padding: 0.5rem 0.75rem; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; text-decoration: none; }
.btn-reset-sm:hover { background: #e2e8f0; }

.stats-grid-compact { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.stat-item-compact { padding: 0.75rem 1rem; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; }
.stat-label-compact { font-size: 0.75rem; color: #64748b; font-weight: 500; }
.stat-item-compact strong { font-size: 1.25rem; color: #1e293b; font-weight: 700; margin-top: 0.25rem; }

.data-table { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
.table { margin: 0; }
.table thead th { background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.875rem 1rem; text-align: left; }
.table tbody td { padding: 0.875rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem; }
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover { background: #f8fafc; }

.code-badge { padding: 0.25rem 0.5rem; background: #eef2ff; color: #3730a3; border-radius: 6px; font-size: 0.75rem; font-weight: 700; }
.lesson-count { padding: 0.25rem 0.5rem; background: #dcfce7; color: #166534; border-radius: 6px; font-size: 0.75rem; font-weight: 700; }
.text-muted { color: #94a3b8; }

.table-actions { display: flex; gap: 0.25rem; }
.btn-action-sm { width: 32px; height: 32px; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; }
.btn-action-sm:hover { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
.btn-action-sm.btn-delete { color: #ef4444; }
.btn-action-sm.btn-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }

.empty-state { text-align: center; padding: 4rem 2rem; background: #f8fafc; border-radius: 12px; border: 2px dashed #e2e8f0; }
.empty-icon { width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
.empty-icon i { font-size: 2.5rem; color: #64748b; }
.empty-state h3 { font-size: 1.25rem; color: #1e293b; margin: 0 0 0.5rem 0; }
.empty-state p { margin: 0 0 1.5rem 0; color: #64748b; }

@media (max-width: 768px) {
    .page-header { flex-direction: column; align-items: stretch; }
    .filter-bar-compact { flex-direction: column; align-items: stretch; }
    .form-select-sm { min-width: 100%; }
    .stats-grid-compact { grid-template-columns: 1fr 1fr; }
    .table thead th, .table tbody td { padding: 0.5rem; font-size: 0.75rem; }
}
</style>
