<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>LMS Admin</title>
    <link rel="icon" type="image/png" href="<?= base_url('uploads/icon/favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #ffffff;
            --sidebar-hover: rgba(255, 255, 255, 0.06);
            --sidebar-active: rgba(47, 111, 237, 0.18);
            --primary: #2f6fed;
            --primary-light: #7aa7ff;
            --accent: #10b981;
            --body-bg: #f6f7fb;
            --card-bg: #ffffff;
            --text-primary: #182033;
            --text-secondary: #667085;
            --border-soft: #e4e7ec;
            --shadow-soft: 0 12px 30px rgba(24, 32, 51, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--body-bg);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.5;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.06);
            border-right: 1px solid #e2e8f0;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-brand .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .sidebar-brand .brand-text {
            color: #0f172a;
            font-weight: 600;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
        }

        .sidebar-brand .brand-sub {
            color: #64748b;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }

        .nav-section-title {
            color: #94a3b8;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 1rem 1.25rem 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            color: #475569;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0 0.75rem;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: #0f172a;
            background: #f1f5f9;
        }

        .sidebar-link.active {
            color: #0f172a;
            background: #eff4ff;
            font-weight: 600;
        }

        .sidebar-link.active i {
            color: #0d9488;
        }

        .sidebar-link i {
            font-size: 1.15rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            margin: 0 0.75rem;
            border-radius: 12px;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .sidebar-user:hover {
            background: #f1f5f9;
        }

        .sidebar-user .avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .sidebar-user .avatar.avatar--img {
            object-fit: cover;
            border: 2px solid #e2e8f0;
            display: block;
        }

        .sidebar-user .user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user .user-name {
            color: #0f172a;
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user .user-role {
            color: #64748b;
            font-size: 0.75rem;
            text-transform: capitalize;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 0;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.94);
            border-bottom: 1px solid var(--border-soft);
            padding: 0.875rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: blur(12px);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-left h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            font-size: 1.35rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0;
            transition: all 0.2s ease;
        }

        .menu-toggle:hover {
            background: #f7f9fc;
            border-color: #cfd6e3;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Search Bar */
        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar i {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .search-bar input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.85rem;
            width: 260px;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #0d9488;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .search-bar input::placeholder {
            color: #94a3b8;
        }

        /* Switch School Button */
        .btn-switch-school {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-switch-school:hover {
            border-color: #0d9488;
            color: #0d9488;
            background: rgba(13, 148, 136, 0.05);
        }

        /* Topbar Icon Buttons */
        .topbar-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: #64748b;
            font-size: 1.1rem;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .topbar-icon-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: #fff5f5;
            color: #c24132;
            border: 1px solid #ffd6d1;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #ffe8e5;
            color: #a83225;
        }

        .content-area {
            padding: 2rem;
        }

        /* Cards */
        .stat-card {
            background: var(--card-bg);
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid var(--border-soft);
            transition: all 0.2s ease;
            height: 100%;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .stat-card:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-soft);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-label {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Table */
        .data-table {
            background: var(--card-bg);
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .data-table .table-header {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-soft);
        }

        .data-table .table-header h5 {
            font-weight: 700;
            font-size: 1.05rem;
            margin: 0;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f7f9fc;
            border-bottom: 1px solid var(--border-soft);
            color: var(--text-secondary);
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0;
            padding: 0.875rem 1.5rem;
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            font-size: 0.9rem;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .badge-role {
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-admin {
            background: #ede9fe;
            color: #6d28d9;
        }

        .badge-user {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-active {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            color: var(--text-secondary);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-action:hover {
            background: #f7f9fc;
            color: var(--text-primary);
        }

        .btn-action.btn-delete:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(47, 111, 237, 0.2);
            color: white;
        }

        /* Form styles */
        .form-card {
            background: var(--card-bg);
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            padding: 2rem;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .form-card .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-card .form-control,
        .form-card .form-select {
            border-radius: 12px;
            border: 1px solid var(--border-soft);
            padding: 0.625rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-card .form-control:focus,
        .form-card .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(47, 111, 237, 0.12);
        }

        /* Alert styles */
        .alert-modern {
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
        }

        .alert-success-modern {
            background: #dcfce7;
            color: #15803d;
        }

        .alert-danger-modern {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 18, 27, 0.48);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1rem;
            }

            .topbar {
                padding: 0.875rem 1rem;
            }
        }
    </style>
</head>

<body>

    <?php $this->load->view('includes/sidebar', array(
        'original_role_slug' => isset($original_role_slug) ? $original_role_slug : null,
        'is_student_mode' => isset($is_student_mode) ? $is_student_mode : false,
    )); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle" id="sidebarToggle" onclick="toggleSidebar()" aria-label="Toggle sidebar" title="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1><?= isset($title) ? $title : 'Dashboard' ?></h1>
            </div>
            <div class="topbar-right">
                <?php if (isset($role_slug) && $role_slug === 'super_admin'): ?>
                    <?php
                    $is_school_select_page = ($this->uri->segment(1) == 'schools' && $this->uri->segment(2) == 'select');
                    $has_selected_school = (bool) $this->session->userdata('school_id');
                    ?>
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search schools or admins..." />
                    </div>
                    <!-- Switch School Button -->
                    <?php if ($has_selected_school && !$is_school_select_page): ?>
                        <a href="<?= site_url('schools/select') ?>" class="btn-switch-school">
                            <i class="bi bi-arrow-left-right"></i>
                            Switch School
                        </a>
                    <?php endif; ?>
                    <!-- Notification Icon -->
                    <button type="button" class="topbar-icon-btn" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
                    <!-- Help Icon -->
                    <button type="button" class="topbar-icon-btn" title="Help">
                        <i class="bi bi-question-circle"></i>
                    </button>
                <?php elseif ($this->session->userdata('school_name') && (!isset($role_slug) || $role_slug !== 'student')): ?>
                    <span style="background:#f1f5f9;padding:0.4rem 0.85rem;border-radius:10px;font-size:0.78rem;font-weight:600;color:#475569;margin-right:0.5rem;">
                        <i class="bi bi-building me-1"></i><?= htmlspecialchars($this->session->userdata('school_name')) ?>
                    </span>
                <?php endif; ?>
                <a href="<?= site_url('auth/logout') ?>" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>
            </div>
        </div>

        <div class="content-area">
            <?php if (isset($is_student_mode) && $is_student_mode): ?>
                <div class="alert alert-warning d-flex justify-content-between align-items-center mb-3" style="border-radius:10px;padding:0.75rem 1rem;">
                    <div>
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Student Mode Active:</strong> You are viewing the system as a student would see it.
                    </div>
                    <a href="<?= site_url('mode/toggle') ?>" class="btn btn-sm" style="background:#b45309;color:white;border-radius:8px;padding:0.4rem 0.8rem;font-size:0.85rem;">
                        Exit
                    </a>
                </div>
            <?php endif; ?>
            <?= render_notifications() ?>