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
            <a href="<?= site_url('course/content/' . $section->subject_id . '?edit=1') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Course
            </a>
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
                        <th>Email</th>
                        <th>Enrolled Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student->name) ?></td>
                            <td><?= htmlspecialchars($student->email) ?></td>
                            <td><?= htmlspecialchars($student->enrolled_date) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
