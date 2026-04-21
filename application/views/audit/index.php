<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-clock-history me-2"></i>Audit Logs</h5>
        <div class="table-filters">
            <span class="badge bg-secondary">Total: <?= $total_logs ?></span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td style="font-size:0.85rem;">
                                <?= date('M d, Y H:i:s', strtotime($log->created_at)) ?>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($log->user_name) ?></div>
                                <div style="color:#94a3b8;font-size:0.78rem;">User ID: <?= $log->user_id ?></div>
                            </td>
                            <td>
                                <?php
                                $action_badges = array(
                                    'create' => 'badge-success',
                                    'update' => 'badge-warning',
                                    'delete' => 'badge-danger'
                                );
                                ?>
                                <span class="badge-role <?= isset($action_badges[$log->action]) ? $action_badges[$log->action] : 'badge-secondary' ?>">
                                    <?= ucfirst($log->action) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.85rem;"><?= ucfirst($log->entity_type) ?></div>
                                <div style="color:#94a3b8;font-size:0.78rem;"><?= htmlspecialchars($log->entity_name) ?></div>
                            </td>
                            <td style="font-size:0.85rem;color:#64748b;"><?= htmlspecialchars($log->description) ?></td>
                            <td style="font-size:0.8rem;color:#94a3b8;"><?= htmlspecialchars($log->ip_address) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5" style="color:#94a3b8;">No audit logs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
