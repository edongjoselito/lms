<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: #ede9fe; color: #6d28d9;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value"><?= $total_users ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="stat-value"><?= $active_users ?></div>
            <div class="stat-label">Active Users</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <div class="stat-value"><?= $total_admins ?></div>
            <div class="stat-label">Admin Accounts</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-clock-history me-2"></i>Quick Overview</h5>
            </div>
            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #f0fdf4; color: #22c55e; width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:600; font-size:0.9rem;">System Running</div>
                        <div style="color:#64748b; font-size:0.8rem;">All services are operational</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #ede9fe; color: #6366f1; width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:600; font-size:0.9rem;"><?= $total_users ?> Registered Users</div>
                        <div style="color:#64748b; font-size:0.8rem;"><?= $active_users ?> currently active</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fef3c7; color: #d97706; width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <div style="font-weight:600; font-size:0.9rem;">User Management</div>
                        <div style="color:#64748b; font-size:0.8rem;"><a href="<?= site_url('users') ?>" style="color:#6366f1;">Manage users &rarr;</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="data-table h-100">
            <div class="table-header">
                <h5><i class="bi bi-person-circle me-2"></i>Your Profile</h5>
            </div>
            <div class="p-4">
                <div class="text-center mb-3">
                    <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:inline-flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.5rem;">
                        <?= strtoupper(substr($this->session->userdata('first_name'), 0, 1) . substr($this->session->userdata('last_name'), 0, 1)) ?>
                    </div>
                </div>
                <div class="text-center">
                    <div style="font-weight:700;font-size:1.05rem;"><?= $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name') ?></div>
                    <div style="color:#64748b;font-size:0.85rem;"><?= $this->session->userdata('email') ?></div>
                    <span class="badge-role badge-admin mt-2 d-inline-block"><?= ucfirst($this->session->userdata('role')) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
