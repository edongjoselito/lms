<?php $sy_label = isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A'; ?>
<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-diagram-3-fill me-2"></i>Sections <small style="color:#94a3b8;font-weight:400;">(S.Y. <?= $sy_label ?>)</small></h5>
        <a href="<?= site_url('academic/create_section') ?>" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Add Section
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Section Name</th>
                    <th>System</th>
                    <th>Level / Program</th>
                    <th>Adviser</th>
                    <th>Capacity</th>
                    <th style="width:100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sections)): ?>
                    <?php foreach ($sections as $sec): ?>
                        <tr>
                            <td style="font-weight:600;"><?= htmlspecialchars($sec->name) ?></td>
                            <td>
                                <span class="badge-role <?= ($sec->system_type == 'ched') ? 'badge-admin' : 'badge-user' ?>">
                                    <?= strtoupper($sec->system_type) ?>
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:0.85rem;">
                                <?= isset($sec->grade_level_name) ? $sec->grade_level_name : '' ?>
                                <?= isset($sec->program_code) ? $sec->program_code : '' ?>
                                <?= $sec->year_level ? '/ Year ' . $sec->year_level : '' ?>
                            </td>
                            <td style="color:#64748b;"><?= isset($sec->adviser_name) && $sec->adviser_name ? $sec->adviser_name : '<span style="color:#cbd5e1;">—</span>' ?></td>
                            <td style="color:#64748b;"><?= $sec->capacity ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('academic/edit_section/' . $sec->id) ?>" class="btn-action" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5" style="color:#94a3b8;">No sections found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
