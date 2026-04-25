<div style="max-width:800px;margin:0 auto;padding:2rem 1.5rem;">
    <h4 style="font-weight:700;margin-bottom:0.25rem;">My Subjects</h4>
    <p style="color:#64748b;margin-bottom:2rem;">Subjects assigned to you for content management.</p>

    <?php if (empty($subjects)): ?>
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:3rem;text-align:center;">
            <i class="bi bi-book" style="font-size:2.5rem;color:#cbd5e1;"></i>
            <p style="color:#94a3b8;margin-top:1rem;margin-bottom:0;">No subjects assigned yet. Ask your Course Creator to assign you to a subject.</p>
        </div>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            <?php foreach ($subjects as $s): ?>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.25rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <div>
                        <div style="font-weight:600;font-size:1rem;"><?= htmlspecialchars($s->code) ?></div>
                        <div style="color:#64748b;font-size:0.875rem;margin-top:2px;"><?= htmlspecialchars($s->description ?: '') ?></div>
                        <?php if (!empty($s->program_code)): ?>
                            <div style="margin-top:4px;">
                                <span style="background:#ede9fe;color:#5b21b6;font-size:0.75rem;font-weight:500;padding:2px 8px;border-radius:20px;"><?= htmlspecialchars($s->program_code) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <a href="<?= site_url('course/content/' . $s->id . '?edit=1') ?>"
                       style="background:#6366f1;color:#fff;text-decoration:none;padding:8px 18px;border-radius:8px;font-size:0.875rem;font-weight:500;white-space:nowrap;">
                        <i class="bi bi-pencil-square me-1"></i> Edit Content
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
