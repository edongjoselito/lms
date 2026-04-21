<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-mortarboard-fill me-2"></i>Programs (CHED)</h5>
        <a href="<?= site_url('academic/create_program') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Add Program
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Program Name</th>
                    <th>Degree</th>
                    <th>Units</th>
                    <th>Years</th>
                    <th>Subjects</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($programs)): ?>
                    <?php foreach ($programs as $p): ?>
                        <?php $program_subjects = $this->Academic_model->get_subjects_by_program($p->id); ?>
                        <tr>
                            <td><span class="badge-role badge-admin"><?= $p->code ?></span></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($p->name) ?></td>
                            <td style="color:#64748b;"><?= ucfirst($p->degree_type) ?></td>
                            <td style="color:#64748b;"><?= $p->total_units ?></td>
                            <td style="color:#64748b;"><?= $p->years_to_complete ?></td>
                            <td style="color:#64748b;"><?= count($program_subjects) ?> subjects</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('academic/program_subjects/' . $p->id) ?>" class="btn-action" title="Manage Subjects">
                                        <i class="bi bi-book-fill"></i>
                                    </a>
                                    <a href="<?= site_url('academic/edit_program/' . $p->id) ?>" class="btn-action" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="<?= site_url('academic/delete_program/' . $p->id) ?>" class="btn-action btn-action-danger" title="Delete" onclick="return confirm('Delete this program?');">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-5" style="color:#94a3b8;">No programs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
