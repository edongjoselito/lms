<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-person-badge me-2"></i>Teachers</h5>
        <div style="display:flex;gap:10px;">
            <a href="<?= site_url('teachers/download_template') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">
                <i class="bi bi-download me-1"></i> Download Template
            </a>
            <a href="<?= site_url('teachers/bulk_upload') ?>" class="btn-primary-custom">
                <i class="bi bi-upload me-1"></i> Bulk Upload
            </a>
            <a href="<?= site_url('teachers/create') ?>" class="btn-primary-custom">
                <i class="bi bi-plus-lg"></i> Add Teacher
            </a>
        </div>
    </div>
            
            <?php if (empty($teachers)): ?>
                <div class="text-center py-5" style="color:#94a3b8;">
                    <i class="bi bi-person-badge" style="font-size:4rem;display:block;margin-bottom:1.5rem;"></i>
                    <h5 style="color:#64748b;margin-bottom:0.5rem;">No Teachers Yet</h5>
                </div>
            <?php else: ?>
                <div class="table-responsive" style="overflow:visible;">
                    <table class="table table-hover" style="margin:0;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">ID Number</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Name</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Email</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Status</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($teachers as $t): ?>
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:0.75rem 1rem;font-weight:500;"><?= isset($t->IDNumber) ? htmlspecialchars($t->IDNumber) : (isset($t->id) ? htmlspecialchars($t->id) : '-') ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?= htmlspecialchars($t->last_name . ', ' . $t->first_name) ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;"><?= isset($t->email) ? htmlspecialchars($t->email) : '-' ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?php if ($t->user_status == 1): ?>
                                            <span style="background:#dcfce7;color:#15803d;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:500;">Active</span>
                                        <?php else: ?>
                                            <span style="background:#fee2e2;color:#dc2626;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:500;">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;text-align:right;">
                                        <div class="dropdown" style="display:inline-block;">
                                            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown" style="padding:0.25rem 0.5rem;">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="z-index:9999;">
                                                <li><a class="dropdown-item" href="<?= site_url('teachers/edit/' . (isset($t->IDNumber) ? $t->IDNumber : $t->id)) ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item text-danger" href="<?= site_url('teachers/delete/' . (isset($t->IDNumber) ? $t->IDNumber : $t->id)) ?>" onclick="return confirm('Delete this teacher? This will also delete the associated user account.');"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
