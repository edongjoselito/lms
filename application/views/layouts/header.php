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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f0f4f8;
            --sidebar-active: #e8edf2;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #dbeafe;
            --accent: #0ea5e9;
            --body-bg: #e5eaf0;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-tertiary: #94a3b8;
            --text-muted: #cbd5e1;
            --border-soft: #e2e8f0;
            --border-hairline: #f1f5f9;
            --shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.06);
            --shadow-soft: 0 2px 12px rgba(15, 23, 42, 0.06), 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-medium: 0 4px 20px rgba(15, 23, 42, 0.08), 0 1px 4px rgba(15, 23, 42, 0.04);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'SF Pro Display', 'Segoe UI', Roboto, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        body {
            font-family: var(--font-sans);
            background: var(--body-bg);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.5;
            font-feature-settings: 'cv11', 'ss01', 'ss03';
            letter-spacing: -0.01em;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            letter-spacing: -0.02em;
            color: var(--text-primary);
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
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            border-right: 1px solid var(--border-soft);
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
        }

        .sidebar-brand .brand-text {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            color: var(--text-tertiary);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0;
            text-transform: none;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0.5rem 0.625rem 1rem;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 3px;
        }

        .nav-section-title {
            color: var(--text-tertiary);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 1.1rem 0.875rem 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.5rem 0.75rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 1px 0;
            transition: background 0.15s ease, color 0.15s ease;
            letter-spacing: -0.005em;
        }

        .sidebar-link:hover {
            color: var(--text-primary);
            background: var(--sidebar-hover);
        }

        .sidebar-link.active {
            color: var(--primary);
            background: var(--primary-light);
            font-weight: 600;
        }

        .sidebar-link.active i {
            color: var(--primary);
        }

        .sidebar-link i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            color: var(--text-tertiary);
            transition: color 0.15s ease;
        }

        .sidebar-link:hover i {
            color: var(--text-secondary);
        }

        .sidebar-footer {
            padding: 0.75rem;
            border-top: 1px solid var(--border-soft);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.15s ease;
        }

        .sidebar-user:hover {
            background: var(--sidebar-hover);
        }

        .sidebar-user .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            flex-shrink: 0;
            letter-spacing: 0;
        }

        .sidebar-user .avatar.avatar--img {
            object-fit: cover;
            border: none;
            display: block;
        }

        .sidebar-user .user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user .user-name {
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: -0.01em;
        }

        .sidebar-user .user-role {
            color: var(--text-tertiary);
            font-size: 0.72rem;
            text-transform: capitalize;
            margin-top: 1px;
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
            background: rgba(255, 255, 255, 0.75);
            border-bottom: 1px solid var(--border-soft);
            padding: 0.875rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: saturate(200%) blur(20px);
            -webkit-backdrop-filter: saturate(200%) blur(20px);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .topbar-left h1 {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            letter-spacing: -0.02em;
        }

        .menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            background: transparent;
            border: 1px solid var(--border-soft);
            border-radius: 8px;
            font-size: 1.1rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0;
            transition: all 0.15s ease;
        }

        .menu-toggle:hover {
            background: #f5f5f7;
            color: var(--text-primary);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Search Bar */
        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar i {
            position: absolute;
            left: 12px;
            color: var(--text-tertiary);
            font-size: 0.85rem;
        }

        .search-bar input {
            padding: 0.5rem 0.875rem 0.5rem 2.2rem;
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            font-size: 0.85rem;
            font-family: var(--font-sans);
            width: 260px;
            background: #ffffff;
            color: var(--text-primary);
            transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-bar input::placeholder {
            color: var(--text-tertiary);
        }

        /* Switch School Button */
        .btn-switch-school {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.875rem;
            border: 1px solid var(--border-soft);
            border-radius: 8px;
            background: #fff;
            color: var(--text-secondary);
            font-size: 0.825rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-switch-school:hover {
            border-color: #d1d5db;
            color: var(--text-primary);
            background: #f9fafb;
        }

        /* Topbar Icon Buttons */
        .topbar-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 1rem;
            cursor: pointer;
            position: relative;
            transition: all 0.15s ease;
        }

        .topbar-icon-btn:hover {
            background: #f5f5f7;
            color: var(--text-primary);
        }

        .notification-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 6px;
            height: 6px;
            background: #ff3b30;
            border-radius: 50%;
            border: 2px solid rgba(250, 250, 250, 0.9);
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.875rem;
            border-radius: 8px;
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-soft);
            font-size: 0.825rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-logout:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .content-area {
            padding: 1.75rem 2rem 2.5rem;
            max-width: 1280px;
            margin: 0 auto;
        }

        /* Cards */
        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--border-soft);
            transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
            height: 100%;
            box-shadow: var(--shadow-xs);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
            border-color: #cbd5e1;
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.1;
            margin-bottom: 0.25rem;
            letter-spacing: -0.03em;
            font-variant-numeric: tabular-nums;
        }

        .stat-card .stat-label {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: -0.005em;
        }

        /* Table */
        .data-table {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-soft);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
        }

        .data-table .table-header {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-hairline);
        }

        .data-table .table-header h5 {
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: transparent;
            border-bottom: 1px solid var(--border-hairline);
            color: var(--text-tertiary);
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1.5rem;
        }

        .table tbody td {
            padding: 0.95rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-hairline);
            font-size: 0.875rem;
            color: var(--text-primary);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: background 0.12s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .badge-role {
            padding: 0.2rem 0.55rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0;
            display: inline-flex;
            align-items: center;
        }

        .badge-admin {
            background: #f4f0ff;
            color: #6d28d9;
        }

        .badge-user {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-status {
            padding: 0.2rem 0.55rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .badge-active {
            background: #ecfdf5;
            color: #047857;
        }

        .badge-inactive {
            background: #fef2f2;
            color: #b91c1c;
        }

        .btn-action {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            border: 1px solid var(--border-soft);
            background: white;
            color: var(--text-secondary);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-action:hover {
            background: #f8fafc;
            color: var(--text-primary);
            border-color: #d1d5db;
        }

        .btn-action.btn-delete:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.55rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
            font-family: var(--font-sans);
            letter-spacing: -0.005em;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(59, 130, 246, 0.2);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.35);
        }

        /* Form styles */
        .form-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-soft);
            padding: 2rem;
            box-shadow: var(--shadow-xs);
        }

        .form-card .form-label {
            font-weight: 500;
            font-size: 0.825rem;
            color: var(--text-primary);
            margin-bottom: 0.45rem;
            letter-spacing: -0.005em;
        }

        .form-card .form-control,
        .form-card .form-select {
            border-radius: 9px;
            border: 1px solid var(--border-soft);
            padding: 0.55rem 0.85rem;
            font-size: 0.875rem;
            font-family: var(--font-sans);
            color: var(--text-primary);
            background: #ffffff;
            transition: all 0.15s ease;
        }

        .form-card .form-control:focus,
        .form-card .form-select:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(10, 10, 10, 0.04);
        }

        .form-card .form-control::placeholder {
            color: var(--text-tertiary);
        }

        /* Alert styles */
        .alert-modern {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid transparent;
        }

        .alert-success-modern {
            background: #f0fdf4;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .alert-danger-modern {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        /* Global Bootstrap overrides — refined to match new system */
        .form-control,
        .form-select {
            font-family: var(--font-sans);
            font-size: 0.875rem;
            border-radius: 9px;
            border: 1px solid var(--border-soft);
            color: var(--text-primary);
            padding: 0.5rem 0.85rem;
            transition: all 0.15s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(10, 10, 10, 0.04);
        }

        .form-control::placeholder {
            color: var(--text-tertiary);
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.4rem;
            letter-spacing: -0.005em;
        }

        .btn {
            font-family: var(--font-sans);
            font-weight: 500;
            font-size: 0.85rem;
            border-radius: 8px;
            padding: 0.5rem 0.95rem;
            letter-spacing: -0.005em;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            border-radius: 10px;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(59, 130, 246, 0.2);
            transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.35);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-soft);
            background: #fff;
        }

        .btn-outline-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: var(--text-primary);
        }

        .btn-success {
            background: #059669;
            border-color: #059669;
        }

        .btn-danger {
            background: #dc2626;
            border-color: #dc2626;
        }

        .btn-warning {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        /* Bootstrap alerts */
        .alert {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .alert-warning {
            background: #fffbeb;
            color: #b45309;
            border-color: #fde68a;
        }

        .alert-info {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        /* Bootstrap badges baseline */
        .badge {
            font-weight: 500;
            font-size: 0.72rem;
            padding: 0.25rem 0.55rem;
            border-radius: 6px;
            letter-spacing: 0;
        }

        /* Dropdown menu */
        .dropdown-menu {
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-medium);
            padding: 0.35rem;
            font-family: var(--font-sans);
        }

        .dropdown-item {
            border-radius: 7px;
            font-size: 0.85rem;
            padding: 0.45rem 0.7rem;
            color: var(--text-primary);
            letter-spacing: -0.005em;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: #f5f5f7;
            color: var(--text-primary);
        }

        .dropdown-divider {
            border-color: var(--border-hairline);
            margin: 0.3rem 0;
        }

        /* Cards (generic bootstrap cards) */
        .card {
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-xs);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-hairline);
            padding: 1rem 1.25rem;
        }

        /* Modal */
        .modal-content {
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-medium);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-hairline);
            padding: 1.1rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-hairline);
            padding: 1rem 1.5rem;
        }

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 10, 10, 0.35);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        @media (max-width: 992px) {
            .content-area {
                padding: 1.25rem;
            }

            .topbar {
                padding: 0.75rem 1.25rem;
            }

            .search-bar input {
                width: 180px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 0 10px 40px rgba(10, 10, 10, 0.2);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1.25rem;
            }

            .topbar {
                padding: 0.75rem 1rem;
            }

            .search-bar {
                display: none;
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