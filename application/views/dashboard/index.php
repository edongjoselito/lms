<?php if (isset($is_platform_view) && $is_platform_view): ?>
<div class="mb-3">
    <span class="badge-role badge-admin"><i class="bi bi-globe me-1"></i>Platform View</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: #ede9fe; color: #6d28d9;">
                <i class="bi bi-building"></i>
            </div>
            <div class="stat-value"><?= $active_schools ?></div>
            <div class="stat-label">Active Schools</div>
            <div style="color:#94a3b8;font-size:0.75rem;">Total: <?= $total_schools ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value"><?= $total_users ?></div>
            <div class="stat-label">Active Users</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="stat-value"><?= $total_courses ?></div>
            <div class="stat-label">Total Courses</div>
        </div>
    </div>
</div>
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
