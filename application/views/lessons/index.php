<div class="mb-3">
    <a href="javascript:history.back()" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;margin:0;"><i class="bi bi-book me-2" style="color:#6366f1;"></i><?= htmlspecialchars($class_program->subject_name) ?></h5>
        <p style="color:#64748b;font-size:0.85rem;margin:0.25rem 0 0 0;"><?= $class_program->section_name ?> &middot; <?= ucfirst($class_program->system_type) ?></p>
    </div>
    <?php if ($can_edit): ?>
    <a href="<?= site_url('lessons/create_module/' . $class_program->id) ?>" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Add Module
    </a>
    <?php endif; ?>
</div>

<?php if (empty($modules)): ?>
<div class="text-center py-5">
    <i class="bi bi-journal-text" style="font-size:3rem;color:#cbd5e1;"></i>
    <p style="color:#94a3b8;margin-top:0.75rem;">No modules yet.<?= $can_edit ? ' Create your first module.' : '' ?></p>
</div>
<?php else: ?>
    <?php foreach ($modules as $i => $module): ?>
    <div class="mb-3" style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
        <div class="d-flex justify-content-between align-items-center p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#module_<?= $module->id ?>">
            <div class="d-flex align-items-center gap-3">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.85rem;">
                    <?= $i + 1 ?>
                </div>
                <div>
                    <div style="font-weight:700;font-size:0.95rem;"><?= htmlspecialchars($module->title) ?></div>
                    <div style="color:#94a3b8;font-size:0.78rem;"><?= $module->lesson_count ?> lesson<?= $module->lesson_count != 1 ? 's' : '' ?>
                        <?php if (!$module->is_published): ?>
                            <span class="badge-status badge-inactive ms-1">Draft</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if ($can_edit): ?>
                <a href="<?= site_url('lessons/create_lesson/' . $module->id) ?>" class="btn-action" title="Add Lesson" onclick="event.stopPropagation();">
                    <i class="bi bi-plus-circle"></i>
                </a>
                <a href="<?= site_url('lessons/edit_module/' . $module->id) ?>" class="btn-action" title="Edit" onclick="event.stopPropagation();">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="<?= site_url('lessons/delete_module/' . $module->id) ?>" class="btn-action btn-delete" title="Delete" onclick="event.stopPropagation();return confirm('Delete this module and all its lessons?');">
                    <i class="bi bi-trash"></i>
                </a>
                <?php endif; ?>
                <i class="bi bi-chevron-down" style="color:#94a3b8;"></i>
            </div>
        </div>
        <div class="collapse <?= $i === 0 ? 'show' : '' ?>" id="module_<?= $module->id ?>">
            <?php if (empty($module->lessons)): ?>
                <div class="p-3 text-center" style="color:#94a3b8;font-size:0.85rem;">No lessons in this module.</div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($module->lessons as $lesson): ?>
                    <a href="<?= site_url('lessons/view/' . $lesson->id) ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-4" style="border:none;border-bottom:1px solid #f1f5f9;">
                        <div style="width:32px;height:32px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <?php
                            $icons = array('text' => 'bi-file-text', 'file' => 'bi-paperclip', 'video' => 'bi-play-circle', 'link' => 'bi-link-45deg');
                            $icon = isset($icons[$lesson->content_type]) ? $icons[$lesson->content_type] : 'bi-file-text';
                            ?>
                            <i class="bi <?= $icon ?>" style="color:#6366f1;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600;font-size:0.9rem;color:#0f172a;"><?= htmlspecialchars($lesson->title) ?></div>
                            <div style="font-size:0.75rem;color:#94a3b8;">
                                <?= ucfirst($lesson->content_type) ?>
                                <?= $lesson->duration_minutes ? ' &middot; ' . $lesson->duration_minutes . ' min' : '' ?>
                                <?php if (!$lesson->is_published): ?>
                                    <span class="badge-status badge-inactive ms-1">Draft</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($can_edit): ?>
                        <div class="d-flex gap-1" onclick="event.preventDefault();event.stopPropagation();">
                            <a href="<?= site_url('lessons/edit_lesson/' . $lesson->id) ?>" class="btn-action" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="<?= site_url('lessons/delete_lesson/' . $lesson->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this lesson?');"><i class="bi bi-trash"></i></a>
                        </div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
