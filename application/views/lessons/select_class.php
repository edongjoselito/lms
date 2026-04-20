<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;margin:0;">
            <?php if ($module_name === 'lessons'): ?>
                <i class="bi bi-book-fill me-2" style="color:#6366f1;"></i>Lessons
            <?php else: ?>
                <i class="bi bi-clipboard-check me-2" style="color:#6366f1;"></i>Assessments
            <?php endif; ?>
        </h5>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;">Select a class to manage <?= $module_name ?>.</p>
    </div>
</div>

<?php if (empty($classes)): ?>
<div class="text-center py-5">
    <i class="bi bi-journal-x" style="font-size:3rem;color:#cbd5e1;"></i>
    <p style="color:#94a3b8;margin-top:0.75rem;">No classes found. Create sections and assign subjects first.</p>
    <a href="<?= site_url('academic/sections') ?>" class="btn-primary-custom btn-sm mt-2">
        <i class="bi bi-plus-lg me-1"></i> Manage Sections
    </a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($classes as $cp): ?>
    <div class="col-md-6 col-lg-4">
        <a href="<?= site_url($module_name . '/index/' . $cp->id) ?>" style="text-decoration:none;">
            <div class="p-4" style="background:white;border-radius:16px;border:2px solid #e2e8f0;transition:all 0.2s;height:100%;" onmouseover="this.style.borderColor='#6366f1';this.style.boxShadow='0 4px 12px rgba(99,102,241,0.15)';" onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                        <?= strtoupper(substr($cp->subject_code ?: $cp->subject_name, 0, 3)) ?>
                    </div>
                    <div>
                        <div style="font-weight:700;color:#0f172a;font-size:0.95rem;"><?= htmlspecialchars($cp->subject_name) ?></div>
                        <div style="color:#94a3b8;font-size:0.78rem;"><?= $cp->subject_code ?></div>
                    </div>
                </div>
                <div style="font-size:0.8rem;color:#64748b;">
                    <i class="bi bi-people me-1"></i> <?= isset($cp->section_name) ? htmlspecialchars($cp->section_name) : '' ?>
                    <?php if (isset($cp->teacher_name) && $cp->teacher_name): ?>
                        &middot; <i class="bi bi-person me-1"></i><?= $cp->teacher_name ?>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
