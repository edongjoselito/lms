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
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-value"><?= $total_students ?></div>
            <div class="stat-label">Students</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div class="stat-value"><?= $total_teachers ?></div>
            <div class="stat-label">Teachers</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                <i class="bi bi-clipboard-check-fill"></i>
            </div>
            <div class="stat-value"><?= $total_enrolled ?></div>
            <div class="stat-label">Enrolled This S.Y.</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="data-table h-100">
            <div class="table-header">
                <h5><i class="bi bi-list-ol me-2"></i>K-12 Grade Levels</h5>
            </div>
            <div class="p-3">
                <?php if (!empty($grade_levels)): ?>
                    <?php foreach ($grade_levels as $gl): ?>
                        <div class="d-flex align-items-center gap-2 py-1 px-2" style="font-size:0.88rem;">
                            <span class="badge-role badge-user" style="min-width:50px;text-align:center;"><?= $gl->code ?></span>
                            <span><?= $gl->name ?></span>
                            <span class="ms-auto" style="color:#94a3b8;font-size:0.75rem;"><?= ucfirst(str_replace('_', ' ', $gl->category)) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center" style="color:#94a3b8;">No grade levels configured.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="data-table h-100">
            <div class="table-header">
                <h5><i class="bi bi-mortarboard-fill me-2"></i>CHED Programs</h5>
            </div>
            <div class="p-3">
                <?php if (!empty($programs)): ?>
                    <?php foreach ($programs as $p): ?>
                        <div class="d-flex align-items-center gap-2 py-2 px-2" style="font-size:0.88rem;border-bottom:1px solid #f1f5f9;">
                            <span class="badge-role badge-admin" style="min-width:55px;text-align:center;"><?= $p->code ?></span>
                            <span style="line-height:1.3;"><?= $p->name ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center" style="color:#94a3b8;">No programs configured.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="data-table h-100">
            <div class="table-header">
                <h5><i class="bi bi-person-circle me-2"></i>Profile</h5>
            </div>
            <div class="p-4 text-center">
                <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:inline-flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.5rem;margin-bottom:0.75rem;">
                    <?= strtoupper(substr($this->session->userdata('first_name'), 0, 1) . substr($this->session->userdata('last_name'), 0, 1)) ?>
                </div>
                <div style="font-weight:700;font-size:1.05rem;"><?= $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name') ?></div>
                <div style="color:#64748b;font-size:0.85rem;"><?= $this->session->userdata('email') ?></div>
                <span class="badge-role badge-admin mt-2 d-inline-block"><?= $this->session->userdata('role_name') ?></span>

                <?php if (in_array($this->session->userdata('role_slug'), array('super_admin','school_admin'))): ?>
                <div class="mt-3 pt-3" style="border-top:1px solid #f1f5f9;">
                    <a href="<?= site_url('users') ?>" class="btn-primary-custom btn-sm w-100 justify-content-center">
                        <i class="bi bi-people-fill"></i> Manage Users
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
