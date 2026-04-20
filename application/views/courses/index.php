<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;margin:0;"><i class="bi bi-mortarboard-fill me-2" style="color:#6366f1;"></i>Courses</h5>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;">
            <?= $can_create ? 'Manage your courses, lessons, and assessments.' : 'Browse and access your courses.' ?>
        </p>
    </div>
    <?php if ($can_create): ?>
    <a href="<?= site_url('courses/create') ?>" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Create Course
    </a>
    <?php endif; ?>
</div>

<?php
$is_student = (isset($available_courses));
$all_courses = isset($courses) ? $courses : array();
if (isset($enrolled_courses)) {
    foreach ($enrolled_courses as $ec) {
        $found = false;
        foreach ($all_courses as $c) { if ($c->id == $ec->id) { $found = true; break; } }
        if (!$found) $all_courses[] = $ec;
    }
}
?>

<!-- My Courses (enrolled) -->
<?php if ($is_student): ?>
<h6 style="font-weight:700;color:#0f172a;margin-bottom:1rem;"><i class="bi bi-bookmark-check-fill me-2" style="color:#10b981;"></i>My Courses</h6>
<?php endif; ?>

<?php if (empty($all_courses)): ?>
<div class="text-center py-4 mb-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
    <i class="bi bi-mortarboard" style="font-size:2.5rem;color:#cbd5e1;"></i>
    <p style="color:#94a3b8;margin-top:0.75rem;margin-bottom:0;">
        <?php if ($is_student): ?>
            You haven't enrolled in any courses yet. Browse available courses below.
        <?php else: ?>
            No courses yet.<?= $can_create ? ' Create your first course.' : '' ?>
        <?php endif; ?>
    </p>
</div>
<?php else: ?>
<div class="row g-3 mb-4">
    <?php foreach ($all_courses as $c): ?>
    <div class="col-md-6 col-lg-4">
        <a href="<?= site_url('courses/view/' . $c->id) ?>" style="text-decoration:none;">
            <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;height:100%;transition:all 0.2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(99,102,241,0.12)';this.style.borderColor='#6366f1';" onmouseout="this.style.boxShadow='none';this.style.borderColor='#e2e8f0';">
                <div style="height:8px;background:linear-gradient(135deg,#6366f1,#8b5cf6);"></div>
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <?php if ($c->code): ?>
                            <span style="background:#f1f5f9;color:#475569;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.72rem;font-weight:600;"><?= htmlspecialchars($c->code) ?></span>
                        <?php else: ?>
                            <span></span>
                        <?php endif; ?>
                        <?php if (!$c->is_published): ?>
                            <span class="badge-status badge-inactive">Draft</span>
                        <?php endif; ?>
                    </div>
                    <h6 style="font-weight:700;color:#0f172a;margin:0.5rem 0 0.25rem 0;"><?= htmlspecialchars($c->title) ?></h6>
                    <?php if ($c->description): ?>
                        <p style="color:#64748b;font-size:0.82rem;margin-bottom:0.75rem;line-height:1.5;"><?= character_limiter(strip_tags($c->description), 100) ?></p>
                    <?php endif; ?>
                    <div style="font-size:0.78rem;color:#94a3b8;">
                        <?php if ($c->category): ?>
                            <span><i class="bi bi-tag me-1"></i><?= htmlspecialchars($c->category) ?></span>
                        <?php endif; ?>
                        <?php if (isset($c->creator_name) && $c->creator_name): ?>
                            <span class="ms-2"><i class="bi bi-person me-1"></i><?= $c->creator_name ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Available Courses (for students to self-enroll) -->
<?php if ($is_student && !empty($available_courses)): ?>
<h6 style="font-weight:700;color:#0f172a;margin-bottom:1rem;"><i class="bi bi-search me-2" style="color:#6366f1;"></i>Available Courses</h6>
<div class="row g-3">
    <?php foreach ($available_courses as $ac): ?>
    <div class="col-md-6 col-lg-4">
        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;height:100%;transition:all 0.2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(99,102,241,0.12)';this.style.borderColor='#6366f1';" onmouseout="this.style.boxShadow='none';this.style.borderColor='#e2e8f0';">
            <div style="height:8px;background:linear-gradient(135deg,#f59e0b,#f97316);"></div>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <?php if ($ac->code): ?>
                        <span style="background:#f1f5f9;color:#475569;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.72rem;font-weight:600;"><?= htmlspecialchars($ac->code) ?></span>
                    <?php else: ?>
                        <span></span>
                    <?php endif; ?>
                </div>
                <h6 style="font-weight:700;color:#0f172a;margin:0.5rem 0 0.25rem 0;"><?= htmlspecialchars($ac->title) ?></h6>
                <?php if ($ac->description): ?>
                    <p style="color:#64748b;font-size:0.82rem;margin-bottom:0.75rem;line-height:1.5;"><?= character_limiter(strip_tags($ac->description), 100) ?></p>
                <?php endif; ?>
                <div style="font-size:0.78rem;color:#94a3b8;margin-bottom:0.75rem;">
                    <?php if ($ac->category): ?>
                        <span><i class="bi bi-tag me-1"></i><?= htmlspecialchars($ac->category) ?></span>
                    <?php endif; ?>
                    <?php if ($ac->creator_name): ?>
                        <span class="ms-2"><i class="bi bi-person me-1"></i><?= $ac->creator_name ?></span>
                    <?php endif; ?>
                </div>
                <a href="<?= site_url('courses/self_enroll/' . $ac->id) ?>" class="btn-primary-custom btn-sm" onclick="return confirm('Enroll in this course?');">
                    <i class="bi bi-plus-circle me-1"></i> Enroll
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($is_student && empty($all_courses) && empty($available_courses)): ?>
<div class="text-center py-5">
    <i class="bi bi-mortarboard" style="font-size:3rem;color:#cbd5e1;"></i>
    <p style="color:#94a3b8;margin-top:0.75rem;">No courses are available right now. Check back later.</p>
</div>
<?php endif; ?>
