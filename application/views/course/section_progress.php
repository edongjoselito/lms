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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
