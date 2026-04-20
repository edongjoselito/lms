<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-journal-check me-2"></i>Grading - Sections</h5>
    </div>
    <div class="p-4">
        <p style="color:#64748b;font-size:0.9rem;">Select a section to view class records and manage grades.</p>
        <div class="row g-3">
            <?php if (!empty($sections)): ?>
                <?php foreach ($sections as $sec): ?>
                    <div class="col-md-4">
                        <div class="p-3" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
                            <div style="font-weight:700;font-size:0.95rem;"><?= htmlspecialchars($sec->name) ?></div>
                            <div style="color:#64748b;font-size:0.8rem;">
                                <?= isset($sec->grade_level_name) ? $sec->grade_level_name : '' ?>
                                <?= isset($sec->program_code) ? $sec->program_code : '' ?>
                                <span class="badge-role <?= ($sec->system_type == 'ched') ? 'badge-admin' : 'badge-user' ?> ms-1"><?= strtoupper($sec->system_type) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-4" style="color:#94a3b8;">No sections found for this school year.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
