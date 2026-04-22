<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title">Manage Users</h1>
        <p class="hero-subtitle">View and manage all user accounts in the system</p>
    </div>
</div>

<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-people-fill me-2"></i>All Users</h5>
        <a href="<?= site_url('users/create') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i>
            Add User
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>School</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.75rem;flex-shrink:0;">
                                        <?= strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#64748b;"><?= htmlspecialchars($user->email) ?></td>
                            <td style="color:#64748b;font-size:0.85rem;"><?= isset($user->school_name) && $user->school_name ? htmlspecialchars($user->school_name) : '<span style="color:#cbd5e1;">—</span>' ?></td>
                            <td>
                                <span class="badge-role <?= (in_array($user->role_slug, array('super_admin', 'school_admin'))) ? 'badge-admin' : 'badge-user' ?>">
                                    <?= $user->role_name ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status <?= ($user->status) ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= ($user->status) ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?= date('M d, Y', strtotime($user->created_at)) ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= site_url('users/edit/' . $user->id) ?>" class="btn-action" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($user->id != $this->session->userdata('user_id')): ?>
                                        <a href="<?= site_url('users/delete/' . $user->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color:#94a3b8;">
                            <i class="bi bi-people" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                            No users found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="table-footer">
            <span class="pagination-info">Showing <?= (($current_page - 1) * $per_page) + 1 ?> - <?= min($current_page * $per_page, $total_users) ?> of <?= $total_users ?> users</span>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="<?= site_url('users?page=' . ($current_page - 1)) ?>" class="btn-pagination">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                <?php else: ?>
                    <button class="btn-pagination" disabled><i class="bi bi-chevron-left"></i> Previous</button>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $current_page): ?>
                        <span class="pagination-page active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= site_url('users?page=' . $i) ?>" class="pagination-page"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= site_url('users?page=' . ($current_page + 1)) ?>" class="btn-pagination">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <button class="btn-pagination" disabled>Next <i class="bi bi-chevron-right"></i></button>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="table-footer">
            <span class="pagination-info">Showing <?= count($users) ?> of <?= $total_users ?> users</span>
        </div>
    <?php endif; ?>
</div>

<style>
    .dashboard-hero {
        margin-bottom: 2rem;
    }

    .hero-content {
        padding: 0.5rem 0;
    }

    .hero-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .hero-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .data-table {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-footer {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        font-size: 0.8rem;
        color: #64748b;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-pagination {
        padding: 0.4rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-pagination:hover:not(:disabled) {
        border-color: #0d9488;
        color: #0d9488;
    }

    .btn-pagination:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-page {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-page:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .pagination-page.active {
        background: #0d9488;
        color: white;
    }
</style>