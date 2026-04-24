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
            <span class="material-symbols-outlined">school</span>
        </div>
        <div>
            <div class="brand-text"><?= htmlspecialchars($brand_text) ?></div>
            <div class="brand-sub"><?= $panel_label ?></div>
        </div>
    </div>


    <?php if ($session_role_slug === 'super_admin' && $selected_school_id && !$is_school_select_page): ?>
        <div class="px-3 pb-2 d-grid gap-1">
            <a href="<?= site_url('schools/select') ?>" class="sidebar-link" style="font-size:0.78rem;margin:0;">
                <span class="material-symbols-outlined">swap_horiz</span>
                <span>Switch School</span>
            </a>
            <a href="<?= site_url('schools/switch_to_platform') ?>" class="sidebar-link" style="font-size:0.78rem;margin:0;">
                <span class="material-symbols-outlined">domain</span>
                <span>Platform View</span>
            </a>
        </div>
    <?php endif; ?>

    <nav class="sidebar-nav">
        <?php if ($nav_role !== 'student' && $nav_role !== 'course_creator'): ?>
            <div class="nav-section-title">Main</div>
            <a href="<?= site_url('dashboard') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'dashboard') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'super_admin'): ?>
            <div class="nav-section-title">Platform</div>
            <a href="<?= site_url('schools') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'schools') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">domain</span>
                <span>Schools</span>
            </a>
            <a href="<?= site_url('academic/programs') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'academic') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">school</span>
                <span>Programs</span>
            </a>
        <?php endif; ?>

        <?php if (in_array($nav_role, array('super_admin', 'school_admin'))): ?>
            <div class="nav-section-title">Administration</div>
            <a href="<?= site_url('users') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'users') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">group</span>
                <span>Users</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'super_admin'): ?>
            <a href="<?= site_url('audit') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'audit') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">history</span>
                <span>Audit Logs</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'teacher'): ?>
            <div class="nav-section-title">Academics</div>
            <a href="<?= site_url('grades') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'grades') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">checklist</span>
                <span>Grades</span>
            </a>
            <a href="<?= site_url('attendance') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'attendance') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">event_available</span>
                <span>Attendance</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'course_creator'): ?>
            <div class="nav-section-title">Course Management</div>
            <a href="<?= site_url('course') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'course' && !$this->uri->segment(2)) ? 'active' : '' ?>">
                <span class="material-symbols-outlined">speed</span>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'school_admin'): ?>
            <div class="nav-section-title">Academic Setup</div>
            <a href="<?= site_url('academic/programs') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'academic' && $this->uri->segment(2) == 'programs') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">school</span>
                <span>Programs</span>
            </a>
        <?php endif; ?>

        <?php if ($nav_role === 'student'): ?>
            <div class="nav-section-title">Main</div>
            <a href="<?= site_url('student') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'student' && !$this->uri->segment(2)) ? 'active' : '' ?>">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if (in_array($nav_role, array('school_admin', 'teacher', 'student', 'course_creator'))): ?>
            <div class="nav-section-title">Learning</div>
            <?php if ($nav_role === 'student'): ?>
                <?php $student_learning_active = ($this->uri->segment(1) == 'student' && in_array($this->uri->segment(2), array('subjects', 'content', 'lesson', 'enroll'))); ?>
                <a href="<?= site_url('student/subjects') ?>" class="sidebar-link <?= $student_learning_active ? 'active' : '' ?>">
                    <span class="material-symbols-outlined">menu_book</span>
                    <span>Subjects</span>
                </a>
            <?php else: ?>
                <a href="<?= site_url('subjects') ?>" class="sidebar-link <?= in_array($this->uri->segment(1), array('subjects')) ? 'active' : '' ?>">
                    <span class="material-symbols-outlined">menu_book</span>
                    <span>Subjects</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <div class="nav-section-title">Account</div>
        <a href="<?= site_url('profile') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'profile') ? 'active' : '' ?>">
            <span class="material-symbols-outlined">person</span>
            <span>My Profile</span>
        </a>
        <?php if ($session_role_slug === 'super_admin'): ?>
            <a href="<?= site_url('settings') ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'settings') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">settings</span>
                <span>Settings</span>
            </a>
        <?php endif; ?>
        <?php if (isset($original_role_slug) && in_array($original_role_slug, array('teacher', 'course_creator'))): ?>
            <?php if (isset($is_student_mode) && $is_student_mode): ?>
                <a href="<?= site_url('mode/toggle') ?>" class="sidebar-link" style="background:#fff2e2;color:#ff9f43;">
                    <span class="material-symbols-outlined" style="color:#ff9f43;">person</span>
                    <span>Exit Student Mode</span>
                </a>
            <?php else: ?>
                <a href="<?= site_url('mode/toggle') ?>" class="sidebar-link">
                    <span class="material-symbols-outlined">visibility</span>
                    <span>View as Student</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="<?= site_url('auth/logout') ?>" class="sidebar-link">
            <span class="material-symbols-outlined">logout</span>
            <span>Logout</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <?php
            $avatar_path = $this->session->userdata('avatar');
            $first_name = $this->session->userdata('first_name');
            $last_name = $this->session->userdata('last_name');
            ?>
            <?php if (!empty($avatar_path) && file_exists('./' . $avatar_path)): ?>
                <img src="<?= base_url($avatar_path) ?>" alt="Avatar" class="avatar avatar--img">
            <?php else: ?>
                <div class="avatar">
                    <?= strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div class="user-info">
                <div class="user-name"><?= $first_name . ' ' . $last_name ?></div>
                <div class="user-role"><?= $this->session->userdata('role_name') ?></div>
            </div>
        </div>
    </div>
</aside>