<div class="row">
    <div class="col-12">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-book-fill me-2"></i>Course Creator Dashboard</h5>
                <span class="badge-role badge-admin"><?= $current_school ? $current_school->name : 'No School' ?></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Stats Cards -->
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-icon" style="background:#dbeafe;color:#1e40af;">
                <i class="bi bi-book"></i>
            </div>
            <div class="stats-info">
                <h3><?= $total_subjects ?></h3>
                <p>Total Subjects</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-icon" style="background:#dcfce7;color:#166534;">
                <i class="bi bi-list-ol"></i>
            </div>
            <div class="stats-info">
                <h3><?= $total_grade_levels ?></h3>
                <p>Grade Levels</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-icon" style="background:#fef3c7;color:#92400e;">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div class="stats-info">
                <h3><?= $total_programs ?></h3>
                <p>Programs</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-lightning-fill me-2"></i>Quick Actions</h5>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="<?= site_url('course/subjects') ?>" class="btn btn-outline-primary" style="text-align:left;">
                        <i class="bi bi-book me-2"></i> Manage Subjects & Content
                    </a>
                    <a href="<?= site_url('academic/subjects') ?>" class="btn btn-outline-secondary" style="text-align:left;">
                        <i class="bi bi-plus-circle me-2"></i> Add New Subject
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Subjects -->
    <div class="col-lg-6">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-clock-history me-2"></i>Subjects Overview</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($subjects)): ?>
                            <?php foreach (array_slice($subjects, 0, 5) as $s): ?>
                                <tr>
                                    <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                                    <td><?= $s->description ?></td>
                                    <td>
                                        <a href="<?= site_url('course/content/' . $s->id) ?>" class="btn-action btn-edit" title="Manage Content">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4" style="color:#94a3b8;">No subjects found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if (count($subjects) > 5): ?>
                <div class="p-2 text-center">
                    <a href="<?= site_url('course/subjects') ?>" style="color:#6366f1;font-size:0.85rem;">View all subjects →</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
