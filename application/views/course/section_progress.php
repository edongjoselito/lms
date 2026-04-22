<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="javascript:history.back()" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-graph-up me-2"></i>Section Progress: <?= $section->section_name ?></h5>
            </div>
            <?php if (empty($students)): ?>
                <div class="p-5 text-center" style="color:#94a3b8;">
                    <i class="bi bi-graph-up" style="font-size:4rem;display:block;margin-bottom:1.5rem;"></i>
                    <h5 style="color:#64748b;margin-bottom:1rem;">No Students Enrolled</h5>
                    <p style="max-width:400px;margin:0 auto;">There are no students enrolled in this section yet.</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Lessons Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student->name ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student->email ?? 'N/A') ?></td>
                                <td>
                                    <div class="progress" style="height:10px;">
                                        <div class="progress-bar" role="progressbar" style="width:<?= $student->progress_percent ?? 0 ?>%;" aria-valuenow="<?= $student->progress_percent ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted"><?= $student->progress_percent ?? 0 ?>%</small>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($student->progress_percent ?? 0) >= 100 ? 'success' : (($student->progress_percent ?? 0) > 0 ? 'warning' : 'secondary') ?>">
                                        <?= ($student->progress_percent ?? 0) >= 100 ? 'Completed' : (($student->progress_percent ?? 0) > 0 ? 'In Progress' : 'Not Started') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($student->completed_lessons)): ?>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#lessonModal<?= md5($student->email) ?>">
                                            <?= count($student->completed_lessons) ?> / <?= count($student->all_lessons) ?> Lessons
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">0 / <?= count($student->all_lessons) ?> Lessons</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Lesson Details Modals -->
                <?php foreach ($students as $student): ?>
                    <?php if (!empty($student->completed_lessons)): ?>
                        <div class="modal fade" id="lessonModal<?= md5($student->email) ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Completed Lessons: <?= htmlspecialchars($student->name) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Module</th>
                                                        <th>Lesson</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($student->all_lessons as $lesson): ?>
                                                        <?php
                                                            $is_completed = false;
                                                            foreach ($student->completed_lessons as $completed) {
                                                                if ($completed->title === $lesson->title && $completed->module_title === $lesson->module_title) {
                                                                    $is_completed = true;
                                                                    break;
                                                                }
                                                            }
                                                        ?>
                                                        <tr class="<?= $is_completed ? 'table-success' : 'table-secondary' ?>">
                                                            <td><?= htmlspecialchars($lesson->module_title) ?></td>
                                                            <td><?= htmlspecialchars($lesson->title) ?></td>
                                                            <td>
                                                                <?php if ($is_completed): ?>
                                                                    <span class="badge bg-success">Completed</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">Not Started</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
