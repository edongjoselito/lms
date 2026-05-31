<div class="data-table">
    <div class="table-header">
        <div>
            <div class="text-muted small mb-1">Section</div>
            <h5 class="mb-2"><?= htmlspecialchars($section->name) ?></h5>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-light text-dark border"><?= count($students) ?> Students</span>
            </div>
        </div>
        <div>
            <?php if (isset($subject_id) && $subject_id): ?>
                <a href="<?= site_url('course/content/' . $subject_id . '?edit=1') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Course
                </a>
            <?php else: ?>
                <a href="<?= site_url('academic/sections') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Sections
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="p-4">
        <?php if (empty($students)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-1"></i>No students enrolled in this section yet.
            </div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Student No.</th>
                        <th>Enrolled Date</th>
                        <?php if (isset($subject_id) && $subject_id): ?>
                            <th>Progress</th>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student->name) ?></td>
                            <td><?= isset($student->student_number) ? htmlspecialchars($student->student_number) : '-' ?></td>
                            <td><?= htmlspecialchars($student->enrolled_date) ?></td>
                            <?php if (isset($subject_id) && $subject_id): ?>
                                <td>
                                    <div class="progress" style="height: 20px; background-color: #e9ecef;">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $student->progress_percent ?>%; background-color: #28a745;" aria-valuenow="<?= $student->progress_percent ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= $student->progress_percent ?>%
                                        </div>
                                    </div>
                                    <small class="text-muted"><?= $student->completed_items ?> / <?= $student->total_items ?> items</small>
                                </td>
                                <td>
                                    <a href="<?= site_url('academic/student_subject_records/' . $section->id . '/' . $student->user_id . '?subject_id=' . $subject_id) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-folder2-open me-1"></i> View Records
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
