<?php if ($this->session->userdata('school_id')): ?>
    <div class="mb-3">
        <a href="<?= site_url('schools/switch_to_platform') ?>" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-size:0.8rem;">
            <i class="bi bi-arrow-left me-1"></i> Back to Platform View
        </a>
    </div>
<?php endif; ?>

<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title">School Management</h1>
        <p class="hero-subtitle">Manage and monitor all educational institutions in your network</p>
    </div>
</div>

<!-- Metrics Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
                    <i class="bi bi-building"></i>
                </div>
                <span class="metric-trend positive">
                    <i class="bi bi-arrow-up"></i> All Time
                </span>
            </div>
            <div class="metric-value"><?= number_format($total_schools) ?></div>
            <div class="metric-label">Total Schools</div>
            <div class="metric-sub"><?= $active_schools ?> active</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <span class="metric-trend positive">
                    <i class="bi bi-arrow-up"></i> Active
                </span>
            </div>
            <div class="metric-value"><?= number_format($active_schools) ?></div>
            <div class="metric-label">Active Schools</div>
            <div class="metric-sub"><?= $total_schools > 0 ? round(($active_schools / $total_schools) * 100) : 0 ?>% of total</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
                    <i class="bi bi-pause-circle"></i>
                </div>
                <span class="metric-trend warning">
                    <i class="bi bi-dash"></i> Inactive
                </span>
            </div>
            <div class="metric-value"><?= number_format($inactive_schools) ?></div>
            <div class="metric-label">Inactive Schools</div>
            <div class="metric-sub">Needs attention</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon" style="background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%);">
                    <i class="bi bi-people"></i>
                </div>
                <span class="metric-trend neutral">
                    <i class="bi bi-dash"></i> Total
                </span>
            </div>
            <div class="metric-value"><?= number_format($total_students) ?></div>
            <div class="metric-label">Total Students</div>
            <div class="metric-sub">Across all schools</div>
        </div>
    </div>
</div>

<!-- Data Section: Two Columns -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-building me-2"></i>All Schools</h5>
                <div class="d-flex gap-2">
                    <?php if ($pending_schools > 0): ?>
                        <a href="<?= site_url('schools/pending') ?>" class="btn btn-warning" style="border-radius:8px;font-size:0.85rem;">
                            <i class="bi bi-clock me-1"></i> Pending (<?= $pending_schools ?>)
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('schools/download_template') ?>" class="btn btn-outline-secondary" style="border-radius:8px;font-size:0.85rem;">
                        <i class="bi bi-download me-1"></i> Template
                    </a>
                    <a href="<?= site_url('schools/create') ?>" class="btn-primary-custom">
                        <i class="bi bi-plus-lg"></i> Add School
                    </a>
                </div>
            </div>
            <div class="table-header" style="background:#f8fafc;padding:1rem;border-bottom:1px solid #e2e8f0;">
                <form action="<?= site_url('schools/bulk_upload') ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                    <label class="mb-0" style="font-weight:600;font-size:0.85rem;color:#475569;">
                        <i class="bi bi-upload me-1"></i> Bulk Upload (CSV):
                    </label>
                    <input type="file" name="csv_file" accept=".csv" class="form-control form-control-sm" style="width:auto;" required>
                    <button type="submit" class="btn btn-success" style="border-radius:8px;font-size:0.85rem;">
                        <i class="bi bi-cloud-upload me-1"></i> Upload
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Users</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($schools)): ?>
                            <?php foreach ($schools as $s): ?>
                                <tr>
                                    <td>
                                        <div class="school-name"><?= htmlspecialchars($s->name) ?></div>
                                        <div class="school-id"><?= $s->school_id_number ?: 'N/A' ?> <?= $s->division ? '· ' . $s->division : '' ?></div>
                                    </td>
                                    <td style="font-weight:600;color:#0f172a;"><?= $s->stats->users ?></td>
                                    <td style="font-weight:600;color:#0f172a;"><?= $s->stats->students ?></td>
                                    <td>
                                        <span class="status-indicator <?= $s->status ? 'active' : 'inactive' ?>">
                                            <span class="status-dot"></span>
                                            <?= $s->status ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-action" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="<?= site_url('schools/switch_school/' . $s->id) ?>"><i class="bi bi-box-arrow-in-right me-2"></i>Enter School</a></li>
                                                <li><a class="dropdown-item" href="<?= site_url('schools/view_users/' . $s->id) ?>"><i class="bi bi-people me-2"></i>View Users</a></li>
                                                <li><a class="dropdown-item" href="<?= site_url('schools/edit/' . $s->id) ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="<?= site_url('schools/delete/' . $s->id) ?>" onclick="return confirm('Delete this school? This will also delete all associated data including users, courses, and enrollments.');"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color:#94a3b8;">
                                    <i class="bi bi-building-x" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                                    No schools yet. Create your first school.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <span class="pagination-info">Showing <?= count($schools) ?> of <?= $total_schools ?> schools</span>
                <div class="pagination-buttons">
                    <button class="btn-pagination" disabled><i class="bi bi-chevron-left"></i> Previous</button>
                    <button class="btn-pagination">Next <i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Context Switcher Banner -->
