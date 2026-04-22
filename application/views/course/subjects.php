<div class="course-subjects-page">
    <a href="<?= site_url('course') ?>" class="page-back">
        <i class="bi bi-arrow-left"></i> Dashboard
    </a>

    <div class="subjects-hero">
        <div>
            <span class="page-eyebrow">Course content</span>
            <h1 class="page-title">Manage Subjects & Content</h1>
            <p class="page-subtitle"><?= count($subjects) ?> subject<?= count($subjects) == 1 ? '' : 's' ?> ready for modules and lessons</p>
        </div>
        <a href="<?= site_url('academic/subjects') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i>Add Subject
        </a>
    </div>

    <div class="subject-table-card">
        <div class="table-responsive">
            <table class="table course-subject-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Grade Level/Program</th>
                        <th>Semester</th>
                        <th style="width:150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($subjects)): ?>
                        <?php foreach ($subjects as $s): ?>
                            <tr>
                                <td><span class="subject-code"><?= htmlspecialchars($s->code) ?></span></td>
                                <td class="subject-title"><?= htmlspecialchars($s->description) ?></td>
                                <td class="subject-muted">
                                    <?php if ($s->grade_level_id): ?>
                                        <?php foreach ($grade_levels as $gl): ?>
                                            <?php if ($gl->id == $s->grade_level_id): ?>
                                                <?= htmlspecialchars($gl->name) ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php elseif ($s->program_id): ?>
                                        <?php foreach ($programs as $p): ?>
                                            <?php if ($p->id == $s->program_id): ?>
                                                <?= htmlspecialchars($p->code) ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="subject-muted">
                                    <?php if ($s->semester_type == '1st_sem'): ?>First Semester
                                    <?php elseif ($s->semester_type == '2nd_sem'): ?>Second Semester
                                    <?php else: ?>-<?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= site_url('course/content/' . $s->id) ?>" class="icon-action primary" title="Manage Content" aria-label="Manage Content">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="<?= site_url('academic/edit_subject/' . $s->id) ?>" class="icon-action" title="Edit Subject" aria-label="Edit Subject">
                                        <i class="bi bi-gear-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-folder2-open"></i>
                                    <h3>No subjects found</h3>
                                    <p>Add a subject before building content.</p>
                                    <a href="<?= site_url('academic/subjects') ?>" class="btn-primary-custom">
                                        <i class="bi bi-plus-lg"></i>Add Subject
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.course-subjects-page {
    padding: 0.5rem 0 1.5rem;
}

.page-back {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 1rem;
    color: #2f6fed;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 700;
}

.page-back:hover {
    color: #1f5ecf;
}

.subjects-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.25rem 0 0.5rem;
    margin-bottom: 1.25rem;
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

.subject-table-card {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.course-subject-table {
    margin: 0;
}

.course-subject-table thead th {
    background: #f7f9fc;
    border-bottom: 1px solid #e4e7ec;
    color: #667085;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0;
    padding: 0.9rem 1.1rem;
}

.course-subject-table tbody td {
    padding: 1rem 1.1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f5;
    font-size: 0.9rem;
}

.course-subject-table tbody tr:last-child td {
    border-bottom: none;
}

.course-subject-table tbody tr:hover {
    background: #f8fafc;
}

.subject-code {
    display: inline-flex;
    padding: 0.32rem 0.65rem;
    border-radius: 10px;
    background: #edf4ff;
    color: #2f6fed;
    font-size: 0.78rem;
    font-weight: 700;
}

.subject-title {
    color: #182033;
    font-weight: 700;
}

.subject-muted {
    color: #667085;
}

.icon-action {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    border: 1px solid #e4e7ec;
    background: #fff;
    color: #667085;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
}

.icon-action + .icon-action {
    margin-left: 0.35rem;
}

.icon-action.primary {
    border-color: #d7e6ff;
    background: #edf4ff;
    color: #2f6fed;
}

.icon-action:hover {
    background: #2f6fed;
    color: #fff;
    border-color: #2f6fed;
}

.empty-state {
    padding: 2.5rem 1rem;
    text-align: center;
    color: #667085;
}

.empty-state i {
    font-size: 2.4rem;
    color: #98a2b3;
    display: block;
    margin-bottom: 0.75rem;
}

.empty-state h3 {
    color: #182033;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 0.35rem 0;
}

.empty-state p {
    margin: 0 0 1rem 0;
}

@media (max-width: 768px) {
    .subjects-hero {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
