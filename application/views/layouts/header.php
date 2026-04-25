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
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f5f5f9;
            --sidebar-active: #f5f5f9;
            --primary: #696cff;
            --primary-dark: #5f61f4;
            --primary-light: #e7e7ff;
            --accent: #696cff;
            --body-bg: #f5f5f9;
            --card-bg: #ffffff;
            --text-primary: #566a7f;
            --text-secondary: #697a8d;
            --text-tertiary: #a1acb8;
            --text-muted: #d9dee3;
            --border-soft: #d9dee3;
            --border-hairline: #f5f5f9;
            --shadow-xs: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
            --shadow-soft: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
            --shadow-medium: 0 4px 20px rgba(67, 89, 113, 0.15);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --font-sans: 'Public Sans', -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'SF Pro Display', 'Segoe UI', Roboto, sans-serif;
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
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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
            background: #696cff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-icon .material-symbols-outlined {
            font-size: 1.25rem;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 2px 0;
            transition: all 0.2s ease;
            letter-spacing: -0.005em;
            border-right: 4px solid transparent;
        }

        .sidebar-link:hover {
            color: var(--text-primary);
            background: var(--sidebar-hover);
        }

        .sidebar-link.active {
            color: var(--primary);
            background: var(--sidebar-active);
            font-weight: 600;
            border-right-color: var(--primary);
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .sidebar-link .material-symbols-outlined {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            color: var(--text-tertiary);
            transition: color 0.15s ease;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .sidebar-link:hover .material-symbols-outlined,
        .sidebar-link.active .material-symbols-outlined {
            color: var(--primary);
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

        .topbar-wrapper {
            padding: 1rem 1.5rem 0;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
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
            width: 36px;
            height: 36px;
            background: transparent;
            border: none;
            border-radius: 50%;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0;
            transition: all 0.15s ease;
        }

        .menu-toggle:hover {
            background: var(--sidebar-hover);
            color: var(--text-primary);
        }

        .menu-toggle .material-symbols-outlined {
            font-size: 1.25rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background: var(--border-soft);
            margin: 0 0.5rem;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-left: 0.5rem;
        }

        .topbar-user-info {
            text-align: right;
        }

        .topbar-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .topbar-user-role {
            font-size: 0.75rem;
            color: var(--text-tertiary);
            line-height: 1.2;
        }

        .topbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid rgba(105, 108, 255, 0.2);
            padding: 2px;
            overflow: hidden;
        }

        .topbar-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .topbar-avatar-initials {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Search Bar */
        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .search-bar .material-symbols-outlined {
            color: var(--text-secondary);
            font-size: 1.25rem;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .search-bar input {
            padding: 0.5rem 0;
            border: none;
            border-radius: 0;
            font-size: 0.875rem;
            font-family: var(--font-sans);
            width: 240px;
            background: transparent;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .search-bar input:focus {
            outline: none;
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
            width: 36px;
            height: 36px;
            background: transparent;
            border: none;
            border-radius: 50%;
            color: var(--text-secondary);
            cursor: pointer;
            position: relative;
            transition: all 0.15s ease;
        }

        .topbar-icon-btn:hover {
            background: var(--sidebar-hover);
            color: var(--text-primary);
        }

        .topbar-icon-btn .material-symbols-outlined {
            font-size: 1.25rem;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .notification-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ba1a1a;
            border-radius: 50%;
            border: 2px solid #ffffff;
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
            padding: 1.5rem;
            max-width: 1600px;
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
        <div class="topbar-wrapper">
            <div class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="sidebarToggle" onclick="toggleSidebar()" aria-label="Toggle sidebar" title="Toggle sidebar">
                        <span class="material-symbols-outlined">menu</span>
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
                            <span class="material-symbols-outlined">search</span>
                            <input type="text" placeholder="Search (Ctrl+/)">
                        </div>
                        <!-- Switch School Button -->
                        <?php if ($has_selected_school && !$is_school_select_page): ?>
                            <a href="<?= site_url('schools/select') ?>" class="btn-switch-school">
                                <span class="material-symbols-outlined" style="font-size:1rem;">swap_horiz</span>
                                Switch School
                            </a>
                        <?php endif; ?>
                    <?php elseif ($this->session->userdata('school_name') && (!isset($role_slug) || $role_slug !== 'student')): ?>
                        <span style="background:var(--sidebar-hover);padding:0.4rem 0.85rem;border-radius:10px;font-size:0.78rem;font-weight:600;color:var(--text-primary);margin-right:0.5rem;">
                            <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;margin-right:0.25rem;">apartment</span>
                            <?= htmlspecialchars($this->session->userdata('school_name')) ?>
                        </span>
                    <?php endif; ?>

                    <!-- Dark Mode Toggle -->
                    <button type="button" class="topbar-icon-btn" title="Toggle Dark Mode">
                        <span class="material-symbols-outlined">dark_mode</span>
                    </button>

                    <!-- Notification Icon -->
                    <button type="button" class="topbar-icon-btn" title="Notifications">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="notification-dot"></span>
                    </button>

                    <!-- Help Icon -->
                    <button type="button" class="topbar-icon-btn" title="Help">
                        <span class="material-symbols-outlined">help_outline</span>
                    </button>

                    <div class="topbar-divider"></div>

                    <!-- User Profile -->
                    <div class="topbar-user">
                        <div class="topbar-user-info">
                            <div class="topbar-user-name"><?= $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name') ?></div>
                            <div class="topbar-user-role"><?= $this->session->userdata('role_name') ?></div>
                        </div>
                        <div class="topbar-avatar">
                            <?php
                            $avatar_path = $this->session->userdata('avatar');
                            $first_name = $this->session->userdata('first_name');
                            $last_name = $this->session->userdata('last_name');
                            ?>
                            <?php if (!empty($avatar_path) && file_exists('./' . $avatar_path)): ?>
                                <img src="<?= base_url($avatar_path) ?>" alt="Avatar">
                            <?php else: ?>
                                <div class="topbar-avatar-initials">
                                    <?= strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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