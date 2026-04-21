<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-list-ol me-2"></i>K-12 Grade Levels</h5>
        <a href="<?= site_url('academic/create_grade_level') ?>" class="btn-primary-custom btn-sm">
            <i class="bi bi-plus-lg"></i> Add Grade Level
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Order</th>
                    <th>Subjects</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grade_levels as $gl): ?>
                    <?php $gl_subjects = $this->Academic_model->get_subjects_by_grade_level($gl->id); ?>
                    <tr>
                        <td><span class="badge-role badge-user"><?= $gl->code ?></span></td>
                        <td style="font-weight:600;"><?= $gl->name ?></td>
                        <td style="color:#64748b;"><?= ucfirst(str_replace('_', ' ', $gl->category)) ?></td>
                        <td style="color:#64748b;"><?= $gl->level_order ?></td>
                        <td style="color:#64748b;"><?= count($gl_subjects) ?> subjects</td>
                        <td>
                            <a href="<?= site_url('academic/grade_level_subjects/' . $gl->id) ?>" class="btn-action" title="Manage Subjects">
                                <i class="bi bi-book-fill"></i>
                            </a>
                            <a href="<?= site_url('academic/edit_grade_level/' . $gl->id) ?>" class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="<?= site_url('academic/delete_grade_level/' . $gl->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this grade level?');">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