<div class="context-switcher-banner">
    <div class="banner-content">
        <div class="banner-icon">
            <i class="bi bi-info-circle"></i>
        </div>
        <div class="banner-text">
            <h6>Quick Actions</h6>
            <p>Bulk upload multiple schools using CSV or switch to a specific school to manage its data.</p>
        </div>
    </div>
    <div class="banner-actions">
        <a href="<?= site_url('schools/select') ?>" class="btn-banner-primary">
            <i class="bi bi-building"></i> Select School
        </a>
        <a href="<?= site_url('audit') ?>" class="btn-banner-outline">
            <i class="bi bi-clock-history"></i> View Logs
        </a>
    </div>
</div>

<style>
    /* Dashboard Hero */
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

    /* Metric Cards */
    .metric-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        height: 100%;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }

    .metric-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .metric-trend {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }

    .metric-trend.positive {
        background: #dcfce7;
        color: #15803d;
    }

    .metric-trend.neutral {
        background: #f1f5f9;
        color: #64748b;
    }

    .metric-trend.warning {
        background: #fef3c7;
        color: #b45309;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .metric-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.25rem;
    }

    .metric-sub {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    /* Data Table */
    .data-table {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .data-table .table-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table .table-header h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
    }

    .school-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.9rem;
    }

    .school-id {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-indicator.active {
        color: #10b981;
    }

    .status-indicator.inactive {
        color: #94a3b8;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }

    .btn-action {
        background: none;
        border: none;
        padding: 0.25rem 0.5rem;
        color: #64748b;
        cursor: pointer;
    }

    .btn-action:hover {
        color: #0f172a;
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

    .pagination-buttons {
        display: flex;
        gap: 0.5rem;
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
    }

    .btn-pagination:hover:not(:disabled) {
        border-color: #3b82f6;
        color: #2563eb;
    }

    .btn-pagination:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Context Switcher Banner */
    .context-switcher-banner {
        background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
        border-radius: 16px;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .banner-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .banner-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 1.25rem;
    }

    .banner-text h6 {
        color: #f1f5f9;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .banner-text p {
        color: #94a3b8;
        font-size: 0.85rem;
        margin: 0;
    }

    .banner-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-banner-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #3b82f6;
        color: white;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-banner-primary:hover {
        background: #2563eb;
        color: white;
    }

    .btn-banner-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: transparent;
        color: #94a3b8;
        border: 1px solid #475569;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-banner-outline:hover {
        border-color: #94a3b8;
        color: #f1f5f9;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .context-switcher-banner {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }

        .banner-content {
            flex-direction: column;
        }

        .banner-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-banner-primary,
        .btn-banner-outline {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .metric-value {
            font-size: 1.5rem;
        }
    }
</style>
