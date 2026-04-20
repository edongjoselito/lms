<?php if ($this->session->userdata('school_id')): ?>
<div class="mb-3">
    <a href="<?= site_url('schools/switch_to_platform') ?>" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-size:0.8rem;">
        <i class="bi bi-arrow-left me-1"></i> Back to Platform View
    </a>
</div>
<?php endif; ?>

<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-building me-2"></i>Schools</h5>
        <a href="<?= site_url('schools/create') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Add School
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>School</th>
                    <th>Type</th>
                    <th>Division / Region</th>
                    <th class="text-center">Users</th>
                    <th class="text-center">Students</th>
                    <th class="text-center">Teachers</th>
                    <th class="text-center">Sections</th>
                    <th style="width:160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($schools)): ?>
                    <?php foreach ($schools as $s): ?>
                        <tr>
                            <td>
                                <div style="font-weight:700;font-size:0.95rem;"><?= htmlspecialchars($s->name) ?></div>
                                <div style="color:#94a3b8;font-size:0.78rem;"><?= $s->school_id_number ?: '' ?> <?= $s->email ? '· ' . $s->email : '' ?></div>
                            </td>
                            <td>
                                <?php
                                $type_badges = array('deped' => 'badge-user', 'ched' => 'badge-admin', 'both' => 'badge-admin');
                                ?>
                                <span class="badge-role <?= isset($type_badges[$s->type]) ? $type_badges[$s->type] : 'badge-user' ?>">
                                    <?= strtoupper($s->type) ?>
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;"><?= $s->division ?: '' ?><?= $s->region ? ', ' . $s->region : '' ?></td>
                            <td class="text-center" style="font-weight:600;"><?= $s->stats->users ?></td>
                            <td class="text-center" style="font-weight:600;"><?= $s->stats->students ?></td>
                            <td class="text-center" style="font-weight:600;"><?= $s->stats->teachers ?></td>
                            <td class="text-center" style="font-weight:600;"><?= $s->stats->sections ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= site_url('schools/switch_school/' . $s->id) ?>" class="btn-primary-custom btn-sm" title="Enter School">
                                        <i class="bi bi-box-arrow-in-right"></i> Enter
                                    </a>
                                    <a href="<?= site_url('schools/edit/' . $s->id) ?>" class="btn-action" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-5" style="color:#94a3b8;">No schools yet. Create your first school.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
