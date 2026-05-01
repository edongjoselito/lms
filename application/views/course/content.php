<?php
if (!function_exists('course_lesson_video_url')) {
    function course_lesson_video_url($content)
    {
        if (preg_match('/data-video-url="([^"]+)"/', (string) $content, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        return '';
    }
}

if (!function_exists('course_lesson_file_url')) {
    function course_lesson_file_url($content)
    {
        if (preg_match('/data-file-url="([^"]+)"/', (string) $content, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        return '';
    }
}

if (!function_exists('course_lesson_link_url')) {
    function course_lesson_link_url($content)
    {
        if (preg_match('/data-link-url="([^"]+)"/', (string) $content, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        return '';
    }
}

if (!function_exists('course_lesson_notes_content')) {
    function course_lesson_notes_content($content)
    {
        $content = (string) $content;
        $content = preg_replace('/<div class="lesson-video-embed[^"]*"[^>]*>.*?<\/div>\s*/is', '', $content);
        $content = preg_replace('/<div class="lesson-file-embed[^"]*"[^>]*>.*?<div class="ratio[^"]*lesson-file-preview[^"]*"[^>]*>.*?<\/div>\s*<\/div>\s*/is', '', $content);
        $content = preg_replace('/<div class="lesson-file-embed[^"]*"[^>]*>.*?<\/div>\s*/is', '', $content);
        $content = preg_replace('/<div class="lesson-link-embed[^"]*"[^>]*>.*?<\/div>\s*/is', '', $content);
        $content = preg_replace('/^<div class="lesson-video-notes">\s*/i', '', $content);
        $content = preg_replace('/\s*<\/div>\s*$/i', '', $content);
        return $content;
    }
}
$student_content_view = !empty($student_content_view) || !empty($is_student_mode);
$is_student_mode = $student_content_view;
$subject_system_type = strtolower($subject->system_type ?: 'general');
$course_modules = isset($modules) && is_array($modules) ? $modules : array();
$course_module_count = count($course_modules);
$course_lesson_count = 0;
$course_activity_count = 0;

foreach ($course_modules as $course_module) {
    $course_lesson_count += !empty($course_module->lessons) && is_array($course_module->lessons) ? count($course_module->lessons) : 0;
    $course_activity_count += !empty($course_module->activities) && is_array($course_module->activities) ? count($course_module->activities) : 0;
}

$course_completion_percent = max(0, min(100, (int) ($progress_percent ?? 0)));
?>

<div class="cc-wrap">
    <!-- Breadcrumb -->
    <div class="cc-breadcrumb">
        <a href="<?= $back_url ?>" class="cc-back-link">
            <span class="cc-back-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            Back to Subjects
        </a>
    </div>

    <!-- Hero Card -->
    <div class="cc-hero-card">
        <div class="cc-hero-main">
            <div class="cc-cover-wrap <?= empty($subject->cover_photo) ? 'cc-cover-wrap--empty' : '' ?>">
                <?php if (!empty($subject->cover_photo)): ?>
                    <img src="<?= base_url('uploads/covers/' . $subject->cover_photo) ?>" alt="Course Cover" class="cc-cover-img">
                <?php else: ?>
                    <div class="cc-cover-fallback" aria-hidden="true">
                        <span><?= htmlspecialchars($subject->code) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="cc-hero-content">
                <div class="cc-hero-meta">
                    <span class="cc-badge cc-badge--<?= htmlspecialchars($subject_system_type) ?>">
                        <?= htmlspecialchars(strtoupper($subject_system_type)) ?>
                    </span>
                    <?php if ($edit_mode): ?>
                        <span class="cc-edit-indicator">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2a10 10 0 100 20 10 10 0 000-20z" stroke="currentColor" stroke-width="2" />
                                <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Edit Mode
                        </span>
                    <?php endif; ?>
                </div>
                <h1 class="cc-hero-title"><?= htmlspecialchars($subject->code) ?> - <?= htmlspecialchars($subject->description) ?></h1>
                <?php if (!empty($subject->name)): ?>
                    <p class="cc-hero-subtitle"><?= htmlspecialchars($subject->name) ?></p>
                <?php endif; ?>
                <div class="cc-hero-stats" aria-label="Course summary">
                    <span class="cc-stat-pill">
                        <strong><?= (int) $course_module_count ?></strong>
                        <span>Modules</span>
                    </span>
                    <span class="cc-stat-pill">
                        <strong><?= (int) $course_lesson_count ?></strong>
                        <span>Lessons</span>
                    </span>
                    <span class="cc-stat-pill">
                        <strong><?= (int) $course_activity_count ?></strong>
                        <span>Activities</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="cc-hero-actions">
            <?php if (!empty($is_student_mode) && !empty($has_subject_access)): ?>
                <div class="cc-progress-wrap">
                    <div class="cc-progress-header">
                        <span class="cc-progress-label">Course Progress</span>
                        <span class="cc-progress-value"><?= $course_completion_percent ?>%</span>
                    </div>
                    <div class="cc-progress-bar">
                        <div class="cc-progress-fill" style="width:<?= $course_completion_percent ?>%;"></div>
                    </div>
                </div>
            <?php elseif (!empty($is_student_mode) && empty($has_subject_access)): ?>
                <div class="cc-enroll-alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                    </svg>
                    <span>Enrollment key required</span>
                </div>
            <?php endif; ?>

            <div class="cc-action-btns">
                <?php if (isset($original_role_slug) && in_array($original_role_slug, array('teacher', 'course_creator'))): ?>
                    <a href="<?= site_url('mode/toggle') ?>" class="cc-btn cc-btn--<?= !empty($is_student_mode) ? 'warning' : 'ghost' ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <?php if (!empty($is_student_mode)): ?>
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" />
                            <?php else: ?>
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="2" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" />
                            <?php endif; ?>
                        </svg>
                        <?= !empty($is_student_mode) ? 'Exit Student Mode' : 'View as Student' ?>
                    </a>
                <?php endif; ?>
                <?php if ($edit_mode): ?>
                    <button class="cc-btn cc-btn--ghost" data-bs-toggle="modal" data-bs-target="#coverPhotoModal">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="2" />
                            <path d="M21 15l-5-5L5 21" stroke="currentColor" stroke-width="2" />
                        </svg>
                        Cover
                    </button>
                    <?php $back_param = $this->input->get('back', TRUE) ?: ''; ?>
                    <a href="<?= site_url('course/content/' . $subject->id . ($back_param ? '?back=' . urlencode($back_param) : '')) ?>" class="cc-btn cc-btn--ghost">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="2" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" />
                        </svg>
                        View
                    </a>
                    <button class="cc-btn cc-btn--primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        Add Module
                    </button>
                <?php elseif (empty($is_student_mode) && !empty($can_edit)): ?>
                    <?php $back_param = $this->input->get('back', TRUE) ?: ''; ?>
                    <a href="<?= site_url('course/content/' . $subject->id . '?edit=1' . ($back_param ? '&back=' . urlencode($back_param) : '')) ?>" class="cc-btn cc-btn--primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="2" />
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" />
                        </svg>
                        Edit Content
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <!-- Main Content Area -->
        <div class="col-lg-9">
            <?php if (!empty($is_student_mode) && empty($has_subject_access)): ?>
                <div class="cc-enroll-card">
                    <div class="cc-enroll-icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h5 class="cc-enroll-title">Enter Enrollment Key</h5>
                    <p class="cc-enroll-desc">This course requires an enrollment key to access.</p>
                    <form action="<?= site_url('course/enroll_subject/' . $subject->id) ?>" method="post" class="cc-enroll-form">
                        <div class="cc-input-wrap">
                            <span class="cc-input-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </span>
                            <input type="text" name="enrollment_key" class="cc-input" required placeholder="Enter enrollment key">
                        </div>
                        <button type="submit" class="cc-btn cc-btn--primary cc-btn--block">Access Course</button>
                    </form>
                </div>
            <?php elseif (empty($modules)): ?>
                <div class="cc-empty-card">
                    <div class="cc-empty-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 class="cc-empty-title">No Content Yet</h5>
                    <p class="cc-empty-desc">Start building your course by adding modules. Each module can contain lessons, assignments, quizzes, and resources.</p>
                    <?php if ($edit_mode): ?>
                        <button class="cc-btn cc-btn--primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Add First Module
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($modules as $module_index => $module): ?>
                    <?php
                    $module_lessons_count = !empty($module->lessons) && is_array($module->lessons) ? count($module->lessons) : 0;
                    $module_activities_count = !empty($module->activities) && is_array($module->activities) ? count($module->activities) : 0;
                    $module_item_count = $module_lessons_count + $module_activities_count;
                    ?>
                    <!-- Module Card -->
                    <div class="cc-module-card" id="module-<?= $module->id ?>">
                        <!-- Module Header -->
                        <div class="cc-module-header">
                            <div class="cc-module-title-wrap">
                                <span class="cc-module-number"><?= $module_index + 1 ?></span>
                                <div class="cc-module-title-group">
                                    <h5 class="cc-module-title"><?= htmlspecialchars($module->title) ?></h5>
                                    <?php if (!$module->is_published): ?>
                                        <span class="cc-module-status">Hidden</span>
                                    <?php endif; ?>
                                    <span class="cc-module-count"><?= (int) $module_item_count ?> <?= $module_item_count === 1 ? 'item' : 'items' ?></span>
                                </div>
                            </div>
                            <?php if ($edit_mode): ?>
                                <div class="dropdown">
                                    <button class="cc-btn cc-btn--ghost cc-btn--icon" data-bs-toggle="dropdown">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="5" r="1.5" fill="currentColor" />
                                            <circle cx="12" cy="12" r="1.5" fill="currentColor" />
                                            <circle cx="12" cy="19" r="1.5" fill="currentColor" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" data-collapse-target="#editModule<?= $module->id ?>"><i class="bi bi-pencil me-2"></i>Edit Module</a></li>
                                        <li><a class="dropdown-item" href="#" data-collapse-target="#addLesson<?= $module->id ?>"><i class="bi bi-file-text me-2"></i>Add Lesson</a></li>
                                        <li><a class="dropdown-item" href="#" data-collapse-target="#addAssessment<?= $module->id ?>"><i class="bi bi-ui-checks me-2"></i>Add Assessment</a></li>
                                        <li><a class="dropdown-item" href="#" data-collapse-target="#addActivity<?= $module->id ?>"><i class="bi bi-lightning me-2"></i>Add Activity</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="<?= site_url('course/delete_module/' . $module->id) ?>" onclick="return confirm('Delete this module and all its contents?')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($module->description): ?>
                            <div class="cc-module-desc">
                                <p><?= htmlspecialchars($module->description) ?></p>
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
                            usort($all_items, function ($a, $b) {
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
                                            <a href="#addAssessment<?= $module->id ?>" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="addAssessment<?= $module->id ?>">
                                                <i class="bi bi-ui-checks me-1"></i>Add Assessment
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
                                        <?php
                                        $is_lesson_item = $item->item_type === 'lesson';
                                        $is_quiz_item = !$is_lesson_item && $item->type === 'quiz';
                                        $is_completed_lesson = $is_lesson_item && in_array((int) $item->id, $completed_lesson_ids ?? array());
                                        $is_accessible_lesson = !$is_lesson_item || empty($is_student_mode) || in_array((int) $item->id, $accessible_lesson_ids ?? array());
                                        $item_url = site_url('course/' . ($is_lesson_item ? 'lesson' : ($is_quiz_item ? 'assessment' : 'activity')) . '/' . $item->id);
                                        ?>
                                        <div class="list-group-item cc-content-item p-3 d-flex align-items-center justify-content-between <?= (!$item->is_published && $edit_mode) ? 'cc-content-item--hidden bg-light' : '' ?>">
                                            <?php if ($is_accessible_lesson): ?>
                                                <a href="<?= $item_url ?>" class="content-item-link d-flex align-items-center flex-grow-1">
                                                <?php else: ?>
                                                    <span class="content-item-link content-item-locked d-flex align-items-center flex-grow-1">
                                                    <?php endif; ?>
                                                    <?php if ($is_lesson_item): ?>
                                                        <?php
                                                        $lesson_type = !empty($item->content_type) ? $item->content_type : 'text';
                                                        $lesson_icons = array(
                                                            'text'  => array('icon' => 'bi-file-text', 'color' => '#dbeafe', 'icon_color' => '#1e40af', 'label' => 'Lesson'),
                                                            'page'  => array('icon' => 'bi-file-earmark-text', 'color' => '#f3f4f6', 'icon_color' => '#374151', 'label' => 'Page'),
                                                            'video' => array('icon' => 'bi-play-btn', 'color' => '#fee2e2', 'icon_color' => '#b91c1c', 'label' => 'Video Lesson'),
                                                            'file'  => array('icon' => 'bi-file-earmark-pdf', 'color' => '#eff6ff', 'icon_color' => '#1d4ed8', 'label' => 'File Lesson'),
                                                            'link'  => array('icon' => 'bi-link-45deg', 'color' => '#f0fdf4', 'icon_color' => '#15803d', 'label' => 'Link Lesson'),
                                                        );
                                                        $lesson_icon_info = $lesson_icons[$lesson_type] ?? $lesson_icons['text'];
                                                        ?>
                                                        <div class="activity-icon me-3" style="width:40px;height:40px;border-radius:8px;background:<?= $lesson_icon_info['color'] ?>;display:flex;align-items:center;justify-content:center;color:<?= $lesson_icon_info['icon_color'] ?>;">
                                                            <i class="bi <?= !$is_accessible_lesson ? 'bi-lock-fill' : $lesson_icon_info['icon'] ?>"></i>
                                                        </div>
                                                        <div class="cc-item-body">
                                                            <h6 class="cc-item-title mb-1"><?= htmlspecialchars($item->title ?? '', ENT_QUOTES, 'UTF-8') ?></h6>
                                                            <small class="cc-item-meta text-muted">
                                                                <span class="badge bg-light text-dark border"><?= $lesson_icon_info['label'] ?></span>
                                                                <?php if ($is_completed_lesson): ?>
                                                                    <span class="ms-1 badge bg-success"><i class="bi bi-check2 me-1"></i>Completed</span>
                                                                <?php elseif (!$is_accessible_lesson): ?>
                                                                    <span class="ms-1 badge bg-secondary"><i class="bi bi-lock-fill me-1"></i>Locked</span>
                                                                <?php endif; ?>
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
                                                        <div class="cc-item-body">
                                                            <h6 class="cc-item-title mb-1"><?= htmlspecialchars($item->title ?? '', ENT_QUOTES, 'UTF-8') ?></h6>
                                                            <small class="cc-item-meta text-muted">
                                                                <span class="badge bg-light text-dark border"><?= $icon_info['label'] ?></span>
                                                                <?php if ($is_quiz_item): ?>
                                                                    <span class="ms-1"><i class="bi bi-list-check"></i> <?= (int) ($item->question_count ?? 0) ?> Questions</span>
                                                                <?php endif; ?>
                                                                <?php if (!$item->is_published): ?>
                                                                    <span class="ms-1 badge bg-secondary">Hidden</span>
                                                                <?php endif; ?>
                                                            </small>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($is_accessible_lesson): ?>
                                                </a>
                                            <?php else: ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if ($edit_mode): ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-link text-muted cc-item-menu-btn" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-flip="true" aria-label="Content actions">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="<?= $item_url ?>"><i class="bi bi-eye me-2"></i>View</a></li>
                                                        <?php if ($item->item_type === 'lesson'): ?>
                                                            <li><a class="dropdown-item" href="<?= site_url('course/lesson_completions/' . $item->id) ?>"><i class="bi bi-check2-circle me-2"></i>View Completions</a></li>
                                                            <li><a class="dropdown-item" href="#editLesson<?= $item->id ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="editLesson<?= $item->id ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="<?= site_url('course/delete_lesson/' . $item->id) ?>" onclick="return confirm('Delete this lesson?')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                                        <?php elseif ($is_quiz_item): ?>
                                                            <li><a class="dropdown-item" href="<?= site_url('course/assessment/' . $item->id) ?>"><i class="bi bi-ui-checks me-2"></i>Manage Questions</a></li>
                                                            <li><a class="dropdown-item text-danger" href="<?= site_url('course/delete_activity/' . $item->id) ?>" onclick="return confirm('Delete this assessment and all attempts?')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                                        <?php else: ?>
                                                            <li><a class="dropdown-item" href="#editActivity<?= $item->id ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="editActivity<?= $item->id ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="<?= site_url('course/delete_activity/' . $item->id) ?>" onclick="return confirm('Delete this activity?')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            <?php elseif (!empty($can_manage_sections) && !$student_content_view): ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-link text-muted cc-item-menu-btn" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-flip="true" aria-label="Content actions">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="<?= $item_url ?>"><i class="bi bi-eye me-2"></i>View</a></li>
                                                        <?php if ($item->item_type === 'lesson'): ?>
                                                            <li><a class="dropdown-item" href="<?= site_url('course/lesson_completions/' . $item->id) ?>"><i class="bi bi-check2-circle me-2"></i>View Completions</a></li>
                                                        <?php elseif ($is_quiz_item): ?>
                                                            <li><a class="dropdown-item" href="<?= site_url('course/assessment/' . $item->id) ?>"><i class="bi bi-people me-2"></i>View Attempts</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($item->item_type === 'lesson' && $edit_mode): ?>
                                            <?php
                                            $lesson_video_url = course_lesson_video_url($item->content ?? '');
                                            $lesson_file_url = course_lesson_file_url($item->content ?? '');
                                            if ($lesson_file_url === '' && !empty($item->file_path)) {
                                                $lesson_file_url = $item->file_path;
                                            }
                                            $lesson_editor_content = in_array($item->content_type, array('video', 'file', 'link')) ? course_lesson_notes_content($item->content ?? '') : ($item->content ?? '');
                                            ?>
                                            <div class="collapse item-edit-panel" id="editLesson<?= $item->id ?>">
                                                <form action="<?= site_url('course/edit_lesson/' . $item->id) ?>" method="post" class="module-add-form" enctype="multipart/form-data">
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
                                                        <div class="col-md-6">
                                                            <label class="form-label">Content Type</label>
                                                            <select class="form-select lesson-content-type" name="content_type">
                                                                <option value="text" <?= $item->content_type == 'text' ? 'selected' : '' ?>>Text/HTML</option>
                                                                <option value="page" <?= $item->content_type == 'page' ? 'selected' : '' ?>>Page</option>
                                                                <option value="video" <?= $item->content_type == 'video' ? 'selected' : '' ?>>Video</option>
                                                                <option value="file" <?= $item->content_type == 'file' ? 'selected' : '' ?>>File</option>
                                                                <option value="link" <?= $item->content_type == 'link' ? 'selected' : '' ?>>External Link</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 lesson-video-fields">
                                                            <div class="video-lesson-box">
                                                                <label class="form-label">Video URL</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-play-btn"></i></span>
                                                                    <input type="url" class="form-control lesson-video-url" name="video_url" value="<?= htmlspecialchars($lesson_video_url, ENT_QUOTES, 'UTF-8') ?>" placeholder="YouTube, Vimeo, or direct MP4/WebM URL">
                                                                </div>
                                                                <div class="form-text">Supported: YouTube, Vimeo, and direct video files such as MP4 or WebM.</div>
                                                                <div class="video-url-preview mt-2"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 lesson-file-fields">
                                                            <div class="file-lesson-box" style="padding:1rem;border:1px solid #bfdbfe;border-radius:8px;background:#eff6ff;">
                                                                <label class="form-label">Upload PDF File</label>
                                                                <input type="file" class="form-control lesson-file-upload" name="file_upload" accept=".pdf,application/pdf">
                                                                <?php if (!empty($lesson_file_url)): ?>
                                                                    <div class="mt-2">
                                                                        <a href="<?= htmlspecialchars($lesson_file_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                                                            <i class="bi bi-file-earmark-pdf me-1"></i>Current PDF
                                                                        </a>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="form-text">Select a new PDF only if you want to replace the current file.</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 lesson-link-fields">
                                                            <div class="link-lesson-box" style="padding:1rem;border:1px solid #bbf7d0;border-radius:8px;background:#f0fdf4;">
                                                                <label class="form-label">External Link URL</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                                                    <input type="url" class="form-control lesson-link-url" name="link_url" value="<?= htmlspecialchars(course_lesson_link_url($item->content ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://example.com">
                                                                </div>
                                                                <div class="form-text">Enter the full URL of the external resource.</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label lesson-content-label">Content</label>
                                                            <textarea class="form-control wysiwyg-content" name="content" rows="4"><?= htmlspecialchars($lesson_editor_content, ENT_QUOTES, 'UTF-8') ?></textarea>
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

                                        <?php if ($item->item_type === 'activity' && empty($is_quiz_item) && $edit_mode): ?>
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
                                                            <label class="form-label activity-content-label">Description</label>
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
                                    <form action="<?= site_url('course/create_lesson/' . $module->id) ?>" method="post" class="module-add-form" enctype="multipart/form-data">
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
                                            <div class="col-md-6">
                                                <label class="form-label">Content Type</label>
                                                <select class="form-select lesson-content-type" name="content_type">
                                                    <option value="text">Text/HTML</option>
                                                    <option value="page">Page</option>
                                                    <option value="video">Video</option>
                                                    <option value="file">File</option>
                                                    <option value="link">External Link</option>
                                                </select>
                                            </div>
                                            <div class="col-12 lesson-video-fields">
                                                <div class="video-lesson-box">
                                                    <label class="form-label">Video URL</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-play-btn"></i></span>
                                                        <input type="url" class="form-control lesson-video-url" name="video_url" placeholder="YouTube, Vimeo, or direct MP4/WebM URL">
                                                    </div>
                                                    <div class="form-text">Supported: YouTube, Vimeo, and direct video files such as MP4 or WebM.</div>
                                                    <div class="video-url-preview mt-2"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 lesson-file-fields">
                                                <div class="file-lesson-box" style="padding:1rem;border:1px solid #bfdbfe;border-radius:8px;background:#eff6ff;">
                                                    <label class="form-label">Upload PDF File</label>
                                                    <input type="file" class="form-control lesson-file-upload" name="file_upload" accept=".pdf,application/pdf">
                                                    <div class="form-text">Select a PDF file to upload.</div>
                                                </div>
                                            </div>
                                            <div class="col-12 lesson-link-fields">
                                                <div class="link-lesson-box" style="padding:1rem;border:1px solid #bbf7d0;border-radius:8px;background:#f0fdf4;">
                                                    <label class="form-label">External Link URL</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                                        <input type="url" class="form-control lesson-link-url" name="link_url" placeholder="https://example.com">
                                                    </div>
                                                    <div class="form-text">Enter the full URL of the external resource.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label lesson-content-label">Content</label>
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

                                <div class="collapse" id="addAssessment<?= $module->id ?>" data-bs-parent="#moduleAddPanels<?= $module->id ?>">
                                    <form action="<?= site_url('course/create_assessment/' . $module->id) ?>" method="post" class="module-add-form" enctype="multipart/form-data">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1"><i class="bi bi-ui-checks me-2"></i>Add Assessment</h6>
                                                <p class="text-muted mb-0 small">Upload a GIFT or Moodle XML question bank for "<?= htmlspecialchars($module->title, ENT_QUOTES, 'UTF-8') ?>".</p>
                                            </div>
                                            <a href="#addAssessment<?= $module->id ?>" class="btn-close" data-bs-toggle="collapse" role="button" aria-label="Close"></a>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" name="title" required placeholder="e.g., Midterm Exam">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Type</label>
                                                <select class="form-select" name="quiz_type">
                                                    <option value="quiz">Quiz</option>
                                                    <option value="exam">Exam</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Max Attempts</label>
                                                <input type="number" class="form-control" name="max_attempts" min="1" value="1">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Time Limit</label>
                                                <input type="number" class="form-control" name="time_limit_minutes" min="0" placeholder="Minutes">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Available From</label>
                                                <input type="datetime-local" class="form-control" name="available_from">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Available Until</label>
                                                <input type="datetime-local" class="form-control" name="available_until">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Description / Instructions</label>
                                                <textarea class="form-control wysiwyg-content" name="description" rows="3" placeholder="Enter assessment instructions..."></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Question Format</label>
                                                <select class="form-select" name="import_format">
                                                    <option value="gift">GIFT</option>
                                                    <option value="xml">Moodle XML</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Question File</label>
                                                <input type="file" class="form-control" name="question_file" accept=".gift,.txt,.xml,text/plain,text/xml,application/xml">
                                                <div class="form-text">Supported question types: multiple choice, true/false, identification, and essay.</div>
                                            </div>
                                            <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" id="pubAssessment<?= $module->id ?>">
                                                        <label class="form-check-label" for="pubAssessment<?= $module->id ?>">Publish immediately</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="shuffle_questions" value="1" id="shuffleAssessment<?= $module->id ?>">
                                                        <label class="form-check-label" for="shuffleAssessment<?= $module->id ?>">Shuffle questions</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="show_results" value="1" id="showAssessmentResults<?= $module->id ?>" checked>
                                                        <label class="form-check-label" for="showAssessmentResults<?= $module->id ?>">Show results</label>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="#addAssessment<?= $module->id ?>" class="btn btn-secondary" data-bs-toggle="collapse" role="button">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Add Assessment</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="collapse" id="addActivity<?= $module->id ?>" data-bs-parent="#moduleAddPanels<?= $module->id ?>">
                                    <form action="<?= site_url('course/create_activity/' . $module->id) ?>" method="post" class="module-add-form" enctype="multipart/form-data">
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
                                                    <option value="quiz">Quiz - Upload GIFT/XML questions</option>
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
                                                <label class="form-label activity-content-label">Description/Instructions</label>
                                                <textarea class="form-control wysiwyg-content" name="content" rows="3" placeholder="Enter instructions or description..."></textarea>
                                            </div>
                                            <div class="col-12 activity-quiz-import-fields">
                                                <div class="quiz-import-box">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Question Format</label>
                                                            <select class="form-select" name="import_format">
                                                                <option value="gift">GIFT</option>
                                                                <option value="xml">Moodle XML</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class="form-label">Question File</label>
                                                            <input type="file" class="form-control" name="question_file" accept=".gift,.txt,.xml,text/plain,text/xml,application/xml">
                                                            <div class="form-text">Supported question types: multiple choice, true/false, identification, and essay.</div>
                                                        </div>
                                                    </div>
                                                </div>
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
            <div class="cc-sidebar-panel">
                <!-- Panel Header -->
                <div class="cc-panel-header">
                    <h5 class="cc-panel-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Course Structure
                    </h5>
                    <?php if (!empty($can_manage_sections)): ?>
                        <button class="cc-btn cc-btn--ghost cc-btn--sm" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Section
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Module List -->
                <div class="cc-module-nav">
                    <?php if (empty($modules)): ?>
                        <p class="cc-panel-empty">No modules yet.</p>
                    <?php else: ?>
                        <?php foreach ($modules as $idx => $mod): ?>
                            <a href="#module-<?= $mod->id ?>" class="cc-nav-item">
                                <span class="cc-nav-number"><?= $idx + 1 ?></span>
                                <span class="cc-nav-text"><?= htmlspecialchars($mod->title ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($can_manage_sections) && !empty($subject_sections)): ?>
                    <div class="cc-panel-divider"></div>
                    <div class="cc-sections-list">
                        <h6 class="cc-subtitle">Sections</h6>
                        <?php foreach ($subject_sections as $section_access): ?>
                            <?php $has_key = trim((string) ($section_access->enrollment_key ?? '')) !== ''; ?>
                            <div class="cc-section-row"
                                id="sectionItem<?= $section_access->id ?>"
                                data-id="<?= (int) $section_access->id ?>"
                                data-section-name="<?= htmlspecialchars($section_access->section_name, ENT_QUOTES, 'UTF-8') ?>"
                                data-enrollment-key="<?= htmlspecialchars((string) ($section_access->enrollment_key ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <div class="cc-section-info">
                                    <span class="cc-section-name"><?= htmlspecialchars($section_access->section_name, ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="cc-section-badge <?= $has_key ? 'locked' : 'open' ?>"><?= $has_key ? 'Locked' : 'Open' ?></span>
                                </div>
                                <div class="cc-section-actions">
                                    <a class="cc-action-btn" href="<?= site_url('course/section_students/' . $section_access->id) ?>" title="View Students">
                                        <i class="bi bi-people"></i>
                                    </a>
                                    <a class="cc-action-btn" href="<?= site_url('course/section_progress/' . $section_access->id) ?>" title="View Lesson Progress">
                                        <i class="bi bi-graph-up"></i>
                                    </a>
                                    <button class="cc-action-btn" onclick="showEditSectionModal(<?= $section_access->id ?>)" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
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

<?php if ($edit_mode): ?>
    <div class="modal fade" id="coverPhotoModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?= site_url('course/upload_cover_photo/' . $subject->id) ?>" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Course Cover Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($subject->cover_photo)): ?>
                        <div class="mb-3">
                            <label class="form-label">Current Cover Photo</label>
                            <img src="<?= base_url('uploads/covers/' . $subject->cover_photo) ?>" alt="Current Cover" style="width:100%;max-height:200px;object-fit:cover;border-radius:8px;">
                            <div class="mt-2">
                                <a href="<?= site_url('course/remove_cover_photo/' . $subject->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this cover photo?');">
                                    <i class="bi bi-trash me-1"></i> Remove Cover Photo
                                </a>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Upload Cover Photo</label>
                        <input type="file" class="form-control" name="cover_photo" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp" required>
                        <div class="form-text">Supported formats: JPG, PNG, GIF, or WebP. Maximum size: 5 MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Cover Photo</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($can_manage_sections)): ?>
    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?= site_url('course/add_subject_section/' . $subject->id) ?>" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Section Name</label>
                        <input type="text" class="form-control" name="section_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enrollment Key</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="enrollment_key" maxlength="50" id="enrollmentKeyInput">
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleEnrollmentKeyVisibility()">
                                <i class="bi bi-eye" id="enrollmentKeyIcon"></i>
                            </button>
                        </div>
                        <div class="form-text">Leave blank for open access (no enrollment key required)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Section</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div class="modal fade" id="editSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?= site_url('course/edit_subject_section/' . $subject->id) ?>" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="class_program_id" id="editClassProgramId">
                    <div class="mb-3">
                        <label class="form-label">Section Name</label>
                        <input type="text" class="form-control" name="section_name" required id="editSectionName">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enrollment Key</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="enrollment_key" maxlength="50" id="editEnrollmentKeyInput">
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleEditEnrollmentKeyVisibility()">
                                <i class="bi bi-eye" id="editEnrollmentKeyIcon"></i>
                            </button>
                        </div>
                        <div class="form-text">Leave blank for open access (no enrollment key required)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<style>
    /* ── Apple-Level Design System ──────────────────────────────────── */
    .cc-wrap {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem 2rem 4rem;
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
        color: #1e293b;
    }

    /* ── Breadcrumb ────────────────────────────────────────────────── */
    .cc-breadcrumb {
        margin-bottom: 1.5rem;
    }

    .cc-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #3b82f6;
        text-decoration: none;
        padding: 8px 14px 8px 10px;
        border-radius: 10px;
        background: rgba(59, 130, 246, 0.08);
        transition: all 0.2s ease;
    }

    .cc-back-link:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #2563eb;
    }

    .cc-back-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
        flex-shrink: 0;
    }

    /* ── Hero Card ─────────────────────────────────────────────────── */
    .cc-hero-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }

    .cc-hero-main {
        padding: 1.5rem 2rem;
    }

    .cc-cover-wrap {
        margin: -1.5rem -2rem 1rem;
        height: 140px;
        overflow: hidden;
    }

    .cc-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cc-hero-content {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .cc-hero-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .cc-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .cc-badge--deped {
        background: #dbeafe;
        color: #3b82f6;
    }

    .cc-badge--ched {
        background: #fef3c7;
        color: #f59e0b;
    }

    .cc-badge--tesda {
        background: #dcfce7;
        color: #22c55e;
    }

    .cc-badge--general {
        background: #f1f5f9;
        color: #64748b;
    }

    .cc-edit-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #fffbeb;
        color: #f59e0b;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .cc-hero-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
        letter-spacing: -0.01em;
    }

    .cc-hero-subtitle {
        font-size: 0.9rem;
        color: #64748b;
        margin: 0;
        font-weight: 400;
    }

    /* ── Hero Actions ─────────────────────────────────────────────── */
    .cc-hero-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 2rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .cc-progress-wrap {
        flex: 1;
        min-width: 200px;
        max-width: 320px;
    }

    .cc-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .cc-progress-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
    }

    .cc-progress-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: #3b82f6;
    }

    .cc-progress-bar {
        height: 4px;
        background: #e2e8f0;
        border-radius: 100px;
        overflow: hidden;
    }

    .cc-progress-fill {
        height: 100%;
        background: #22c55e;
        border-radius: 100px;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cc-enroll-alert {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #fffbeb;
        color: #f59e0b;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .cc-action-btns {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* ── Buttons ────────────────────────────────────────────────────── */
    .cc-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .cc-btn--primary {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: #fff;
        box-shadow: 0 1px 3px rgba(59, 130, 246, 0.2);
    }

    .cc-btn--primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.35);
    }

    .cc-btn--ghost {
        background: #f8fafc;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }

    .cc-btn--ghost:hover {
        background: #fff;
        border-color: #cbd5e1;
        color: #3b82f6;
    }

    .cc-btn--warning {
        background: #fffbeb;
        color: #f59e0b;
        border: 1px solid #fde68a;
    }

    .cc-btn--warning:hover {
        background: #fef3c7;
    }

    .cc-btn--sm {
        padding: 8px 14px;
        font-size: 0.8125rem;
    }

    .cc-btn--icon {
        padding: 10px;
    }

    .cc-btn--block {
        width: 100%;
    }

    /* ── Empty & Enroll Cards ──────────────────────────────────────── */
    .cc-empty-card,
    .cc-enroll-card {
        background: #fff;
        border: 1px solid #e5e9f0;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(17, 24, 39, .05);
    }

    .cc-empty-icon,
    .cc-enroll-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #94a3b8;
        margin-bottom: 1.25rem;
    }

    .cc-enroll-icon {
        background: #fffbeb;
        color: #f59e0b;
    }

    .cc-empty-title,
    .cc-enroll-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .cc-empty-desc,
    .cc-enroll-desc {
        font-size: 0.9rem;
        color: #64748b;
        max-width: 420px;
        margin: 0 auto 1.5rem;
    }

    .cc-enroll-form {
        max-width: 360px;
        margin: 0 auto;
    }

    .cc-input-wrap {
        position: relative;
        margin-bottom: 1rem;
    }

    .cc-input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        display: flex;
        align-items: center;
    }

    .cc-input {
        width: 100%;
        padding: 10px 12px 10px 40px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #0f172a;
        background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .cc-input:focus {
        outline: none;
        border-color: #3b67e8;
        box-shadow: 0 0 0 3px rgba(59, 103, 232, .1);
    }

    /* ── Module Cards ───────────────────────────────────────────────── */
    .cc-module-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: visible;
        margin-bottom: 1rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }

    .cc-module-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: #fafafa;
        border-bottom: 1px solid #e2e8f0;
    }

    .cc-module-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 0;
    }

    .cc-module-number {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #3b82f6;
        color: #fff;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .cc-module-title-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        min-width: 0;
    }

    .cc-module-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
    }

    .cc-module-status {
        padding: 2px 8px;
        background: #e2e8f0;
        color: #64748b;
        border-radius: 100px;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .cc-module-desc {
        padding: 0.75rem 1.25rem;
        background: #fafafa;
        border-bottom: 1px solid #e2e8f0;
    }

    .cc-module-desc p {
        margin: 0;
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.4;
    }

    /* ── Content Items (Lessons/Activities) ──────────────────────────── */
    .list-group-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item:hover {
        background: #f8fafc;
    }

    .content-item-link {
        color: inherit;
        text-decoration: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.125rem;
    }

    .activity-icon.video {
        background: #fff2f2;
        color: #ff3b30;
    }

    .activity-icon.lesson {
        background: #dbeafe;
        color: #3b82f6;
    }

    .activity-icon.activity {
        background: #f0fdf4;
        color: #34c759;
    }

    .content-item-link h6 {
        font-size: 0.9375rem;
        font-weight: 500;
        color: #1e293b;
        margin: 0 0 4px 0;
        letter-spacing: -0.01em;
    }

    .content-item-link:hover h6 {
        color: #3b82f6;
    }

    .content-item-locked {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .content-item-locked h6 {
        color: #86868b;
    }

    /* ── Unified Sidebar Panel ───────────────────────────────────────── */
    .cc-sidebar-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }

    .cc-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .cc-panel-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .cc-panel-title svg {
        color: #3b82f6;
    }

    .cc-panel-empty {
        font-size: 0.875rem;
        color: #94a3b8;
        padding: 1.5rem;
        text-align: center;
        margin: 0;
    }

    .cc-panel-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 0.5rem 1.25rem;
    }

    /* Module Navigation */
    .cc-module-nav {
        padding: 0.5rem;
    }

    .cc-nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.75rem;
        border-radius: 8px;
        color: #475569;
        text-decoration: none;
        font-size: 0.8125rem;
        transition: all 0.15s ease;
    }

    .cc-nav-item:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .cc-nav-number {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e2e8f0;
        color: #64748b;
        border-radius: 4px;
        font-size: 0.6875rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .cc-nav-item:hover .cc-nav-number {
        background: #3b82f6;
        color: #fff;
    }

    .cc-nav-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Sections List */
    .cc-sections-list {
        padding: 0.5rem 1rem 1rem;
    }

    .cc-subtitle {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 0.75rem;
    }

    .cc-section-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .cc-section-row:last-child {
        border-bottom: none;
    }

    .cc-section-name {
        font-size: 0.8125rem;
        color: #1e293b;
    }

    .cc-section-badge {
        font-size: 0.6875rem;
        font-weight: 500;
        padding: 0.125rem 0.375rem;
        border-radius: 4px;
    }

    .cc-section-badge.open {
        background: #dcfce7;
        color: #15803d;
    }

    .cc-section-badge.locked {
        background: #fee2e2;
        color: #dc2626;
    }

    .cc-action-btn {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: none;
        color: #94a3b8;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .cc-action-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* ── Utility Classes ──────────────────────────────────────────────── */
    .activity-icon {
        flex-shrink: 0;
    }

    .course-content-page {
        overflow: visible !important;
    }

    .data-table {
        overflow: visible !important;
    }

    .list-group-item {
        overflow: visible !important;
    }

    .p-0 {
        overflow: visible !important;
    }

    .enrollment-key-panel {
        color: #86868b;
    }

    .enrollment-key-panel>i {
        display: block;
        margin-bottom: 1rem;
        color: #ff9500;
        font-size: 3rem;
    }

    .enrollment-key-panel h5 {
        color: #334155;
        font-weight: 800;
    }

    .enrollment-key-panel p {
        max-width: 420px;
        margin: 0 auto 1.25rem;
    }

    .enrollment-key-form {
        max-width: 520px;
        margin: 0 auto;
    }

    .section-key-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .section-key-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.65rem 0;
        border-top: 1px solid #e2e8f0;
    }

    .section-key-item strong,
    .section-key-item span {
        display: block;
        font-size: 0.84rem;
    }

    .section-key-item span {
        margin-top: 0.15rem;
    }

    .module-add-panels {
        background: #f8fafc;
    }

    .item-edit-panel {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .lesson-video-fields,
    .lesson-file-fields,
    .lesson-link-fields,
    .activity-quiz-import-fields {
        display: none;
    }

    .lesson-form-is-video .lesson-video-fields {
        display: block;
    }

    .lesson-form-is-file .lesson-file-fields {
        display: block;
    }

    .activity-form-is-quiz .activity-quiz-import-fields {
        display: block;
    }

    .quiz-import-box {
        padding: 1rem;
        border: 1px solid #fde68a;
        border-radius: 8px;
        background: #fffbeb;
    }

    .lesson-form-is-link .lesson-link-fields {
        display: block;
    }

    .video-lesson-box,
    .file-lesson-box,
    .link-lesson-box {
        padding: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        background: #fafafa;
        margin-bottom: 1rem;
    }

    .video-url-preview:empty {
        display: none;
    }

    .video-url-preview iframe,
    .video-url-preview video {
        width: 100%;
        border: 0;
        border-radius: 8px;
        background: #0f172a;
    }

    .module-add-form h6 {
        color: #334155;
        font-weight: 700;
    }

    /* ── WYSIWYG Editor ──────────────────────────────────────────────── */
    .wysiwyg-source {
        display: none;
    }

    .wysiwyg-editor {
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
    }

    .wysiwyg-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        padding: 0.625rem;
        background: #fafafa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .wysiwyg-toolbar-group {
        display: inline-flex;
        gap: 0.25rem;
        padding-right: 0.5rem;
        margin-right: 0.5rem;
        border-right: 1px solid rgba(0, 0, 0, 0.08);
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
        border: none;
        background: transparent;
        color: #6b7280;
        border-radius: 6px;
        transition: all 0.15s;
    }

    .wysiwyg-toolbar .btn:hover {
        background: rgba(0, 0, 0, 0.06);
        color: #1e293b;
    }

    .wysiwyg-format {
        width: auto;
        min-width: 6.5rem;
        height: 2rem;
        font-size: 0.8125rem;
        border: none;
        background: rgba(0, 0, 0, 0.04);
        border-radius: 6px;
        padding: 0 0.5rem;
    }

    .wysiwyg-toolbar .btn.active {
        background: #3b82f6;
        color: #fff;
    }

    .wysiwyg-area {
        min-height: 140px;
        padding: 0.875rem;
        outline: none;
        overflow-wrap: anywhere;
        font-size: 0.9375rem;
        line-height: 1.6;
        color: #1e293b;
    }

    .wysiwyg-area:empty::before {
        content: attr(data-placeholder);
        color: #9ca3af;
    }

    .wysiwyg-area blockquote {
        margin: 0 0 1rem;
        padding: 0.75rem 1rem;
        border-left: 3px solid #3b82f6;
        background: #f8fafc;
        color: #374151;
        border-radius: 0 6px 6px 0;
    }

    .wysiwyg-area pre {
        padding: 0.875rem;
        border-radius: 8px;
        background: #1d1d1f;
        color: #f5f5f7;
        overflow-x: auto;
        font-size: 0.8125rem;
    }

    .wysiwyg-source-visible {
        display: block;
        min-height: 180px;
        border: 0;
        resize: vertical;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.875rem;
        line-height: 1.6;
        background: #fafafa;
        padding: 0.875rem;
        border-radius: 8px;
    }

    .lesson-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #dbeafe;
        color: #3b82f6;
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .item-edit-panel {
        background: #fafafa;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        margin: 0;
        padding: 1.5rem;
    }

    .module-add-form {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .file-preview-box {
        background: #fafafa;
        border: 1px dashed rgba(0, 0, 0, 0.16);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
    }

    .file-preview-box i {
        font-size: 2.5rem;
        color: #9ca3af;
        margin-bottom: 0.75rem;
    }

    .video-preview-box {
        background: #fafafa;
        border: 1px dashed rgba(0, 0, 0, 0.16);
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
    }

    .video-preview-box i {
        font-size: 2.5rem;
        color: #9ca3af;
        margin-bottom: 0.75rem;
    }

    .video-info-text {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .video-input-hint {
        color: #9ca3af;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    /* ── Modal Styles ───────────────────────────────────────────────── */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1.25rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        color: #1e293b;
    }

    /* ── Form Controls ──────────────────────────────────────────────── */
    .form-control,
    .form-select {
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    .form-label {
        font-weight: 500;
        font-size: 0.8125rem;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-close {
        width: 24px;
        height: 24px;
        opacity: 0.4;
        transition: opacity 0.15s;
    }

    .btn-close:hover {
        opacity: 0.6;
    }

    /* ── Collapse Panel Animations ──────────────────────────────────── */
    .collapse {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .collapse.show {
        background: #fafafa;
    }

    /* ── Page-specific overrides ────────────────────────────────────── */
    .course-content-page .wysiwyg-editor {
        border-color: rgba(0, 0, 0, 0.12);
        border-radius: 10px;
    }

    .course-content-page .wysiwyg-toolbar {
        background: #fafafa;
        border-bottom-color: rgba(0, 0, 0, 0.08);
    }

    .course-content-page {
        padding: 0.5rem 0 1.5rem;
    }

    .course-content-page>.row:first-child .mb-3 a {
        color: #2f6fed !important;
        font-size: 0.9rem !important;
        font-weight: 700 !important;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .course-content-page>.row:first-child .data-table {
        background: transparent;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
    }

    .course-content-page>.row:first-child .table-header {
        padding: 0.25rem 0 0.75rem;
        border-bottom: 0;
        align-items: flex-start;
        gap: 1rem;
    }

    .course-content-page>.row:first-child .table-header h5 {
        color: #182033;
        font-size: 1.55rem;
        line-height: 1.3;
        font-weight: 700;
    }

    .course-content-page>.row:first-child .table-header .btn,
    .course-content-page .btn-primary,
    .course-content-page .btn-outline-primary,
    .course-content-page .btn-outline-secondary,
    .course-content-page .btn-outline-success,
    .course-content-page .btn-secondary,
    .course-content-page .btn-success {
        border-radius: 12px;
        font-weight: 700;
    }

    .course-content-page .btn-primary,
    .course-content-page .btn-success {
        background: #2f6fed;
        border-color: #2f6fed;
    }

    .course-content-page .btn-outline-primary {
        color: #2f6fed;
        border-color: #d7e6ff;
        background: #edf4ff;
    }

    .course-content-page .btn-outline-primary:hover,
    .course-content-page .btn-outline-secondary:hover,
    .course-content-page .btn-outline-success:hover {
        background: #2f6fed;
        border-color: #2f6fed;
        color: #fff;
    }

    .course-content-page .course-cover-photo {
        padding: 0;
        margin: 0.5rem 0 0;
    }

    .course-content-page .course-cover-photo img {
        border-radius: 8px !important;
        border: 1px solid #e4e7ec;
    }

    .course-content-page>.row.g-4 {
        margin-top: 0.75rem !important;
    }

    .course-content-page .data-table,
    .course-content-page .form-card {
        border: 1px solid #e4e7ec;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
    }

    .course-content-page .col-lg-9>.data-table {
        margin-bottom: 1rem !important;
    }

    .course-content-page .col-lg-9>.data-table>.table-header {
        background: #fff !important;
        border-bottom: 1px solid #edf0f4;
        padding: 1.15rem 1.25rem;
    }

    .course-content-page .col-lg-9>.data-table>.table-header .badge.bg-primary {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        background: #edf4ff !important;
        color: #2f6fed;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .course-content-page .col-lg-9>.data-table>.table-header h5 {
        color: #182033;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .course-content-page .col-lg-9>.data-table>.p-3[style*="background"] {
        background: #f8fafc !important;
        border-bottom-color: #edf0f4 !important;
    }

    .course-content-page .list-group {
        padding: 0.75rem;
        gap: 0.65rem;
        display: flex;
        flex-direction: column;
    }

    .course-content-page .list-group-item {
        border: 1px solid #edf0f4 !important;
        border-radius: 8px !important;
        background: #f8fafc;
    }

    .course-content-page .list-group-item:hover {
        background: #fff;
        border-color: #d7e6ff !important;
    }

    .course-content-page .content-item-link {
        color: inherit;
        min-width: 0;
        text-decoration: none;
    }

    .course-content-page .content-item-link h6 {
        color: #182033;
        font-weight: 700;
    }

    .course-content-page .content-item-link:hover h6 {
        color: #2f6fed;
        text-decoration: none;
    }

    .course-content-page .activity-icon {
        border-radius: 14px !important;
        background: #edf4ff !important;
        color: #2f6fed !important;
    }

    .course-content-page .badge.bg-light {
        background: #eef2f7 !important;
        color: #475467 !important;
        border-color: #e4e7ec !important;
    }

    .course-content-page .badge.bg-success {
        background: #10b981 !important;
    }

    .course-content-page .col-lg-3 .list-group-item {
        border: 0 !important;
        background: transparent;
        border-radius: 8px !important;
    }

    .course-content-page .col-lg-3 .list-group-item:hover {
        background: #f8fafc;
    }

    .course-content-page .wysiwyg-editor {
        border-color: #e4e7ec;
        border-radius: 8px;
    }

    .course-content-page .wysiwyg-toolbar {
        background: #f8fafc;
        border-bottom-color: #e4e7ec;
    }

    .course-content-page .video-lesson-box,
    .course-content-page .file-lesson-box,
    .course-content-page .link-lesson-box {
        border-radius: 8px !important;
    }

    @media (max-width: 768px) {
        .course-content-page>.row:first-child .table-header {
            flex-direction: column;
        }

        .course-content-page>.row:first-child .table-header>div:last-child {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .course-content-page .list-group-item {
            align-items: flex-start !important;
            gap: 0.75rem;
        }
    }

    /* ── Responsive for New Design ──────────────────────────────────── */
    @media (max-width: 768px) {
        .cc-wrap {
            padding: 0.75rem 0.75rem 2rem;
        }

        .cc-hero-main {
            padding: 1.25rem;
        }

        .cc-hero-title {
            font-size: 1.25rem;
        }

        .cc-hero-actions {
            flex-direction: column;
            align-items: stretch;
            padding: 1rem 1.25rem;
        }

        .cc-action-btns {
            justify-content: stretch;
        }

        .cc-btn {
            flex: 1;
        }

        .cc-progress-wrap {
            max-width: 100%;
        }

        .cc-module-header {
            padding: 1rem 1.25rem;
        }

        .cc-module-title {
            font-size: 0.95rem;
        }
    }

    @media (max-width: 480px) {
        .cc-module-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
    }

    /* Course content visual refresh */
    .cc-wrap,
    .cc-wrap * {
        letter-spacing: 0;
    }

    .cc-wrap {
        width: 100%;
        max-width: none;
        padding: 1rem 0 4rem;
    }

    .cc-hero-card,
    .cc-module-card,
    .cc-sidebar-panel,
    .cc-empty-card,
    .cc-enroll-card {
        border-color: #e7edf5;
        border-radius: 16px;
        box-shadow: 0 12px 28px rgba(67, 89, 113, 0.08);
    }

    .cc-hero-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(220px, 280px);
        overflow: hidden;
        background: #ffffff;
    }

    .cc-hero-main {
        display: grid;
        grid-template-columns: minmax(220px, 28%) 1fr;
        min-height: 210px;
        padding: 0;
    }

    .cc-cover-wrap {
        position: relative;
        height: auto;
        min-height: 210px;
        margin: 0;
        border-right: 1px solid #e7edf5;
        background: #eef4f7;
    }

    .cc-cover-img {
        width: 100%;
        height: 100%;
        min-height: 210px;
        object-fit: cover;
        display: block;
    }

    .cc-cover-wrap--empty {
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            linear-gradient(135deg, rgba(105, 108, 255, 0.08), rgba(16, 185, 129, 0.08)),
            #f3f7fb;
    }

    .cc-cover-fallback {
        width: min(150px, 58%);
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(105, 108, 255, 0.18);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.78);
        color: #566a7f;
        font-size: clamp(1.3rem, 3vw, 2rem);
        font-weight: 700;
        text-align: center;
        box-shadow: 0 12px 28px rgba(67, 89, 113, 0.08);
    }

    .cc-cover-fallback span {
        max-width: 90%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cc-hero-content {
        justify-content: center;
        padding: 1.5rem 1.75rem;
    }

    .cc-hero-title {
        max-width: 920px;
        color: #253446;
        font-size: clamp(1.35rem, 2vw, 1.8rem);
        line-height: 1.25;
    }

    .cc-hero-subtitle {
        color: #697a8d;
    }

    .cc-hero-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-top: 0.35rem;
    }

    .cc-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        min-height: 36px;
        padding: 0.45rem 0.75rem;
        border: 1px solid #e7edf5;
        border-radius: 10px;
        background: #f8fafc;
        color: #697a8d;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .cc-stat-pill strong {
        color: #253446;
        font-size: 0.95rem;
        font-variant-numeric: tabular-nums;
    }

    .cc-hero-actions {
        flex-direction: column;
        align-items: stretch;
        justify-content: center;
        background: #ffffff;
        border-top: 0;
        border-left: 1px solid #eef2f7;
        padding: 1.25rem;
    }

    .cc-hero-actions .cc-progress-wrap {
        width: 100%;
        min-width: 0;
        max-width: none;
    }

    .cc-action-btns {
        width: 100%;
        align-items: stretch;
    }

    .cc-action-btns .cc-btn {
        width: 100%;
    }

    .cc-progress-bar {
        height: 8px;
        background: #edf2f7;
    }

    .cc-progress-fill {
        background: #10b981;
    }

    .cc-btn {
        min-height: 40px;
        border-radius: 10px;
    }

    .cc-btn--primary {
        background: #696cff;
        box-shadow: 0 8px 18px rgba(105, 108, 255, 0.22);
    }

    .cc-btn--primary:hover {
        background: #5f61f4;
        color: #ffffff;
    }

    .cc-btn--ghost {
        background: #ffffff;
        border-color: #e7edf5;
        color: #566a7f;
    }

    .cc-btn--ghost:hover {
        background: #f8fafc;
        border-color: #d9dee3;
        color: #253446;
    }

    .cc-module-card {
        overflow: visible;
        margin-bottom: 1.2rem;
        background: #ffffff;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .cc-module-card:hover {
        border-color: #d8e0ea;
        box-shadow: 0 14px 32px rgba(67, 89, 113, 0.1);
    }

    .cc-module-header {
        background: #ffffff;
        border-bottom-color: #eef2f7;
        padding: 1rem 1.15rem;
    }

    .cc-module-number {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #696cff;
        box-shadow: 0 8px 18px rgba(105, 108, 255, 0.2);
    }

    .cc-module-title {
        color: #253446;
        overflow-wrap: anywhere;
    }

    .cc-module-count {
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        background: #f3f6fa;
        color: #697a8d;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .cc-module-desc {
        background: #fbfcfe;
        border-bottom-color: #eef2f7;
    }

    .cc-module-card .list-group {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        padding: 0.9rem;
        overflow: visible;
    }

    .cc-content-item {
        position: relative;
        gap: 0.75rem;
        border: 1px solid #eef2f7 !important;
        border-radius: 12px !important;
        background: #ffffff;
        overflow: visible !important;
        transition: transform 0.16s ease, border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .cc-content-item:last-child {
        margin-bottom: 0;
    }

    .cc-content-item:hover {
        z-index: 10;
        transform: translateY(-1px);
        border-color: #d8e0ea !important;
        box-shadow: 0 8px 18px rgba(67, 89, 113, 0.07);
        background: #ffffff;
    }

    .cc-content-item:focus-within {
        z-index: 20;
    }

    .cc-content-item--hidden {
        background: #f8fafc !important;
    }

    .content-item-link {
        min-width: 0;
    }

    .cc-item-body {
        min-width: 0;
    }

    .cc-item-title {
        color: #253446;
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .cc-item-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.35rem 0.5rem;
        line-height: 1.4;
    }

    .cc-item-meta .ms-1 {
        margin-left: 0 !important;
    }

    .cc-item-meta .badge {
        border-radius: 999px;
        font-size: 0.68rem;
        padding: 0.25rem 0.5rem;
    }

    .activity-icon {
        border-radius: 12px !important;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.55);
    }

    .cc-item-menu-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 1px solid transparent;
        border-radius: 10px;
        text-decoration: none;
    }

    .cc-item-menu-btn:hover,
    .cc-item-menu-btn:focus {
        background: #f3f6fa;
        border-color: #e7edf5;
        color: #253446 !important;
    }

    .cc-content-item .dropdown {
        position: relative;
        z-index: 30;
    }

    .cc-content-item .dropdown-menu {
        z-index: 1095;
        margin-top: 0.35rem !important;
        border-radius: 10px;
    }

    .cc-sidebar-panel {
        position: sticky;
        top: 1rem;
    }

    .cc-panel-header {
        background: #ffffff;
        border-bottom-color: #eef2f7;
    }

    .cc-nav-item {
        min-height: 38px;
        border: 1px solid transparent;
    }

    .cc-nav-item:hover {
        background: #f8fafc;
        border-color: #eef2f7;
    }

    .cc-sections-list {
        padding-bottom: 1.15rem;
    }

    .cc-section-row {
        padding: 0.65rem 0;
    }

    .cc-section-info {
        min-width: 0;
    }

    .cc-section-name {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
    }

    .module-add-panels,
    .item-edit-panel,
    .collapse.show {
        background: #f8fafc;
    }

    .module-add-form {
        border-color: #e7edf5;
        border-radius: 12px;
        box-shadow: 0 8px 18px rgba(67, 89, 113, 0.06);
    }

    .video-lesson-box,
    .file-lesson-box,
    .link-lesson-box,
    .quiz-import-box {
        border-radius: 12px !important;
    }

    @media (max-width: 992px) {
        .cc-wrap {
            padding-left: 0;
            padding-right: 0;
        }

        .cc-hero-card {
            grid-template-columns: 1fr;
        }

        .cc-hero-main {
            grid-template-columns: 1fr;
        }

        .cc-cover-wrap {
            min-height: 180px;
            border-right: 0;
            border-bottom: 1px solid #e7edf5;
        }

        .cc-cover-img {
            min-height: 180px;
            max-height: 240px;
        }

        .cc-sidebar-panel {
            position: static;
        }

        .cc-hero-actions {
            border-left: 0;
            border-top: 1px solid #eef2f7;
        }

        .cc-action-btns {
            flex-direction: row;
        }

        .cc-action-btns .cc-btn {
            width: auto;
        }
    }

    @media (max-width: 768px) {
        .cc-wrap {
            padding: 0.75rem 0 2rem;
        }

        .cc-hero-content,
        .cc-hero-actions {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .cc-hero-stats,
        .cc-action-btns {
            width: 100%;
        }

        .cc-stat-pill {
            flex: 1 1 30%;
            justify-content: center;
        }

        .cc-content-item {
            align-items: flex-start !important;
        }

        .cc-item-menu-btn {
            flex-shrink: 0;
        }
    }

    @media (max-width: 480px) {
        .cc-stat-pill {
            flex-basis: 100%;
        }

        .cc-content-item {
            flex-wrap: nowrap;
            padding: 0.8rem !important;
        }

        .activity-icon {
            width: 36px !important;
            height: 36px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function getVideoPreviewMarkup(url) {
            url = String(url || '').trim();
            if (!url) {
                return '';
            }

            var youtubeMatch = url.match(/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtube\.com\/shorts\/|youtu\.be\/)([A-Za-z0-9_-]+)/i);
            if (youtubeMatch) {
                return '<iframe src="https://www.youtube.com/embed/' + youtubeMatch[1] + '" title="Video preview" allowfullscreen></iframe>';
            }

            var vimeoMatch = url.match(/vimeo\.com\/(\d+)/i);
            if (vimeoMatch) {
                return '<iframe src="https://player.vimeo.com/video/' + vimeoMatch[1] + '" title="Video preview" allowfullscreen></iframe>';
            }

            if (url.match(/\.(mp4|webm|ogg)(\?.*)?$/i)) {
                var safeUrl = url.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return '<video controls preload="metadata"><source src="' + safeUrl + '">Your browser does not support the video tag.</video>';
            }

            return '<div class="alert alert-info mb-0 py-2"><i class="bi bi-info-circle me-1"></i>This link will be saved as an external video button.</div>';
        }

        function setupVideoLessonForm(form) {
            var typeSelect = form.querySelector('.lesson-content-type');
            var videoUrlInput = form.querySelector('.lesson-video-url');
            var preview = form.querySelector('.video-url-preview');
            var contentLabel = form.querySelector('.lesson-content-label');
            var contentField = form.querySelector('textarea[name="content"]');
            var fileUrlInput = form.querySelector('.lesson-file-url');
            var linkUrlInput = form.querySelector('.lesson-link-url');
            var contentFieldContainer = form.querySelector('.lesson-content-field');

            if (!typeSelect) {
                return;
            }

            function refreshVideoFields() {
                var contentType = typeSelect.value;
                var isVideo = contentType === 'video';
                var isFile = contentType === 'file';
                var isLink = contentType === 'link';
                var isPage = contentType === 'page';
                var isText = contentType === 'text';

                form.classList.remove('lesson-form-is-video', 'lesson-form-is-file', 'lesson-form-is-link', 'lesson-form-is-page', 'lesson-form-is-text');
                if (isVideo) form.classList.add('lesson-form-is-video');
                if (isFile) form.classList.add('lesson-form-is-file');
                if (isLink) form.classList.add('lesson-form-is-link');
                if (isPage) form.classList.add('lesson-form-is-page');
                if (isText) form.classList.add('lesson-form-is-text');

                if (contentLabel) {
                    if (isVideo) {
                        contentLabel.textContent = 'Video Notes / Transcript';
                    } else if (isFile) {
                        contentLabel.textContent = 'File Description';
                    } else if (isLink) {
                        contentLabel.textContent = 'Link Description';
                    } else if (isPage) {
                        contentLabel.textContent = 'Page Content';
                    } else {
                        contentLabel.textContent = 'Content';
                    }
                }

                if (contentField) {
                    if (isPage) {
                        contentField.placeholder = 'Paste or write standalone page content...';
                    } else if (isVideo) {
                        contentField.placeholder = 'Enter video notes or transcript...';
                    } else if (isFile) {
                        contentField.placeholder = 'Enter file description...';
                    } else if (isLink) {
                        contentField.placeholder = 'Enter link description...';
                    } else {
                        contentField.placeholder = 'Enter lesson content...';
                    }
                }

                if (videoUrlInput) {
                    videoUrlInput.toggleAttribute('required', isVideo);
                }
                if (fileUrlInput) {
                    fileUrlInput.toggleAttribute('required', isFile);
                }
                if (linkUrlInput) {
                    linkUrlInput.toggleAttribute('required', isLink);
                }
                if (preview && videoUrlInput) {
                    preview.innerHTML = isVideo ? getVideoPreviewMarkup(videoUrlInput.value) : '';
                }
            }

            typeSelect.addEventListener('change', refreshVideoFields);
            if (videoUrlInput) {
                videoUrlInput.addEventListener('input', refreshVideoFields);
            }
            refreshVideoFields();
        }

        function setupActivityForm(form) {
            var typeSelect = form.querySelector('select[name="type"]');
            var contentLabel = form.querySelector('.activity-content-label');
            var contentField = form.querySelector('textarea[name="content"]');
            var questionFile = form.querySelector('input[name="question_file"]');

            if (!typeSelect) {
                return;
            }

            function refreshActivityFields() {
                var activityType = typeSelect.value;
                var isQuiz = activityType === 'quiz';

                form.classList.toggle('activity-form-is-quiz', isQuiz);
                if (questionFile) {
                    questionFile.toggleAttribute('required', isQuiz);
                }

                if (contentLabel) {
                    var labels = {
                        'assignment': 'Assignment Instructions',
                        'quiz': 'Quiz Description',
                        'forum': 'Forum Description',
                        'resource': 'Resource Description',
                        'page': 'Page Content',
                        'label': 'Label Text'
                    };
                    contentLabel.textContent = labels[activityType] || 'Description';
                }

                if (contentField) {
                    var placeholders = {
                        'assignment': 'Enter assignment instructions, requirements, and submission guidelines...',
                        'quiz': 'Enter quiz description, time limit, and other instructions...',
                        'forum': 'Enter forum topic, discussion guidelines, and instructions...',
                        'resource': 'Describe the resource (file, link, or material)...',
                        'page': 'Enter page content...',
                        'label': 'Enter label or section divider text...'
                    };
                    contentField.placeholder = placeholders[activityType] || 'Enter description...';
                }
            }

            typeSelect.addEventListener('change', refreshActivityFields);
            refreshActivityFields();
        }

        document.querySelectorAll('form.module-add-form, form.item-edit-panel').forEach(function(form) {
            setupVideoLessonForm(form);
            setupActivityForm(form);
        });

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

        document.querySelectorAll('textarea.wysiwyg-content').forEach(function(textarea, index) {
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
                editor.querySelectorAll('[data-command]').forEach(function(button) {
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

            formatSelect.addEventListener('change', function() {
                applyFormat(formatSelect.value);
            });

            editor.querySelectorAll('[data-command]').forEach(function(button) {
                button.addEventListener('click', function() {
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

            sourceToggle.addEventListener('click', function() {
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

            area.addEventListener('input', function() {
                syncFromEditor();
                refreshToolbarState();
            });

            area.addEventListener('keyup', refreshToolbarState);
            area.addEventListener('mouseup', refreshToolbarState);

            area.addEventListener('paste', function(event) {
                event.preventDefault();
                var text = (event.clipboardData || window.clipboardData).getData('text/plain');
                insertPlainText(text);
                syncFromEditor();
            });

            textarea.addEventListener('input', function() {
                if (isSourceMode) {
                    return;
                }
                syncToEditor();
            });

            if (textarea.form) {
                textarea.form.addEventListener('submit', function() {
                    if (!isSourceMode) {
                        textarea.value = area.innerHTML;
                    }
                });
            }

            refreshToolbarState();
        });
    });
</script>

<script>
    // Global functions for inline onclick handlers
    function toggleEnrollmentKeyVisibility() {
        var input = document.getElementById('enrollmentKeyInput');
        var icon = document.getElementById('enrollmentKeyIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function toggleEditEnrollmentKeyVisibility() {
        var input = document.getElementById('editEnrollmentKeyInput');
        var icon = document.getElementById('editEnrollmentKeyIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function showEditSectionModal(id) {
        var sectionItem = document.getElementById('sectionItem' + id);
        if (sectionItem) {
            var sectionName = sectionItem.getAttribute('data-section-name') || '';
            var enrollmentKey = sectionItem.getAttribute('data-enrollment-key') || '';

            document.getElementById('editClassProgramId').value = sectionItem.getAttribute('data-id');
            document.getElementById('editSectionName').value = sectionName;
            document.getElementById('editEnrollmentKeyInput').value = enrollmentKey;

            var modalElement = document.getElementById('editSectionModal');
            if (typeof bootstrap !== 'undefined') {
                var modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                alert('Error: Bootstrap is not loaded');
            }
        }
    }

    function hideEditSection() {
        var modal = bootstrap.Modal.getInstance(document.getElementById('editSectionModal'));
        if (modal) {
            modal.hide();
        }
    }

    // Handle dropdown → collapse items via custom data-collapse-target attribute
    // (avoids Bootstrap's double-toggle conflict with data-bs-toggle="collapse" inside dropdowns)
    document.addEventListener('click', function (e) {
        var item = e.target.closest('[data-collapse-target]');
        if (!item) return;
        e.preventDefault();
        var targetSel = item.getAttribute('data-collapse-target');
        var target = document.querySelector(targetSel);
        if (!target) return;
        // Close the dropdown
        var dropdownEl = item.closest('.dropdown');
        if (dropdownEl) {
            var btn = dropdownEl.querySelector('[data-bs-toggle="dropdown"]');
            if (btn) {
                var dd = bootstrap.Dropdown.getInstance(btn);
                if (dd) dd.hide();
            }
        }
        // Toggle the collapse (close siblings in the same parent accordion)
        var col = bootstrap.Collapse.getOrCreateInstance(target, { toggle: false });
        col.toggle();
        // Scroll into view once open
        target.addEventListener('shown.bs.collapse', function scrollOnce() {
            target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            target.removeEventListener('shown.bs.collapse', scrollOnce);
        });
    });
</script>
