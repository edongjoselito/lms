<?php if (isset($is_platform_view) && $is_platform_view): ?>
    <!-- Hero Section -->
    <div class="dashboard-hero">
        <div class="hero-content">
            <h1 class="hero-title">Platform Overview</h1>
            <p class="hero-subtitle">Monitor your educational network across all schools</p>
        </div>
    </div>

    <!-- Metrics Cards Row -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                        <i class="bi bi-building"></i>
                    </div>
                    <span class="metric-trend positive">
                        <i class="bi bi-arrow-up"></i> 12%
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
                    <div class="metric-icon" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);">
                        <i class="bi bi-people"></i>
                    </div>
                    <span class="metric-trend positive">
                        <i class="bi bi-arrow-up"></i> 5.2%
                    </span>
                </div>
                <div class="metric-value"><?= number_format($total_students) ?></div>
                <div class="metric-label">Active Students</div>
                <div class="metric-sub"><?= number_format($total_users) ?> total users</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">
                        <i class="bi bi-book"></i>
                    </div>
                    <span class="metric-trend neutral">
                        <i class="bi bi-dash"></i> Stable
                    </span>
                </div>
                <div class="metric-value"><?= number_format($total_courses) ?></div>
                <div class="metric-label">Courses Created</div>
                <div class="metric-sub">Across all schools</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <span class="metric-trend warning">
                        <i class="bi bi-exclamation-triangle"></i> Peak
                    </span>
                </div>
                <div class="metric-value"><?= number_format($active_sessions) ?></div>
                <div class="metric-label">Active Sessions</div>
                <div class="metric-sub">Currently online</div>
            </div>
        </div>
    </div>

    <!-- Data Section: Two Columns -->
    <div class="row g-4 mb-4">
        <!-- Left Column: Distribution & Insights -->
        <div class="col-lg-4">
            <!-- School Type Distribution Card -->
            <div class="distribution-card">
                <div class="card-header">
                    <h5><i class="bi bi-pie-chart me-2"></i>School Distribution</h5>
                </div>
                <div class="distribution-content">
                    <div class="distribution-item">
                        <div class="distribution-info">
                            <span class="distribution-label">DepEd</span>
                            <span class="distribution-value"><?= $school_types['deped'] ?> schools</span>
                        </div>
                        <div class="distribution-bar">
                            <div class="distribution-progress" style="width: <?= $total_schools > 0 ? round(($school_types['deped'] / $total_schools) * 100) : 0 ?>%; background: #0f172a;"></div>
                        </div>
                    </div>
                    <div class="distribution-item">
                        <div class="distribution-info">
                            <span class="distribution-label">CHED</span>
                            <span class="distribution-value"><?= $school_types['ched'] ?> schools</span>
                        </div>
                        <div class="distribution-bar">
                            <div class="distribution-progress" style="width: <?= $total_schools > 0 ? round(($school_types['ched'] / $total_schools) * 100) : 0 ?>%; background: #0d9488;"></div>
                        </div>
                    </div>
                    <div class="distribution-item">
                        <div class="distribution-info">
                            <span class="distribution-label">TESDA</span>
                            <span class="distribution-value"><?= $school_types['tesda'] ?> schools</span>
                        </div>
                        <div class="distribution-bar">
                            <div class="distribution-progress" style="width: <?= $total_schools > 0 ? round(($school_types['tesda'] / $total_schools) * 100) : 0 ?>%; background: #6366f1;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insights Box -->
            <div class="insights-box">
                <div class="insights-icon">
                    <i class="bi bi-lightbulb"></i>
                </div>
                <div class="insights-content">
                    <h6>Insight</h6>
                    <p>TESDA enrollment has increased by 23% this quarter. Consider adding more technical vocational courses.</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Schools Table -->
        <div class="col-lg-8">
            <div class="data-table">
                <div class="table-header">
                    <h5><i class="bi bi-building me-2"></i>Recent Schools</h5>
                    <a href="<?= site_url('schools') ?>" class="btn-view-all">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>Type</th>
                                <th>Admins</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($recent_schools) && count($recent_schools) > 0): ?>
                                <?php foreach ($recent_schools as $school): ?>
                                    <tr>
                                        <td>
                                            <div class="school-name"><?= htmlspecialchars($school->name ?? 'N/A') ?></div>
                                            <div class="school-id"><?= htmlspecialchars($school->school_id_number ?? 'N/A') ?></div>
                                        </td>
                                        <td>
                                            <?php
                                            $type_colors = array('deped' => '#0f172a', 'ched' => '#0d9488', 'tesda' => '#6366f1');
                                            $type_labels = array('deped' => 'DepEd', 'ched' => 'CHED', 'tesda' => 'TESDA');
                                            $type = isset($school->type) ? $school->type : 'deped';
                                            ?>
                                            <span class="school-type-badge" style="background: <?= isset($type_colors[$type]) ? $type_colors[$type] : '#64748b' ?>; color: white;">
                                                <?= isset($type_labels[$type]) ? $type_labels[$type] : ucfirst($type) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="admin-avatars">
                                                <div class="admin-avatar">A</div>
                                                <div class="admin-avatar">B</div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-indicator <?= $school->status ? 'active' : 'inactive' ?>">
                                                <span class="status-dot"></span>
                                                <?= $school->status ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn-action" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="<?= site_url('schools/edit/' . $school->id) ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                    <li><a class="dropdown-item" href="<?= site_url('schools/switch_school/' . $school->id) ?>"><i class="bi bi-box-arrow-in-right me-2"></i>Switch to</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No schools found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span class="pagination-info">Showing <?= count($recent_schools) ?> of <?= $total_schools ?> schools</span>
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
                <h6>Switching Context?</h6>
                <p>As a Super Admin, you can switch to any school instance to manage specific school data, or view system-wide logs.</p>
            </div>
        </div>
        <div class="banner-actions">
            <a href="<?= site_url('schools/select') ?>" class="btn-banner-primary">
                <i class="bi bi-building"></i> Select School Instance
            </a>
            <a href="<?= site_url('audit') ?>" class="btn-banner-outline">
                <i class="bi bi-clock-history"></i> System Logs
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

        /* Distribution Card */
        .distribution-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .distribution-card .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .distribution-card .card-header h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .distribution-content {
            padding: 1.5rem;
        }

        .distribution-item {
            margin-bottom: 1.25rem;
        }

        .distribution-item:last-child {
            margin-bottom: 0;
        }

        .distribution-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .distribution-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
        }

        .distribution-value {
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .distribution-bar {
            height: 8px;
            background: #f1f5f9;
            border-radius: 4px;
            overflow: hidden;
        }

        .distribution-progress {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Insights Box */
        .insights-box {
            background: linear-gradient(135deg, #eff4ff 0%, #dbeafe 100%);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            gap: 1rem;
            border: 1px solid #bfdbfe;
        }

        .insights-icon {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .insights-content h6 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 0.25rem;
        }

        .insights-content p {
            font-size: 0.8rem;
            color: #3b82f6;
            margin: 0;
            line-height: 1.5;
        }

        /* Data Table */
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

        .btn-view-all {
            font-size: 0.85rem;
            font-weight: 500;
            color: #0d9488;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-view-all:hover {
            color: #0f766e;
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

        .school-type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .admin-avatars {
            display: flex;
            gap: -8px;
        }

        .admin-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid #fff;
            margin-left: -8px;
        }

        .admin-avatar:first-child {
            margin-left: 0;
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
            border-color: #0d9488;
            color: #0d9488;
        }

        .btn-pagination:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Context Switcher Banner */
        .context-switcher-banner {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
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
            background: #0d9488;
            color: white;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-banner-primary:hover {
            background: #0f766e;
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
            .search-bar {
                display: none;
            }

            .btn-switch-school span {
                display: none;
            }
        }
    </style>
<?php else: ?>
    <?php $sy = isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A'; ?>
    <div class="mb-3">
        <span class="badge-role badge-admin"><i class="bi bi-calendar3 me-1"></i>S.Y. <?= $sy ?></span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #ede9fe; color: #6d28d9;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value"><?= $total_users ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div class="stat-value"><?= isset($total_teachers) ? $total_teachers : 0 ?></div>
                <div class="stat-label">Teachers</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-value"><?= isset($total_subjects) ? $total_subjects : 0 ?></div>
                <div class="stat-label">Total Subjects</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-value"><?= isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A' ?></div>
                <div class="stat-label">School Year</div>
            </div>
        </div>
    </div>

<?php endif; ?>