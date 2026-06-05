<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="lp-page">

    <!-- Breadcrumb -->
    <div class="lp-top-actions">
        <a href="<?= $back_url ?>" class="lp-back">
            <i class="bi bi-arrow-left-short"></i> <?= htmlspecialchars($back_label) ?>
        </a>
    </div>

    <!-- Hero header -->
    <div class="lp-hero">
        <div class="lp-hero-bg"></div>
        <div class="lp-hero-content">
            <div class="lp-hero-left">
                <div class="lp-hero-avatar">LP</div>
                <div class="lp-hero-info">
                    <div class="lp-hero-meta">
                        <span class="lp-tag lp-tag-code"><?= htmlspecialchars($subject->code) ?></span>
                        <span class="lp-tag lp-tag-lesson"><?= htmlspecialchars($lesson->title) ?></span>
                    </div>
                    <h1 class="lp-hero-title">ILAW Lesson Plan</h1>
                    <p class="lp-hero-desc">Instructional Learning and Assessment Worksheet</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="lp-layout">
        <div class="lp-card">
            <div class="lp-card-head">
                <div class="lp-card-title">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Lesson Plan Details</span>
                </div>
                <?php if ($can_edit): ?>
                    <?php if ($lesson_plan): ?>
                        <a href="<?= $delete_url ?>" class="lp-btn lp-btn--danger" onclick="return confirm('Delete this lesson plan?');">
                            <i class="bi bi-trash3-fill"></i> Delete
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if ($lesson_plan): ?>
                <!-- View Mode -->
                <div class="lp-content">
                    <div class="lp-section">
                        <h3 class="lp-section-title"><i class="bi bi-bullseye"></i> Objectives</h3>
                        <div class="lp-section-content"><?= nl2br(htmlspecialchars($lesson_plan->objectives ?? '')) ?></div>
                    </div>

                    <div class="lp-section">
                        <h3 class="lp-section-title"><i class="bi bi-book"></i> Subject Matter</h3>
                        <div class="lp-section-content"><?= nl2br(htmlspecialchars($lesson_plan->subject_matter ?? '')) ?></div>
                    </div>

                    <div class="lp-section">
                        <h3 class="lp-section-title"><i class="bi bi-box-seam"></i> Materials</h3>
                        <div class="lp-section-content"><?= nl2br(htmlspecialchars($lesson_plan->materials ?? '')) ?></div>
                    </div>

                    <div class="lp-section">
                        <h3 class="lp-section-title"><i class="bi bi-list-ol"></i> Procedures</h3>
                        <div class="lp-section-content"><?= nl2br(htmlspecialchars($lesson_plan->procedures ?? '')) ?></div>
                    </div>

                    <div class="lp-section">
                        <h3 class="lp-section-title"><i class="bi bi-clipboard-check"></i> Evaluation</h3>
                        <div class="lp-section-content"><?= nl2br(htmlspecialchars($lesson_plan->evaluation ?? '')) ?></div>
                    </div>

                    <div class="lp-section">
                        <h3 class="lp-section-title"><i class="bi bi-pencil-square"></i> Assignment</h3>
                        <div class="lp-section-content"><?= nl2br(htmlspecialchars($lesson_plan->assignment ?? '')) ?></div>
                    </div>

                    <?php if (!empty($lesson_plan->remarks)): ?>
                        <div class="lp-section">
                            <h3 class="lp-section-title"><i class="bi bi-chat-text"></i> Remarks</h3>
                            <div class="lp-section-content"><?= nl2br(htmlspecialchars($lesson_plan->remarks)) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($can_edit): ?>
                        <div class="lp-actions">
                            <button type="button" class="lp-btn lp-btn--primary" onclick="editLessonPlan()">
                                <i class="bi bi-pencil-fill"></i> Edit Lesson Plan
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="lp-empty">
                    <div class="lp-empty-icon">
                        <i class="bi bi-file-earmark-plus"></i>
                    </div>
                    <div class="lp-empty-title">No lesson plan yet</div>
                    <div class="lp-empty-sub">
                        <?php if ($can_edit): ?>
                            Click "Create Lesson Plan" to create an ILAW lesson plan for this lesson.
                        <?php else: ?>
                            No lesson plan has been created for this lesson yet.
                        <?php endif; ?>
                    </div>
                    <?php if ($can_edit): ?>
                        <button type="button" class="lp-btn lp-btn--primary" onclick="showCreateForm()">
                            <i class="bi bi-plus-lg"></i> Create Lesson Plan
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Form Modal -->
<?php if ($can_edit): ?>
    <div class="modal fade" id="lpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lpModalTitle"><?= $lesson_plan ? 'Edit Lesson Plan' : 'Create Lesson Plan' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open($lesson_plan ? $update_url : $create_url, ['id' => 'lpForm']) ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Objectives</label>
                            <textarea class="form-control" name="objectives" rows="3" placeholder="Enter the learning objectives..."><?= $lesson_plan ? htmlspecialchars($lesson_plan->objectives ?? '') : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Matter</label>
                            <textarea class="form-control" name="subject_matter" rows="3" placeholder="Enter the subject matter..."><?= $lesson_plan ? htmlspecialchars($lesson_plan->subject_matter ?? '') : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Materials</label>
                            <textarea class="form-control" name="materials" rows="2" placeholder="List the materials needed..."><?= $lesson_plan ? htmlspecialchars($lesson_plan->materials ?? '') : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Procedures</label>
                            <textarea class="form-control" name="procedures" rows="4" placeholder="Describe the teaching procedures..."><?= $lesson_plan ? htmlspecialchars($lesson_plan->procedures ?? '') : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Evaluation</label>
                            <textarea class="form-control" name="evaluation" rows="3" placeholder="Describe the evaluation methods..."><?= $lesson_plan ? htmlspecialchars($lesson_plan->evaluation ?? '') : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assignment</label>
                            <textarea class="form-control" name="assignment" rows="2" placeholder="Enter the assignment..."><?= $lesson_plan ? htmlspecialchars($lesson_plan->assignment ?? '') : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="2" placeholder="Any additional remarks..."><?= $lesson_plan ? htmlspecialchars($lesson_plan->remarks ?? '') : '' ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?= $lesson_plan ? 'Update Lesson Plan' : 'Create Lesson Plan' ?></button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
