<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('course/content/' . $subject->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Course Content
            </a>
        </div>
        <div class="data-table">
            <div class="table-header">
                <div class="text-muted small mb-1"><?= htmlspecialchars($subject->code) ?> &middot; <?= htmlspecialchars($module->title) ?></div>
                <h5><i class="bi bi-check2-circle me-2"></i>Lesson Completions: <?= htmlspecialchars($lesson->title) ?></h5>
                <div class="text-muted small mt-1"><?= count($completions) ?> student<?= count($completions) === 1 ? '' : 's' ?> completed</div>
            </div>
            <?php if (empty($completions)): ?>
                <div class="p-5 text-center" style="color:#94a3b8;">
                    <i class="bi bi-inbox" style="font-size:4rem;display:block;margin-bottom:1.5rem;"></i>
                    <h5 style="color:#64748b;margin-bottom:1rem;">No Completions Yet</h5>
                    <p style="max-width:400px;margin:0 auto;">No students have completed this lesson yet.</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Completed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completions as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c->name ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($c->email ?? 'N/A') ?></td>
                                <td><?= $c->completed_at ? date('M d, Y g:i A', strtotime($c->completed_at)) : 'N/A' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
