<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<?php
$rs = isset($role_slug) ? $role_slug : $this->session->userdata('role_slug');
$session_role_slug = $this->session->userdata('role_slug');
$selected_school_id = $this->session->userdata('school_id');
$nav_role = ($rs === 'super_admin' && $selected_school_id) ? 'school_admin' : $rs;
$is_school_select_page = ($this->uri->segment(1) == 'schools' && $this->uri->segment(2) == 'select');
$school_name = $selected_school_id ? $this->session->userdata('school_name') : '';
$brand_text = $school_name ?: 'LMS Platform';

if ($rs === 'student') {
    $brand_text = 'Student Portal';
    $panel_label = 'Student Panel';
} elseif ($rs === 'teacher') {
    $panel_label = 'Teacher Panel';
} elseif ($rs === 'course_creator') {
    $panel_label = 'Course Panel';
} elseif ($selected_school_id) {
    $panel_label = 'School Panel';
} else {
    $panel_label = 'Management Panel';
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <div class="brand-text"><?= htmlspecialchars($brand_text) ?></div>
            <div class="brand-sub"><?= $panel_label ?></div>
        </div>
    </div>


    <?php if ($session_role_slug === 'super_admin' && $selected_school_id && !$is_school_select_page): ?>
        <div class="px-3 pb-2 d-grid gap-2">
            <a href="<?= site_url('schools/select') ?>" class="d-flex align-items-center gap-2 px-3 py-2" style="background:rgba(13,148,136,0.1);border-radius:10px;font-size:0.78rem;color:#0d9488;text-decoration:none;font-weight:600;">
                <i class="bi bi-arrow-left-right"></i>
                <span>Switch School</span>
            </a>
            <a href="<?= site_url('schools/switch_to_platform') ?>" class="d-flex align-items-center gap-2 px-3 py-2" style="background:#f8fafc;border-radius:10px;font-size:0.78rem;color:#475569;text-decoration:none;font-weight:600;">
                <i class="bi bi-building"></i>
                <span>Platform View</span>
            </a>
        </div>
    <?php endif; ?>

    <nav class="sidebar-nav">
        <?php if ($nav_role !== 'student' && $nav_role !== 'course_creator'): ?>
            <div class="nav-section-title">Main</div>
            <a href="<?= site_url('dashboard') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'super_admin'): ?>
            <div class="nav-section-title">Platform</div>
            <a href="<?= site_url('schools') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'schools') ? 'active' : '' ?>">
                <i class="bi bi-building"></i>
                <span>Schools</span>
            </a>
            <a href="<?= site_url('academic/programs') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'academic') ? 'active' : '' ?>">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Programs</span>
            </a>
        <?php endif; ?>

        <?php if (in_array($nav_role, array('super_admin', 'school_admin'))): ?>
            <div class="nav-section-title">Administration</div>
            <a href="<?= site_url('users') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'users') ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i>
                <span>Users</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'super_admin'): ?>
            <a href="<?= site_url('audit') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'audit') ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                <span>Audit Logs</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'teacher'): ?>
            <div class="nav-section-title">Academics</div>
            <a href="<?= site_url('grades') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'grades') ? 'active' : '' ?>">
                <i class="bi bi-journal-check"></i>
                <span>Grades</span>
            </a>
            <a href="<?= site_url('attendance') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'attendance') ? 'active' : '' ?>">
                <i class="bi bi-calendar-check-fill"></i>
                <span>Attendance</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'course_creator'): ?>
            <div class="nav-section-title">Course Management</div>
            <a href="<?= site_url('course') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'course' && !$this->uri->segment(2)) ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'school_admin'): ?>
            <div class="nav-section-title">Academic Setup</div>
            <a href="<?= site_url('academic/programs') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'academic' && $this->uri->segment(2) == 'programs') ? 'active' : '' ?>">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Programs</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'student'): ?>
            <div class="nav-section-title">Main</div>
            <a href="<?= site_url('student') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'student' && !$this->uri->segment(2)) ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if (in_array($nav_role, array('school_admin', 'teacher', 'student', 'course_creator'))): ?>
            <div class="nav-section-title">Learning</div>
            <?php if ($nav_role === 'student'): ?>
                <?php $student_learning_active = ($this->uri->segment(1) == 'student' && in_array($this->uri->segment(2), array('subjects', 'content', 'lesson', 'enroll'))); ?>
                <a href="<?= site_url('student/subjects') ?>" class="sidebar-link <?= $student_learning_active ? 'active' : '' ?>">
                    <i class="bi bi-book-fill"></i>
                    <span>Subjects</span>
                </a>
            <?php else: ?>
                <a href="<?= site_url('subjects') ?>" class="sidebar-link <?= in_array($this->uri->segment(1), array('subjects')) ? 'active' : '' ?>">
                    <i class="bi bi-book-fill"></i>
                    <span>Subjects</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <div class="nav-section-title">Account</div>
        <a href="<?= site_url('profile') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'profile') ? 'active' : '' ?>">
            <i class="bi bi-person-circle"></i>
            <span>My Profile</span>
        </a>
        <?php if ($nav_role === 'super_admin'): ?>
            <a href="#" class="sidebar-link">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        <?php endif; ?>
        <?php if (isset($original_role_slug) && in_array($original_role_slug, array('teacher', 'course_creator'))): ?>
            <?php if (isset($is_student_mode) && $is_student_mode): ?>
                <a href="<?= site_url('mode/toggle') ?>" class="sidebar-link" style="background:#fef3c7;color:#b45309;">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Exit Student Mode</span>
                </a>
            <?php else: ?>
                <a href="<?= site_url('mode/toggle') ?>" class="sidebar-link">
                    <i class="bi bi-eye-fill"></i>
                    <span>View as Student</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="<?= site_url('auth/logout') ?>" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">
                <?= strtoupper(substr($this->session->userdata('first_name'), 0, 1) . substr($this->session->userdata('last_name'), 0, 1)) ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name') ?></div>
                <div class="user-role"><?= $this->session->userdata('role_name') ?></div>
            </div>
        </div>
    </div>
</aside>