<?php if ($can_edit): ?>
var lpModal = new bootstrap.Modal(document.getElementById('lpModal'));

function showCreateForm() {
    lpModal.show();
}

function editLessonPlan() {
    lpModal.show();
}
<?php endif; ?>
</script>

<style>
.lp-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

.lp-top-actions {
    margin-bottom: 1.5rem;
}

.lp-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}
.lp-back:hover { background: #dbeafe; color: #1d4ed8; text-decoration: none; }

.lp-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}
.lp-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}
.lp-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.lp-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}
.lp-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}
.lp-hero-avatar {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.3);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 1px;
}
.lp-hero-info { min-width: 0; }
.lp-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}
.lp-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}
.lp-tag-code { background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); }
.lp-tag-lesson { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.25); }
.lp-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}
.lp-hero-desc {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.72);
    margin: 0;
}

.lp-layout { display: grid; gap: 1.5rem; }
.lp-card {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}
.lp-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    flex-wrap: wrap;
}
.lp-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}
.lp-card-title i { color: #2563eb; font-size: 1rem; }

.lp-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}
.lp-btn--primary {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(59,130,246,0.35);
}
.lp-btn--primary:hover {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59,130,246,0.3);
}
.lp-btn--danger {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(220,38,38,0.35);
}
.lp-btn--danger:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220,38,38,0.3);
}

.lp-content { padding: 1.5rem; }
.lp-section {
    margin-bottom: 2rem;
}
.lp-section:last-child { margin-bottom: 0; }
.lp-section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.75rem;
}
.lp-section-title i { color: #2563eb; }
.lp-section-content {
    padding: 1rem 1.25rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
    color: #334155;
    line-height: 1.7;
    white-space: pre-wrap;
}

.lp-actions {
    display: flex;
    justify-content: center;
    padding-top: 1.5rem;
    border-top: 1px solid #f1f5f9;
}

.lp-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4rem 2rem;
    text-align: center;
}
.lp-empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
    font-size: 1.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}
.lp-empty-title { font-size: 1rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; }
.lp-empty-sub { font-size: 0.85rem; color: #94a3b8; max-width: 320px; line-height: 1.5; margin-bottom: 1.5rem; }

@media (max-width: 768px) {
    .lp-hero-content { padding: 1.5rem; }
    .lp-hero-title { font-size: 1.25rem; }
    .lp-content { padding: 1rem; }
    .lp-section-content { padding: 0.875rem 1rem; }
}
</style>
