<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-book-fill me-2"></i>Subjects</h5>
        <div class="d-flex gap-2 align-items-center">
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= site_url('academic/subjects') ?>" class="btn <?= empty($filter_type) ? 'btn-dark' : 'btn-outline-secondary' ?>" style="border-radius:8px 0 0 8px;font-size:0.8rem;">All</a>
                <a href="<?= site_url('academic/subjects?system_type=deped') ?>" class="btn <?= ($filter_type == 'deped') ? 'btn-dark' : 'btn-outline-secondary' ?>" style="font-size:0.8rem;">DepEd</a>
                <a href="<?= site_url('academic/subjects?system_type=ched') ?>" class="btn <?= ($filter_type == 'ched') ? 'btn-dark' : 'btn-outline-secondary' ?>" style="border-radius:0 8px 8px 0;font-size:0.8rem;">CHED</a>
            </div>
            <a href="<?= site_url('academic/create_subject') ?>" class="btn-primary-custom">
                <i class="bi bi-plus-lg"></i> Add Subject
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Subject Name</th>
                    <th>System</th>
                    <th>Level / Program</th>
                    <th>Units</th>
                    <th style="width:100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $s): ?>
                        <tr>
                            <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($s->name) ?></td>
                            <td>
                                <span class="badge-role <?= ($s->system_type == 'ched') ? 'badge-admin' : 'badge-user' ?>">
                                    <?= strtoupper($s->system_type) ?>
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?php if ($s->system_type == 'deped'): ?>
                                    <?= isset($s->grade_level_name) ? $s->grade_level_name : '-' ?>
                                <?php else: ?>
                                    <?= isset($s->program_code) ? $s->program_code : '-' ?><?= $s->year_level ? ' / Year ' . $s->year_level : '' ?>
                                <?php endif; ?>
                            </td>
                            <td style="color:#64748b;"><?= $s->units ?: '-' ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('academic/edit_subject/' . $s->id) ?>" class="btn-action" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5" style="color:#94a3b8;">No subjects found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
