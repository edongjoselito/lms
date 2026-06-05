<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$CI =& get_instance();
$current_user_id = (int) $CI->session->userdata('user_id');
$role_count = !empty($roles) ? count($roles) : 0;
$page_user_count = !empty($users) ? count($users) : 0;
$active_page_count = 0;
$page_title = isset($page_title) && $page_title !== '' ? $page_title : 'Manage Users';
$page_description = isset($page_description) && $page_description !== '' ? $page_description : 'View and manage user accounts, roles, and account status across your school.';
$list_title = isset($list_title) && $list_title !== '' ? $list_title : 'All Users';
$pagination_base_url = isset($pagination_base_url) && $pagination_base_url !== '' ? $pagination_base_url : 'users';
$can_create_users = isset($can_create_users) ? (bool) $can_create_users : true;
$create_url = isset($create_url) && $create_url !== '' ? $create_url : site_url('users/create');
$back_url = isset($back_url) ? $back_url : '';
$back_label = isset($back_label) && $back_label !== '' ? $back_label : 'Back';

if (!empty($users)) {
    foreach ($users as $user_item) {
        if (!empty($user_item->status)) {
            $active_page_count++;
        }
    }
}
?>

<div class="ps-page">
    <?php if ($back_url): ?>
        <div class="mb-3">
            <a href="<?= htmlspecialchars($back_url, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-size:0.8rem;">
                <i class="bi bi-arrow-left me-1"></i> <?= htmlspecialchars($back_label) ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">US</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Administration</span>
                        <span class="ps-tag ps-tag-code">User Directory</span>
                    </div>
                    <h1 class="ps-hero-title"><?= htmlspecialchars($page_title) ?></h1>
                    <p class="ps-hero-desc"><?= htmlspecialchars($page_description) ?></p>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $total_users ?></div>
                    <div class="ps-hero-stat-lbl">Total Users</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $role_count ?></div>
                    <div class="ps-hero-stat-lbl">Roles</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $active_page_count ?></div>
                    <div class="ps-hero-stat-lbl">Active Here</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-people-fill"></i>
                    <span><?= htmlspecialchars($list_title) ?></span>
                    <span class="ps-count-pill"><?= (int) $page_user_count ?></span>
                </div>
                <div class="ps-card-tools">
                    <?php if (!empty($users)): ?>
                    <div class="ps-search-wrap">
                        <i class="bi bi-search ps-search-icon"></i>
                        <input type="text" class="ps-search" id="userSearch" placeholder="Search users...">
                    </div>
                    <?php endif; ?>
                    <?php if ($can_create_users): ?>
                    <a href="<?= htmlspecialchars($create_url, ENT_QUOTES, 'UTF-8') ?>" class="ps-submit-btn ps-submit-btn-inline">
                        <i class="bi bi-plus-lg"></i> Add User
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($users)): ?>
                <div class="ps-table-head ps-user-table-head">
                    <div class="ps-th ps-th-num">#</div>
                    <div class="ps-th ps-th-user">User</div>
                    <div class="ps-th ps-th-role">Role</div>
                    <div class="ps-th ps-th-status">Status</div>
                    <div class="ps-th ps-th-created">Created</div>
                    <div class="ps-th ps-th-actions">Actions</div>
                </div>
                <div class="ps-subject-list" id="userList">
                    <?php foreach ($users as $index => $user): ?>
                        <?php
                        $full_name = trim($user->first_name . ' ' . $user->last_name);
                        $initials = strtoupper(substr((string) $user->first_name, 0, 1) . substr((string) $user->last_name, 0, 1));
                        $role_name = isset($user->role_name) ? trim((string) $user->role_name) : '';
                        $role_slug = isset($user->role_slug) ? trim((string) $user->role_slug) : '';
                        $status_label = !empty($user->status) ? 'Active' : 'Inactive';
                        $created_label = !empty($user->created_at) ? date('M d, Y', strtotime($user->created_at)) : '-';
                        $search_text = strtolower(trim($full_name . ' ' . $user->email . ' ' . $role_name . ' ' . $status_label));
                        ?>
                        <div class="ps-subject-item ps-user-item" data-search="<?= htmlspecialchars($search_text, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="ps-col-num">
                                <span class="ps-subject-index"><?= (($current_page - 1) * $per_page) + $index + 1 ?></span>
                            </div>

                            <div class="ps-col-user">
                                <div class="ps-user-profile">
                                    <div class="ps-user-avatar"><?= htmlspecialchars($initials !== '' ? $initials : 'U') ?></div>
                                    <div class="ps-user-copy">
                                        <div class="ps-user-name"><?= htmlspecialchars($full_name !== '' ? $full_name : 'User') ?></div>
                                        <div class="ps-user-email"><?= htmlspecialchars($user->email) ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="ps-col-role">
                                <span class="ps-role-badge <?= in_array($role_slug, array('super_admin', 'school_admin')) ? 'ps-role-badge-admin' : 'ps-role-badge-user' ?>">
                                    <?= htmlspecialchars($role_name !== '' ? $role_name : 'Role') ?>
                                </span>
                            </div>

                            <div class="ps-col-status">
                                <span class="ps-status-badge <?= !empty($user->status) ? 'ps-status-active' : 'ps-status-inactive' ?>">
                                    <?= $status_label ?>
                                </span>
                            </div>

                            <div class="ps-col-created">
                                <span class="ps-created-text"><?= htmlspecialchars($created_label) ?></span>
                            </div>

                            <div class="ps-col-actions">
                                <a href="<?= site_url('users/edit/' . $user->id) ?>" class="ps-action-btn ps-action-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <?php if ((int) $user->id !== $current_user_id): ?>
                                    <a href="<?= site_url('users/delete/' . $user->id) ?>" class="ps-action-btn ps-action-del" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="bi bi-trash3-fill"></i> Delete
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ps-no-results" id="noResults" style="display:none;">
                    <i class="bi bi-search" style="font-size:1.8rem;opacity:0.3;"></i>
                    <p>No users match your search.</p>
                </div>
            <?php else: ?>
                <div class="ps-empty">
                    <div class="ps-empty-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-empty-title">No users found</div>
                    <div class="ps-empty-sub">Create your first user account to get started.</div>
                    <?php if ($can_create_users): ?>
                    <a href="<?= htmlspecialchars($create_url, ENT_QUOTES, 'UTF-8') ?>" class="ps-submit-btn ps-empty-btn">
                        <i class="bi bi-plus-lg"></i> Add User
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="ps-pagination-bar">
                <?php if ($total_pages > 1): ?>
                    <span class="ps-pagination-info">
                        Showing <?= (($current_page - 1) * $per_page) + 1 ?> - <?= min($current_page * $per_page, $total_users) ?> of <?= (int) $total_users ?> users
                    </span>
                    <div class="ps-pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="<?= site_url($pagination_base_url . '?page=' . ($current_page - 1)) ?>" class="ps-page-btn">
                                <i class="bi bi-chevron-left"></i> Previous
                            </a>
                        <?php else: ?>
                            <span class="ps-page-btn ps-page-btn-disabled">
                                <i class="bi bi-chevron-left"></i> Previous
                            </span>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $current_page): ?>
                                <span class="ps-page-num ps-page-num-active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="<?= site_url($pagination_base_url . '?page=' . $i) ?>" class="ps-page-num"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?= site_url($pagination_base_url . '?page=' . ($current_page + 1)) ?>" class="ps-page-btn">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="ps-page-btn ps-page-btn-disabled">
                                Next <i class="bi bi-chevron-right"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <span class="ps-pagination-info">Showing <?= (int) $page_user_count ?> of <?= (int) $total_users ?> users</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('userSearch') && document.getElementById('userSearch').addEventListener('input', function () {
    var q = this.value.toLowerCase().trim();
    var items = document.querySelectorAll('.ps-user-item');
    var visible = 0;

    items.forEach(function (item) {
        var match = !q || item.dataset.search.indexOf(q) !== -1;
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('noResults').style.display = visible === 0 ? 'flex' : 'none';
});
</script>

<style>
.ps-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

.ps-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}

.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.ps-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.ps-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}

