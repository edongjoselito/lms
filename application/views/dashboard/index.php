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
                    <div class="metric-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
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
                    <div class="metric-icon" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
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
                    <div class="metric-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);">
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
                    <div class="metric-icon" style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);">
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
                            <div class="distribution-progress" style="width: <?= $total_schools > 0 ? round(($school_types['deped'] / $total_schools) * 100) : 0 ?>%; background: #3b82f6;"></div>
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
                                            $type_colors = array('deped' => '#3b82f6', 'ched' => '#10b981', 'tesda' => '#8b5cf6');
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
            margin-bottom: 1.5rem;
            padding: 0.5rem 0;
        }

        .hero-content {
            padding: 0;
        }

        .hero-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            color: #64748b;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Metric Cards */
        .metric-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
            height: 100%;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }

        .metric-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        .metric-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .metric-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.05rem;
        }

        .metric-trend {
            font-size: 0.72rem;
            font-weight: 500;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
        }

        .metric-trend.positive {
            background: #ecfdf5;
            color: #047857;
        }

        .metric-trend.neutral {
            background: #f5f5f7;
            color: #6b7280;
        }

        .metric-trend.warning {
            background: #fffbeb;
            color: #b45309;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.1;
            margin-bottom: 0.35rem;
            letter-spacing: -0.035em;
            font-variant-numeric: tabular-nums;
        }

        .metric-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #0a0a0a;
            margin-bottom: 0.2rem;
            letter-spacing: -0.005em;
        }

        .metric-sub {
            font-size: 0.78rem;
            color: #9ca3af;
            letter-spacing: -0.005em;
        }

        /* Distribution Card */
        .distribution-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }

        .distribution-card .card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid #f2f2f4;
            background: transparent;
        }

        .distribution-card .card-header h5 {
            font-size: 0.925rem;
            font-weight: 600;
            color: #0a0a0a;
            margin: 0;
            letter-spacing: -0.01em;
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
            margin-bottom: 0.55rem;
        }

        .distribution-label {
            font-size: 0.825rem;
            font-weight: 500;
            color: #0a0a0a;
            letter-spacing: -0.005em;
        }

        .distribution-value {
            font-size: 0.825rem;
            color: #9ca3af;
            font-variant-numeric: tabular-nums;
        }

        .distribution-bar {
            height: 6px;
            background: #f5f5f7;
            border-radius: 3px;
            overflow: hidden;
        }

        .distribution-progress {
            height: 100%;
            border-radius: 3px;
            transition: width 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Insights Box */
        .insights-box {
            background: #fafafa;
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            gap: 0.875rem;
            border: 1px solid #ececee;
        }

        .insights-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .insights-content h6 {
            font-size: 0.825rem;
            font-weight: 600;
            color: #0a0a0a;
            margin-bottom: 0.25rem;
            letter-spacing: -0.005em;
        }

        .insights-content p {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0;
            line-height: 1.55;
            letter-spacing: -0.005em;
        }

        /* Data Table */
        .data-table .table-header {
            padding: 1.1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f2f2f4;
        }

        .data-table .table-header h5 {
            font-size: 0.925rem;
            font-weight: 600;
            color: #0a0a0a;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .btn-view-all {
            font-size: 0.8rem;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.35rem 0.6rem;
            border-radius: 7px;
            transition: all 0.15s ease;
        }

        .btn-view-all:hover {
            color: #0a0a0a;
            background: #f5f5f7;
        }

        .school-name {
            font-weight: 500;
            color: #1e293b;
            font-size: 0.875rem;
            letter-spacing: -0.005em;
        }

        .school-id {
            font-size: 0.72rem;
            color: #9ca3af;
            margin-top: 1px;
        }

        .school-type-badge {
            padding: 0.2rem 0.55rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0;
        }

        .admin-avatars {
            display: flex;
        }

        .admin-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #0a0a0a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.68rem;
            font-weight: 600;
            border: 2px solid #fff;
            margin-left: -6px;
        }

        .admin-avatar:first-child {
            margin-left: 0;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.78rem;
            font-weight: 500;
        }

        .status-indicator.active {
            color: #059669;
        }

        .status-indicator.inactive {
            color: #9ca3af;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .table-footer {
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #f2f2f4;
        }

        .pagination-info {
            font-size: 0.78rem;
            color: #6b7280;
        }

        .pagination-buttons {
            display: flex;
            gap: 0.4rem;
        }

        .btn-pagination {
            padding: 0.35rem 0.7rem;
            border: 1px solid #ececee;
            border-radius: 7px;
            background: #fff;
            color: #6b7280;
            font-size: 0.78rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.15s ease;
        }

        .btn-pagination:hover:not(:disabled) {
            border-color: #d1d5db;
            color: #0a0a0a;
            background: #fafafa;
        }

        .btn-pagination:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Context Switcher Banner */
        .context-switcher-banner {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 16px;
            padding: 1.5rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 2rem;
            box-shadow: 0 4px 24px rgba(30, 41, 59, 0.15);
        }

        .banner-content {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .banner-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e5e7eb;
            font-size: 1.1rem;
        }

        .banner-text h6 {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            letter-spacing: -0.01em;
        }

        .banner-text p {
            color: #9ca3af;
            font-size: 0.82rem;
            margin: 0;
            letter-spacing: -0.005em;
        }

        .banner-actions {
            display: flex;
            gap: 0.55rem;
        }

        .btn-banner-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1rem;
            background: #ffffff;
            color: #0a0a0a;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            letter-spacing: -0.005em;
        }

        .btn-banner-primary:hover {
            background: #f5f5f7;
            color: #0a0a0a;
            transform: translateY(-1px);
        }

        .btn-banner-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1rem;
            background: transparent;
            color: #e5e7eb;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            letter-spacing: -0.005em;
        }

        .btn-banner-outline:hover {
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .context-switcher-banner {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
                align-items: stretch;
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