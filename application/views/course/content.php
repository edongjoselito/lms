<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('course/subjects') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Subjects
            </a>
        </div>
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-book-fill me-2"></i><?= $subject->code ?> - <?= $subject->description ?></h5>
                <div>
                    <?php if ($edit_mode): ?>
                        <a href="<?= site_url('course/content/' . $subject->id) ?>" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="bi bi-eye me-1"></i> View Mode
                        </a>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                            <i class="bi bi-plus-lg me-1"></i> Add Module
                        </button>
                    <?php else: ?>
                        <a href="<?= site_url('course/content/' . $subject->id . '?edit=1') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Edit Mode
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Main Content Area -->
    <div class="col-lg-9">
        <?php if (empty($modules)): ?>
            <div class="data-table">
                <div class="p-5 text-center" style="color:#94a3b8;">
                    <i class="bi bi-folder2-open" style="font-size:4rem;display:block;margin-bottom:1.5rem;"></i>
                    <h5 style="color:#64748b;margin-bottom:1rem;">No Content Yet</h5>
                    <p style="max-width:400px;margin:0 auto 1.5rem;">Start building your course by adding modules. Each module can contain lessons, assignments, quizzes, and resources.</p>
                    <?php if ($edit_mode): ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                            <i class="bi bi-plus-lg me-2"></i>Add First Module
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($modules as $module_index => $module): ?>
                <!-- Module/Topic Section -->
                <div class="data-table mb-4" id="module-<?= $module->id ?>">
                    <!-- Module Header -->
                    <div class="table-header" style="background:#f8fafc;">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2"><?= $module_index + 1 ?></span>
                            <h5 class="mb-0"><?= $module->title ?></h5>
                            <?php if (!$module->is_published): ?>
                                <span class="badge bg-secondary ms-2" style="font-size:0.7rem;">Hidden</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($edit_mode): ?>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#editModule<?= $module->id ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="editModule<?= $module->id ?>"><i class="bi bi-pencil me-2"></i>Edit Module</a></li>
                                    <li><a class="dropdown-item" href="#addLesson<?= $module->id ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="addLesson<?= $module->id ?>"><i class="bi bi-file-text me-2"></i>Add Lesson</a></li>
                                    <li><a class="dropdown-item" href="#addActivity<?= $module->id ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="addActivity<?= $module->id ?>"><i class="bi bi-lightning me-2"></i>Add Activity</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?= site_url('course/delete_module/' . $module->id) ?>" onclick="return confirm('Delete this module and all its contents?')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($module->description): ?>
                        <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                            <p class="mb-0" style="color:#64748b;font-size:0.9rem;"><?= $module->description ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($edit_mode): ?>
                        <div class="collapse item-edit-panel" id="editModule<?= $module->id ?>">
                            <form action="<?= site_url('course/edit_module/' . $module->id) ?>" method="post" class="module-add-form">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1"><i class="bi bi-pencil me-2"></i>Edit Module</h6>
                                        <p class="text-muted mb-0 small"><?= $subject->code ?> - <?= $subject->description ?></p>
                                    </div>
                                    <a href="#editModule<?= $module->id ?>" class="btn-close" data-bs-toggle="collapse" role="button" aria-label="Close"></a>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($module->title ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="is_published" value="1" <?= $module->is_published ? 'checked' : '' ?> id="pubMod<?= $module->id ?>">
                                            <label class="form-check-label" for="pubMod<?= $module->id ?>">Published</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($module->description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end gap-2">
                                        <a href="#editModule<?= $module->id ?>" class="btn btn-secondary" data-bs-toggle="collapse" role="button">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Module Content -->
                    <div class="p-0">
                        <?php 
                        // Combine lessons and activities
                        $all_items = array();
                        foreach ($module->lessons as $lesson) {
                            $lesson->item_type = 'lesson';
                            $all_items[] = $lesson;
                        }
                        foreach ($module->activities as $activity) {
                            $activity->item_type = 'activity';
                            $all_items[] = $activity;
                        }
                        usort($all_items, function($a, $b) {
                            return $a->order_num - $b->order_num;
                        });
                        ?>
                        
                        <?php if (empty($all_items)): ?>
                            <div class="p-4 text-center" style="color:#94a3b8;">
                                <p class="mb-0">No content in this module yet.</p>
                                <?php if ($edit_mode): ?>
                                    <div class="mt-3">
                                        <a href="#addLesson<?= $module->id ?>" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="addLesson<?= $module->id ?>">
                                            <i class="bi bi-file-text me-1"></i>Add Lesson
                                        </a>
                                        <a href="#addActivity<?= $module->id ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="addActivity<?= $module->id ?>">
                                            <i class="bi bi-lightning me-1"></i>Add Activity
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($all_items as $item): ?>
                                    <?php $item_url = site_url('course/' . ($item->item_type === 'lesson' ? 'lesson' : 'activity') . '/' . $item->id); ?>
                                    <div class="list-group-item p-3 d-flex align-items-center justify-content-between <?= (!$item->is_published && $edit_mode) ? 'bg-light' : '' ?>">
                                        <a href="<?= $item_url ?>" class="content-item-link d-flex align-items-center flex-grow-1">
                                            <?php if ($item->item_type === 'lesson'): ?>
                                                <div class="activity-icon me-3" style="width:40px;height:40px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;color:#1e40af;">
                                                    <i class="bi bi-file-text"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1"><?= $item->title ?></h6>
                                                    <small class="text-muted">
                                                        <span class="badge bg-light text-dark border">Lesson</span>
                                                        <?php if ($item->duration_minutes): ?>
                                                            <span class="ms-1"><i class="bi bi-clock"></i> <?= $item->duration_minutes ?> min</span>
                                                        <?php endif; ?>
                                                        <?php if (!$item->is_published): ?>
                                                            <span class="ms-1 badge bg-secondary">Hidden</span>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            <?php else: ?>
                                                <?php 
                                                $activity_icons = array(
                                                    'assignment' => array('icon' => 'bi-clipboard-check', 'color' => '#dcfce7', 'icon_color' => '#166534', 'label' => 'Assignment'),
                                                    'quiz' => array('icon' => 'bi-question-circle', 'color' => '#fef3c7', 'icon_color' => '#92400e', 'label' => 'Quiz'),
                                                    'forum' => array('icon' => 'bi-chat-dots', 'color' => '#ede9fe', 'icon_color' => '#6d28d9', 'label' => 'Forum'),
                                                    'resource' => array('icon' => 'bi-link', 'color' => '#e0f2fe', 'icon_color' => '#0369a1', 'label' => 'Resource'),
                                                    'page' => array('icon' => 'bi-file-earmark', 'color' => '#f3f4f6', 'icon_color' => '#374151', 'label' => 'Page'),
                                                    'label' => array('icon' => 'bi-tag', 'color' => '#fce7f3', 'icon_color' => '#be185d', 'label' => 'Label'),
                                                );
                                                $icon_info = $activity_icons[$item->type] ?? $activity_icons['page'];
                                                ?>
                                                <div class="activity-icon me-3" style="width:40px;height:40px;border-radius:8px;background:<?= $icon_info['color'] ?>;display:flex;align-items:center;justify-content:center;color:<?= $icon_info['icon_color'] ?>;">
                                                    <i class="bi <?= $icon_info['icon'] ?>"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1"><?= $item->title ?></h6>
                                                    <small class="text-muted">
                                                        <span class="badge bg-light text-dark border"><?= $icon_info['label'] ?></span>
                                                        <?php if (!$item->is_published): ?>
                                                            <span class="ms-1 badge bg-secondary">Hidden</span>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                        
                                        <?php if ($edit_mode): ?>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="<?= $item_url ?>"><i class="bi bi-eye me-2"></i>View</a></li>
                                                    <?php if ($item->item_type === 'lesson'): ?>
                                                        <li><a class="dropdown-item" href="#editLesson<?= $item->id ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="editLesson<?= $item->id ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                        <li><a class="dropdown-item text-danger" href="<?= site_url('course/delete_lesson/' . $item->id) ?>" onclick="return confirm('Delete this lesson?')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                                    <?php else: ?>
                                                        <li><a class="dropdown-item" href="#editActivity<?= $item->id ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="editActivity<?= $item->id ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                        <li><a class="dropdown-item text-danger" href="<?= site_url('course/delete_activity/' . $item->id) ?>" onclick="return confirm('Delete this activity?')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($item->item_type === 'lesson' && $edit_mode): ?>
                                        <div class="collapse item-edit-panel" id="editLesson<?= $item->id ?>">
                                            <form action="<?= site_url('course/edit_lesson/' . $item->id) ?>" method="post" class="module-add-form">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="mb-1"><i class="bi bi-pencil me-2"></i>Edit Lesson</h6>
                                                        <p class="text-muted mb-0 small"><?= $module->title ?></p>
                                                    </div>
                                                    <a href="#editLesson<?= $item->id ?>" class="btn-close" data-bs-toggle="collapse" role="button" aria-label="Close"></a>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($item->title ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Content Type</label>
                                                        <select class="form-select" name="content_type">
                                                            <option value="text" <?= $item->content_type == 'text' ? 'selected' : '' ?>>Text/HTML</option>
                                                            <option value="video" <?= $item->content_type == 'video' ? 'selected' : '' ?>>Video</option>
                                                            <option value="file" <?= $item->content_type == 'file' ? 'selected' : '' ?>>File</option>
                                                            <option value="link" <?= $item->content_type == 'link' ? 'selected' : '' ?>>External Link</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Duration (minutes)</label>
                                                        <input type="number" class="form-control" name="duration_minutes" value="<?= $item->duration_minutes ?>" min="0">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Content</label>
                                                        <textarea class="form-control wysiwyg-content" name="content" rows="4"><?= htmlspecialchars($item->content ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                                    </div>
                                                    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_published" value="1" <?= $item->is_published ? 'checked' : '' ?> id="published<?= $item->id ?>">
                                                            <label class="form-check-label" for="published<?= $item->id ?>">Published</label>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <a href="#editLesson<?= $item->id ?>" class="btn btn-secondary" data-bs-toggle="collapse" role="button">Cancel</a>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($item->item_type === 'activity' && $edit_mode): ?>
                                        <div class="collapse item-edit-panel" id="editActivity<?= $item->id ?>">
                                            <form action="<?= site_url('course/edit_activity/' . $item->id) ?>" method="post" class="module-add-form">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="mb-1"><i class="bi bi-pencil me-2"></i>Edit Activity</h6>
                                                        <p class="text-muted mb-0 small"><?= $module->title ?></p>
                                                    </div>
                                                    <a href="#editActivity<?= $item->id ?>" class="btn-close" data-bs-toggle="collapse" role="button" aria-label="Close"></a>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Type</label>
                                                        <select class="form-select" name="type">
                                                            <option value="assignment" <?= $item->type == 'assignment' ? 'selected' : '' ?>>Assignment</option>
                                                            <option value="quiz" <?= $item->type == 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                                            <option value="forum" <?= $item->type == 'forum' ? 'selected' : '' ?>>Forum</option>
                                                            <option value="resource" <?= $item->type == 'resource' ? 'selected' : '' ?>>Resource</option>
                                                            <option value="page" <?= $item->type == 'page' ? 'selected' : '' ?>>Page</option>
                                                            <option value="label" <?= $item->type == 'label' ? 'selected' : '' ?>>Label</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($item->title ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control wysiwyg-content" name="content" rows="3"><?= htmlspecialchars($item->content ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                                    </div>
                                                    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_published" value="1" <?= $item->is_published ? 'checked' : '' ?> id="actPublished<?= $item->id ?>">
                                                            <label class="form-check-label" for="actPublished<?= $item->id ?>">Published</label>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <a href="#editActivity<?= $item->id ?>" class="btn btn-secondary" data-bs-toggle="collapse" role="button">Cancel</a>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($edit_mode): ?>
                        <div class="module-add-panels border-top" id="moduleAddPanels<?= $module->id ?>">
                            <div class="collapse" id="addLesson<?= $module->id ?>" data-bs-parent="#moduleAddPanels<?= $module->id ?>">
                                <form action="<?= site_url('course/create_lesson/' . $module->id) ?>" method="post" class="module-add-form">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1"><i class="bi bi-file-text me-2"></i>Add Lesson</h6>
                                            <p class="text-muted mb-0 small">Add lesson content to "<?= $module->title ?>".</p>
                                        </div>
                                        <a href="#addLesson<?= $module->id ?>" class="btn-close" data-bs-toggle="collapse" role="button" aria-label="Close"></a>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" required placeholder="e.g., Introduction to Topic">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Content Type</label>
                                            <select class="form-select" name="content_type">
                                                <option value="text">Text/HTML</option>
                                                <option value="video">Video</option>
                                                <option value="file">File</option>
                                                <option value="link">External Link</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Duration (minutes)</label>
                                            <input type="number" class="form-control" name="duration_minutes" min="0" placeholder="e.g., 30">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Content</label>
                                            <textarea class="form-control wysiwyg-content" name="content" rows="4" placeholder="Enter lesson content..."></textarea>
                                        </div>
                                        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="pubLesson<?= $module->id ?>">
                                                <label class="form-check-label" for="pubLesson<?= $module->id ?>">Publish immediately</label>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="#addLesson<?= $module->id ?>" class="btn btn-secondary" data-bs-toggle="collapse" role="button">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Add Lesson</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="collapse" id="addActivity<?= $module->id ?>" data-bs-parent="#moduleAddPanels<?= $module->id ?>">
                                <form action="<?= site_url('course/create_activity/' . $module->id) ?>" method="post" class="module-add-form">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1"><i class="bi bi-lightning me-2"></i>Add Activity</h6>
                                            <p class="text-muted mb-0 small">Add an activity to "<?= $module->title ?>".</p>
                                        </div>
                                        <a href="#addActivity<?= $module->id ?>" class="btn-close" data-bs-toggle="collapse" role="button" aria-label="Close"></a>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Activity Type</label>
                                            <select class="form-select" name="type">
                                                <option value="assignment">Assignment - Student submission task</option>
                                                <option value="quiz">Quiz - Assessment with questions</option>
                                                <option value="forum">Forum - Discussion board</option>
                                                <option value="resource">Resource - File or link</option>
                                                <option value="page">Page - Standalone content</option>
                                                <option value="label">Label - Section divider/text</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" required placeholder="e.g., Week 1 Assignment">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description/Instructions</label>
                                            <textarea class="form-control wysiwyg-content" name="content" rows="3" placeholder="Enter instructions or description..."></textarea>
                                        </div>
                                        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="pubAct<?= $module->id ?>">
                                                <label class="form-check-label" for="pubAct<?= $module->id ?>">Publish immediately</label>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="#addActivity<?= $module->id ?>" class="btn btn-secondary" data-bs-toggle="collapse" role="button">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Add Activity</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-3">
        <!-- Course Structure -->
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1rem;font-size:1rem;">
                <i class="bi bi-diagram-3 me-2" style="color:#6366f1;"></i>Structure
            </h5>
            <?php if (empty($modules)): ?>
                <p class="text-muted" style="font-size:0.85rem;">No modules yet.</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($modules as $idx => $mod): ?>
                        <a href="#module-<?= $mod->id ?>" class="list-group-item list-group-item-action py-2 px-0 border-0" style="font-size:0.85rem;">
                            <span class="badge bg-light text-dark me-1"><?= $idx + 1 ?></span>
                            <?= $mod->title ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Module Modal -->
<?php if ($edit_mode): ?>
<div class="modal fade" id="addModuleModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= site_url('course/create_module/' . $subject->id) ?>" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" required placeholder="e.g., Week 1: Introduction">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Brief description of this module..."></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="pubNewMod">
                    <label class="form-check-label" for="pubNewMod">Publish immediately</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Module</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<style>
.activity-icon {
    flex-shrink: 0;
}
.list-group-item:hover {
    background-color: #f8fafc;
}
.content-item-link {
    color: inherit;
    min-width: 0;
    text-decoration: none;
}
.content-item-link h6 {
    color: #1e293b;
}
.content-item-link:hover h6 {
    color: #4f46e5;
    text-decoration: underline;
}
.module-add-panels {
    background: #f8fafc;
}
.item-edit-panel {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}
.module-add-form {
    padding: 1rem;
}
.module-add-form h6 {
    color: #334155;
    font-weight: 700;
}
.wysiwyg-source {
    display: none;
}
.wysiwyg-editor {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background: #fff;
    overflow: hidden;
}
.wysiwyg-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.wysiwyg-toolbar-group {
    display: inline-flex;
    gap: 0.25rem;
    padding-right: 0.35rem;
    margin-right: 0.1rem;
    border-right: 1px solid #e2e8f0;
}
.wysiwyg-toolbar-group:last-child {
    border-right: 0;
}
.wysiwyg-toolbar .btn {
    width: 2rem;
    height: 2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}
.wysiwyg-format {
    width: auto;
    min-width: 7.25rem;
    height: 2rem;
    font-size: 0.85rem;
}
.wysiwyg-toolbar .btn.active {
    background: #e0e7ff;
    border-color: #818cf8 !important;
    color: #3730a3;
}
.wysiwyg-area {
    min-height: 140px;
    padding: 0.75rem;
    outline: none;
    overflow-wrap: anywhere;
}
.wysiwyg-area:empty::before {
    content: attr(data-placeholder);
    color: #94a3b8;
}
.wysiwyg-area blockquote {
    margin: 0 0 1rem;
    padding: 0.75rem 1rem;
    border-left: 4px solid #c7d2fe;
    background: #f8fafc;
    color: #475569;
}
.wysiwyg-area pre {
    padding: 0.75rem;
    border-radius: 0.375rem;
    background: #0f172a;
    color: #e2e8f0;
    overflow-x: auto;
}
.wysiwyg-source-visible {
    display: block;
    min-height: 180px;
    border: 0;
    border-radius: 0;
    border-top: 1px solid #e2e8f0;
    font-family: SFMono-Regular, Consolas, "Liberation Mono", monospace;
    font-size: 0.85rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function escapeAttribute(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function normalizeUrl(url) {
        if (!url) {
            return '';
        }

        if (/^(https?:\/\/|mailto:|tel:|#|\/)/i.test(url)) {
            return url;
        }

        return 'https://' + url;
    }

    function runCommand(command, value) {
        document.execCommand(command, false, value || null);
    }

    function insertPlainText(text) {
        if (document.queryCommandSupported && document.queryCommandSupported('insertText')) {
            runCommand('insertText', text);
            return;
        }

        runCommand('insertHTML', text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\n/g, '<br>'));
    }

    document.querySelectorAll('textarea.wysiwyg-content').forEach(function (textarea, index) {
        if (textarea.dataset.wysiwygReady === '1') {
            return;
        }

        textarea.dataset.wysiwygReady = '1';
        textarea.classList.add('wysiwyg-source');

        var editor = document.createElement('div');
        editor.className = 'wysiwyg-editor';
        var placeholder = escapeAttribute(textarea.getAttribute('placeholder') || 'Enter content...');
        editor.innerHTML =
            '<div class="wysiwyg-toolbar" role="toolbar" aria-label="Content formatting">' +
                '<span class="wysiwyg-toolbar-group">' +
                    '<select class="form-select form-select-sm wysiwyg-format" title="Format">' +
                        '<option value="p">Paragraph</option>' +
                        '<option value="h2">Heading 2</option>' +
                        '<option value="h3">Heading 3</option>' +
                        '<option value="blockquote">Quote</option>' +
                        '<option value="pre">Code block</option>' +
                    '</select>' +
                '</span>' +
                '<span class="wysiwyg-toolbar-group">' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="undo" title="Undo"><i class="bi bi-arrow-counterclockwise"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="redo" title="Redo"><i class="bi bi-arrow-clockwise"></i></button>' +
                '</span>' +
                '<span class="wysiwyg-toolbar-group">' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="bold" title="Bold"><i class="bi bi-type-bold"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="italic" title="Italic"><i class="bi bi-type-italic"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="underline" title="Underline"><i class="bi bi-type-underline"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="strikeThrough" title="Strikethrough"><i class="bi bi-type-strikethrough"></i></button>' +
                '</span>' +
                '<span class="wysiwyg-toolbar-group">' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="justifyLeft" title="Align left"><i class="bi bi-text-left"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="justifyCenter" title="Align center"><i class="bi bi-text-center"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="justifyRight" title="Align right"><i class="bi bi-text-right"></i></button>' +
                '</span>' +
                '<span class="wysiwyg-toolbar-group">' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="insertUnorderedList" title="Bulleted list"><i class="bi bi-list-ul"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="insertOrderedList" title="Numbered list"><i class="bi bi-list-ol"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="outdent" title="Decrease indent"><i class="bi bi-text-indent-left"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="indent" title="Increase indent"><i class="bi bi-text-indent-right"></i></button>' +
                '</span>' +
                '<span class="wysiwyg-toolbar-group">' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="createLink" title="Insert link"><i class="bi bi-link-45deg"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="unlink" title="Remove link"><i class="bi bi-link"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="insertHorizontalRule" title="Divider"><i class="bi bi-hr"></i></button>' +
                '</span>' +
                '<span class="wysiwyg-toolbar-group">' +
                    '<button type="button" class="btn btn-sm btn-light border" data-command="removeFormat" title="Clear formatting"><i class="bi bi-eraser"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-light border" data-source-toggle="1" title="HTML source"><i class="bi bi-code-slash"></i></button>' +
                '</span>' +
            '</div>' +
            '<div class="wysiwyg-area" contenteditable="true" data-placeholder="' + placeholder + '"></div>';

        textarea.parentNode.insertBefore(editor, textarea.nextSibling);

        var area = editor.querySelector('.wysiwyg-area');
        var formatSelect = editor.querySelector('.wysiwyg-format');
        var sourceToggle = editor.querySelector('[data-source-toggle]');
        var isSourceMode = false;

        area.innerHTML = textarea.value;
        area.id = 'wysiwygContent' + index;

        function syncFromEditor() {
            if (!isSourceMode) {
                textarea.value = area.innerHTML;
            }
        }

        function syncToEditor() {
            area.innerHTML = textarea.value;
        }

        function refreshToolbarState() {
            editor.querySelectorAll('[data-command]').forEach(function (button) {
                var command = button.dataset.command;
                if (!command || ['undo', 'redo', 'createLink', 'unlink', 'insertHorizontalRule', 'removeFormat', 'indent', 'outdent'].includes(command)) {
                    return;
                }

                try {
                    button.classList.toggle('active', document.queryCommandState(command));
                } catch (e) {
                    button.classList.remove('active');
                }
            });
        }

        function applyFormat(tagName) {
            area.focus();
            runCommand('formatBlock', tagName);
            syncFromEditor();
            refreshToolbarState();
        }

        formatSelect.addEventListener('change', function () {
            applyFormat(formatSelect.value);
        });

        editor.querySelectorAll('[data-command]').forEach(function (button) {
            button.addEventListener('click', function () {
                var command = button.dataset.command;

                if (isSourceMode && command !== 'removeFormat') {
                    return;
                }

                area.focus();

                if (command === 'createLink') {
                    var url = normalizeUrl(window.prompt('Enter link URL'));
                    if (!url) {
                        return;
                    }
                    runCommand(command, url);
                } else {
                    runCommand(command);
                }

                syncFromEditor();
                refreshToolbarState();
            });
        });

        sourceToggle.addEventListener('click', function () {
            isSourceMode = !isSourceMode;
            sourceToggle.classList.toggle('active', isSourceMode);
            area.hidden = isSourceMode;
            textarea.classList.toggle('wysiwyg-source', !isSourceMode);
            textarea.classList.toggle('wysiwyg-source-visible', isSourceMode);

            if (isSourceMode) {
                textarea.value = area.innerHTML;
                textarea.focus();
            } else {
                syncToEditor();
                area.focus();
            }
        });

        area.addEventListener('input', function () {
            syncFromEditor();
            refreshToolbarState();
        });

        area.addEventListener('keyup', refreshToolbarState);
        area.addEventListener('mouseup', refreshToolbarState);

        area.addEventListener('paste', function (event) {
            event.preventDefault();
            var text = (event.clipboardData || window.clipboardData).getData('text/plain');
            insertPlainText(text);
            syncFromEditor();
        });

        textarea.addEventListener('input', function () {
            if (isSourceMode) {
                return;
            }
            syncToEditor();
        });

        if (textarea.form) {
            textarea.form.addEventListener('submit', function () {
                if (!isSourceMode) {
                    textarea.value = area.innerHTML;
                }
            });
        }

        refreshToolbarState();
    });
});
</script>