.ps-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.ps-hero-avatar {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.3);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 1px;
}

.ps-hero-info {
    min-width: 0;
}

.ps-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.ps-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.ps-tag-degree {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.ps-tag-code {
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.9);
    border: 1px solid rgba(255,255,255,0.25);
}

.ps-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.ps-hero-desc {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.72);
    margin: 0;
    line-height: 1.5;
    max-width: 560px;
}

.ps-hero-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
    flex-wrap: wrap;
}

.ps-hero-stat {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 100px;
}

.ps-hero-stat-num {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.ps-hero-stat-lbl {
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255,255,255,0.75);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-top: 0.3rem;
}

.ps-layout {
    display: grid;
    gap: 1.5rem;
    align-items: start;
}

.ps-layout-full {
    grid-template-columns: 1fr;
}

.ps-card {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}

.ps-subject-card {
    overflow: visible;
}

.ps-subject-card > .ps-card-head {
    border-radius: 20px 20px 0 0;
}

.ps-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    flex-wrap: wrap;
}

.ps-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.ps-card-title i {
    color: #2563eb;
    font-size: 1rem;
}

.ps-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.6rem;
    letter-spacing: 0.02em;
}

.ps-card-tools {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.ps-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.ps-search-icon {
    position: absolute;
    left: 0.7rem;
    color: #94a3b8;
    font-size: 0.8rem;
    pointer-events: none;
}

.ps-search {
    padding: 0.45rem 0.75rem 0.45rem 2rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.83rem;
    color: #334155;
    background: #fff;
    outline: none;
    width: 220px;
    font-family: inherit;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.ps-search:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}

.ps-user-table-head {
    display: grid;
    grid-template-columns: 52px minmax(220px, 1.4fr) minmax(110px, 0.7fr) minmax(100px, 0.6fr) minmax(110px, 0.7fr) auto;
    padding: 0.55rem 1.5rem;
    background: #f8faff;
    border-bottom: 1px solid #eaecf0;
}

.ps-th {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.ps-th-actions {
    text-align: right;
    min-width: 150px;
}

.ps-subject-list {
    position: relative;
    overflow: visible;
    padding: 0;
}

.ps-user-item {
    position: relative;
    display: grid;
    grid-template-columns: 52px minmax(220px, 1.4fr) minmax(110px, 0.7fr) minmax(100px, 0.6fr) minmax(110px, 0.7fr) auto;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.14s;
    animation: ps-fadein 0.22s ease forwards;
}

@keyframes ps-fadein {
    from { opacity: 0; transform: translateY(3px); }
    to { opacity: 1; transform: translateY(0); }
}

.ps-user-item:last-child {
    border-bottom: none;
}

.ps-user-item:hover {
    background: #f8f9ff;
}

.ps-col-num {
    display: flex;
    align-items: center;
}

.ps-subject-index {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 0.72rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ps-col-user,
.ps-col-role,
.ps-col-status,
.ps-col-created {
    min-width: 0;
    padding-right: 1rem;
}

.ps-user-profile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
}

.ps-user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: linear-gradient(135deg,#3b82f6,#60a5fa);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 800;
    flex-shrink: 0;
}

.ps-user-copy {
    min-width: 0;
}

.ps-user-name {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1e293b;
    line-height: 1.25;
}

.ps-user-email {
    margin-top: 0.15rem;
    font-size: 0.82rem;
    color: #64748b;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.ps-detail-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 600;
}

.ps-detail-badge i {
    color: #2563eb;
}

.ps-role-badge,
.ps-status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.32rem 0.7rem;
    border-radius: 999px;
    font-size: 0.74rem;
    font-weight: 700;
    letter-spacing: 0.02em;
}

.ps-role-badge-admin {
    background: #dbeafe;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.ps-role-badge-user {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.ps-status-active {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.ps-status-inactive {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.ps-created-text {
    font-size: 0.82rem;
    color: #64748b;
}

.ps-muted-empty {
    color: #cbd5e1;
    font-style: italic;
    font-size: 0.82rem;
}

.ps-col-actions {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    justify-content: flex-end;
    min-width: 150px;
}

.ps-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.38rem 0.7rem;
    border-radius: 9px;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.14s ease;
    white-space: nowrap;
    cursor: pointer;
}

