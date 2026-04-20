<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-people-fill me-2"></i>All Users</h5>
        <a href="<?= site_url('users/create') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i>
            Add User
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>School</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.75rem;flex-shrink:0;">
                                        <?= strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#64748b;"><?= htmlspecialchars($user->email) ?></td>
                            <td style="color:#64748b;font-size:0.85rem;"><?= isset($user->school_name) && $user->school_name ? htmlspecialchars($user->school_name) : '<span style="color:#cbd5e1;">—</span>' ?></td>
                            <td>
                                <span class="badge-role <?= (in_array($user->role_slug, array('super_admin','school_admin'))) ? 'badge-admin' : 'badge-user' ?>">
                                    <?= $user->role_name ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status <?= ($user->status) ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= ($user->status) ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?= date('M d, Y', strtotime($user->created_at)) ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= site_url('users/edit/' . $user->id) ?>" class="btn-action" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($user->id != $this->session->userdata('user_id')): ?>
                                        <a href="<?= site_url('users/delete/' . $user->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color:#94a3b8;">
                            <i class="bi bi-people" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                            No users found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
