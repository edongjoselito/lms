<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <div style="width:72px;height:72px;border-radius:18px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:inline-flex;align-items:center;justify-content:center;color:white;font-size:2rem;margin-bottom:1rem;">
                <i class="bi bi-building"></i>
            </div>
            <h3 style="font-weight:800;color:#0f172a;">Select a School</h3>
            <p style="color:#64748b;font-size:0.95rem;">Choose which school you want to manage.</p>
        </div>

        <?php if (!empty($schools)): ?>
        <div class="row g-3">
            <?php foreach ($schools as $s): ?>
                <div class="col-md-6">
                    <a href="<?= site_url('schools/switch_school/' . $s->id) ?>" style="text-decoration:none;">
                        <div class="p-4" style="background:white;border-radius:16px;border:2px solid #e2e8f0;transition:all 0.2s;cursor:pointer;" onmouseover="this.style.borderColor='#6366f1';this.style.boxShadow='0 4px 12px rgba(99,102,241,0.15)';" onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.1rem;flex-shrink:0;">
                                    <?= strtoupper(substr($s->name, 0, 2)) ?>
                                </div>
                                <div>
                                    <div style="font-weight:700;color:#0f172a;font-size:1rem;"><?= htmlspecialchars($s->name) ?></div>
                                    <div style="color:#94a3b8;font-size:0.8rem;">
                                        <span class="badge-role <?= ($s->type == 'ched') ? 'badge-admin' : 'badge-user' ?>"><?= strtoupper($s->type) ?></span>
                                        <?= $s->division ? '· ' . $s->division : '' ?>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <i class="bi bi-arrow-right-circle-fill" style="font-size:1.5rem;color:#6366f1;"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <div style="color:#94a3b8;font-size:1rem;margin-bottom:1rem;">No schools registered yet.</div>
            <a href="<?= site_url('schools/create') ?>" class="btn-primary-custom">
                <i class="bi bi-plus-lg"></i> Create First School
            </a>
        </div>
        <?php endif; ?>

        <?php if (!empty($schools)): ?>
        <div class="text-center mt-4">
            <a href="<?= site_url('schools/create') ?>" style="color:#6366f1;font-weight:500;font-size:0.9rem;text-decoration:none;">
                <i class="bi bi-plus-circle me-1"></i> Add another school
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