.ps-action-edit {
    background: #fef9c3;
    color: #a16207;
}

.ps-action-edit:hover {
    background: #fef08a;
    color: #854d0e;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-action-del {
    background: #fee2e2;
    color: #dc2626;
}

.ps-action-del:hover {
    background: #fecaca;
    color: #b91c1c;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-pagination-bar {
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
}

.ps-pagination-info {
    font-size: 0.8rem;
    color: #64748b;
}

.ps-pagination {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.ps-page-btn,
.ps-page-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 34px;
    padding: 0.4rem 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}

.ps-page-btn {
    gap: 0.25rem;
}

.ps-page-btn:hover,
.ps-page-num:hover {
    border-color: #bfdbfe;
    background: #eff6ff;
    color: #1d4ed8;
    text-decoration: none;
}

.ps-page-num {
    min-width: 34px;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.ps-page-num-active {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}

.ps-page-btn-disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.ps-no-results {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 3rem 2rem;
    color: #94a3b8;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
}

.ps-no-results p {
    margin: 0;
}

.ps-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4rem 2rem;
    text-align: center;
}

.ps-empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
    font-size: 1.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.ps-empty-title {
    font-size: 1rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.4rem;
}

.ps-empty-sub {
    font-size: 0.85rem;
    color: #94a3b8;
    max-width: 300px;
    line-height: 1.5;
}

