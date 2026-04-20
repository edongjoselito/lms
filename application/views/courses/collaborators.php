<div class="mb-3">
    <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;margin:0;"><i class="bi bi-people-fill me-2" style="color:#6366f1;"></i>Course Collaborators</h5>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;"><?= htmlspecialchars($course->title) ?></p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Collaborators List -->
        <div class="mb-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-person-workspace me-2"></i>Collaborators (<?= count($collaborators) ?>)</h6>
            </div>
            <?php if (empty($collaborators)): ?>
                <div class="p-3 text-center" style="color:#94a3b8;font-size:0.85rem;">No collaborators added yet.</div>
            <?php else: ?>
                <?php foreach ($collaborators as $c): ?>
                <div class="d-flex align-items-center justify-content-between p-3" style="border-bottom:1px solid #f1f5f9;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.75rem;">
                            <?= strtoupper(substr($c->name, 0, 2)) ?>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($c->name) ?></div>
                            <div style="color:#94a3b8;font-size:0.75rem;"><?= htmlspecialchars($c->email) ?></div>
                            <?php if ($c->section_name): ?>
                                <div style="color:#6366f1;font-size:0.75rem;font-weight:500;"><i class="bi bi-collection me-1"></i><?= htmlspecialchars($c->section_name) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="<?= site_url('courses/remove_collaborator/' . $course->id . '/' . $c->teacher_id) ?>" class="btn-action btn-delete" title="Remove" onclick="return confirm('Remove this collaborator?');"><i class="bi bi-x-lg"></i></a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Section Enrollment Keys -->
        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-key-fill me-2"></i>Section Enrollment Keys</h6>
            </div>
            <?php if (empty($section_keys)): ?>
                <div class="p-3 text-center" style="color:#94a3b8;font-size:0.85rem;">No section keys set yet.</div>
            <?php else: ?>
                <?php foreach ($section_keys as $sk): ?>
                <div class="d-flex align-items-center justify-content-between p-3" style="border-bottom:1px solid #f1f5f9;">
                    <div>
                        <div style="font-weight:600;font-size:0.9rem;"><?= htmlspecialchars($sk->section_name) ?></div>
                        <div style="color:#94a3b8;font-size:0.75rem;">Key: <code style="background:#f1f5f9;padding:0.2rem 0.4rem;border-radius:4px;"><?= htmlspecialchars($sk->enrollment_key) ?></code></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Collaborator & Set Section Key -->
    <div class="col-lg-4">
        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-person-plus me-2"></i>Add Collaborator</h6>
            </div>
            <form action="<?= site_url('courses/add_collaborator/' . $course->id) ?>" method="post" class="p-3">
                <div class="mb-3">
                    <label style="font-size:0.85rem;font-weight:600;color:#334155;">Teacher</label>
                    <select class="form-select" name="teacher_id" required style="font-size:0.85rem;">
                        <option value="">Select a teacher</option>
                        <?php foreach ($available_teachers as $at): ?>
                            <option value="<?= $at->id ?>"><?= htmlspecialchars($at->name) ?> (<?= htmlspecialchars($at->email) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label style="font-size:0.85rem;font-weight:600;color:#334155;">Section (Optional)</label>
                    <select class="form-select" name="section_id" style="font-size:0.85rem;">
                        <option value="">All sections</option>
                        <?php foreach ($sections as $s): ?>
                            <option value="<?= $s->id ?>"><?= htmlspecialchars($s->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color:#94a3b8;font-size:0.75rem;">Collaborator will only manage students in this section</small>
                </div>
                <button type="submit" class="btn-primary-custom w-100"><i class="bi bi-plus-lg"></i> Add Collaborator</button>
            </form>
        </div>

        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;margin-top:1rem;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-key me-2"></i>Set Section Key</h6>
            </div>
            <form action="<?= site_url('courses/set_section_key/' . $course->id) ?>" method="post" class="p-3">
                <div class="mb-3">
                    <label style="font-size:0.85rem;font-weight:600;color:#334155;">Section</label>
                    <select class="form-select" name="section_id" required style="font-size:0.85rem;">
                        <option value="">Select a section</option>
                        <?php foreach ($sections as $s): ?>
                            <option value="<?= $s->id ?>"><?= htmlspecialchars($s->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label style="font-size:0.85rem;font-weight:600;color:#334155;">Enrollment Key</label>
                    <input type="text" class="form-control" name="enrollment_key" placeholder="Enter enrollment key" required style="font-size:0.85rem;">
                    <small style="color:#94a3b8;font-size:0.75rem;">Students in this section must use this key to enroll</small>
                </div>
                <button type="submit" class="btn-primary-custom w-100"><i class="bi bi-check-lg"></i> Set Key</button>
            </form>
        </div>
    </div>
</div>
