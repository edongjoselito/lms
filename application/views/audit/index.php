<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title">Audit Logs</h1>
        <p class="hero-subtitle">Track all system activities and changes across the platform</p>
    </div>
</div>

<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-clock-history me-2"></i>Audit Logs</h5>
        <div class="table-filters">
            <span class="badge bg-secondary">Total: <?= $total_logs ?></span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td style="font-size:0.85rem;">
                                <?= date('M d, Y H:i:s', strtotime($log->created_at)) ?>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($log->user_name) ?></div>
                                <div style="color:#94a3b8;font-size:0.78rem;">User ID: <?= $log->user_id ?></div>
                            </td>
                            <td>
                                <?php
                                $action_badges = array(
                                    'create' => 'badge-success',
                                    'update' => 'badge-warning',
                                    'delete' => 'badge-danger'
                                );
                                ?>
                                <span class="badge-role <?= isset($action_badges[$log->action]) ? $action_badges[$log->action] : 'badge-secondary' ?>">
                                    <?= ucfirst($log->action) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.85rem;"><?= ucfirst($log->entity_type) ?></div>
                                <div style="color:#94a3b8;font-size:0.78rem;"><?= htmlspecialchars($log->entity_name) ?></div>
                            </td>
                            <td style="font-size:0.85rem;color:#64748b;"><?= htmlspecialchars($log->description) ?></td>
                            <td style="font-size:0.8rem;color:#94a3b8;"><?= htmlspecialchars($log->ip_address) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color:#94a3b8;">No audit logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="table-footer">
            <span class="pagination-info">Showing <?= (($current_page - 1) * $per_page) + 1 ?> - <?= min($current_page * $per_page, $total_logs) ?> of <?= $total_logs ?> logs</span>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="<?= site_url('audit?page=' . ($current_page - 1)) ?>" class="btn-pagination">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                <?php else: ?>
                    <button class="btn-pagination" disabled><i class="bi bi-chevron-left"></i> Previous</button>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $current_page): ?>
                        <span class="pagination-page active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= site_url('audit?page=' . $i) ?>" class="pagination-page"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= site_url('audit?page=' . ($current_page + 1)) ?>" class="btn-pagination">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <button class="btn-pagination" disabled>Next <i class="bi bi-chevron-right"></i></button>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="table-footer">
            <span class="pagination-info">Showing <?= count($logs) ?> of <?= $total_logs ?> logs</span>
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