.ps-submit-btn {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(59,130,246,0.35);
    font-family: inherit;
    margin-top: 0.25rem;
    letter-spacing: 0.01em;
    text-decoration: none;
}

.ps-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(59,130,246,0.45);
    color: #fff;
    text-decoration: none;
}

.ps-submit-btn-inline {
    width: auto;
    margin-top: 0;
    padding: 0.65rem 1rem;
    border-radius: 10px;
}

.ps-empty-btn {
    width: auto;
    margin-top: 1.25rem;
    padding: 0.75rem 1rem;
}

@media (max-width: 1180px) {
    .ps-user-table-head {
        display: none;
    }

    .ps-user-item {
        grid-template-columns: 48px 1fr auto;
        grid-template-rows: auto auto auto;
        gap: 0.65rem 0;
    }

    .ps-col-role,
    .ps-col-status,
    .ps-col-created {
        grid-column: 2;
        padding-right: 0;
    }

    .ps-col-actions {
        grid-row: 1 / 4;
        align-self: start;
    }
}

@media (max-width: 768px) {
    .ps-hero-content {
        padding: 1.5rem;
    }

    .ps-hero-left {
        align-items: flex-start;
    }

    .ps-hero-title {
        font-size: 1.3rem;
    }

    .ps-card-head,
    .ps-pagination-bar {
        padding: 1rem;
    }

    .ps-card-tools {
        width: 100%;
        justify-content: stretch;
    }

    .ps-search-wrap,
    .ps-search,
    .ps-submit-btn-inline {
        width: 100%;
    }

    .ps-user-item {
        padding: 1rem;
    }

    .ps-pagination {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 520px) {
    .ps-user-item {
        grid-template-columns: 1fr;
        grid-template-rows: none;
        gap: 0.75rem;
    }

    .ps-col-num {
        display: none;
    }

    .ps-col-user,
    .ps-col-role,
    .ps-col-status,
    .ps-col-created,
    .ps-col-actions {
        grid-column: auto;
        grid-row: auto;
    }

    .ps-col-actions {
        justify-content: flex-start;
        min-width: 0;
    }

    .ps-user-email {
        white-space: normal;
    }
}
</style>
