<div class="mb-3">
    <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;margin:0;"><i class="bi bi-people-fill me-2" style="color:#6366f1;"></i>Participants</h5>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;"><?= htmlspecialchars($course->title) ?></p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Teachers -->
        <div class="mb-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-person-workspace me-2"></i>Teachers (<?= count($teachers) ?>)</h6>
            </div>
            <?php if (empty($teachers)): ?>
                <div class="p-3 text-center" style="color:#94a3b8;font-size:0.85rem;">No teachers assigned.</div>
            <?php else: ?>
                <?php foreach ($teachers as $t): ?>
                <div class="d-flex align-items-center justify-content-between p-3" style="border-bottom:1px solid #f1f5f9;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.75rem;">
                            <?= strtoupper(substr($t->name, 0, 2)) ?>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($t->name) ?></div>
                            <div style="color:#94a3b8;font-size:0.75rem;"><?= htmlspecialchars($t->email) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Students -->
        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-person-fill me-2"></i>Students (<?= count($students) ?>)</h6>
            </div>
            <?php if (empty($students)): ?>
                <div class="p-3 text-center" style="color:#94a3b8;font-size:0.85rem;">No students enrolled yet.</div>
            <?php else: ?>
                <?php foreach ($students as $s): ?>
                <div class="d-flex align-items-center justify-content-between p-3" style="border-bottom:1px solid #f1f5f9;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.75rem;">
                            <?= strtoupper(substr($s->name, 0, 2)) ?>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($s->name) ?></div>
                            <div style="color:#94a3b8;font-size:0.75rem;"><?= htmlspecialchars($s->email) ?></div>
                        </div>
                    </div>
                    <a href="<?= site_url('courses/unenroll/' . $course->id . '/' . $s->user_id) ?>" class="btn-action btn-delete" title="Remove" onclick="return confirm('Remove this student?');"><i class="bi bi-x-lg"></i></a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Enroll Panel -->
    <div class="col-lg-4">
        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-person-plus me-2"></i>Enroll Students</h6>
            </div>
            <form action="<?= site_url('courses/enroll/' . $course->id) ?>" method="post" class="p-3">
                <input type="hidden" name="role" value="student">
                <?php if (empty($available_students)): ?>
                    <p style="color:#94a3b8;font-size:0.85rem;margin:0;">All students are already enrolled.</p>
                <?php else: ?>
                    <div style="max-height:300px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:10px;padding:0.5rem;">
                        <?php foreach ($available_students as $as): ?>
                        <label class="d-flex align-items-center gap-2 p-2" style="cursor:pointer;border-radius:8px;font-size:0.85rem;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="user_ids[]" value="<?= $as->id ?>" class="form-check-input">
                            <span><?= htmlspecialchars($as->last_name . ', ' . $as->first_name) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn-primary-custom btn-sm mt-3 w-100"><i class="bi bi-plus-lg me-1"></i> Enroll Selected</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
