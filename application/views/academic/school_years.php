<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-calendar3 me-2"></i>School Years</h5>
        <a href="<?= site_url('academic/create_school_year') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Add School Year
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Status</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($school_years)): ?>
                    <?php foreach ($school_years as $sy): ?>
                        <tr>
                            <td style="font-weight:600;"><?= $sy->year_start ?> - <?= $sy->year_end ?></td>
                            <td>
                                <?php if ($sy->is_active): ?>
                                    <span class="badge-status badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge-status badge-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$sy->is_active): ?>
                                    <a href="<?= site_url('academic/activate_school_year/' . $sy->id) ?>" class="btn-action" title="Set Active" onclick="return confirm('Activate this school year?');">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center py-5" style="color:#94a3b8;">No school years found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
