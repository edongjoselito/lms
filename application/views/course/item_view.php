<?php
$student_content_view = !empty($student_content_view) || !empty($is_student_mode);
$is_student_mode = $student_content_view;
$is_lesson = $item_type === 'lesson';
$is_video_lesson = $is_lesson && !empty($item->content_type) && $item->content_type === 'video';
$activity_icons = array(
    'assignment' => array('icon' => 'bi-clipboard-check', 'color' => '#dcfce7', 'icon_color' => '#166534', 'label' => 'Assignment'),
    'quiz' => array('icon' => 'bi-question-circle', 'color' => '#fef3c7', 'icon_color' => '#92400e', 'label' => 'Quiz'),
    'forum' => array('icon' => 'bi-chat-dots', 'color' => '#ede9fe', 'icon_color' => '#6d28d9', 'label' => 'Forum'),
    'resource' => array('icon' => 'bi-link', 'color' => '#e0f2fe', 'icon_color' => '#0369a1', 'label' => 'Resource'),
    'page' => array('icon' => 'bi-file-earmark', 'color' => '#f3f4f6', 'icon_color' => '#374151', 'label' => 'Page'),
    'label' => array('icon' => 'bi-tag', 'color' => '#fce7f3', 'icon_color' => '#be185d', 'label' => 'Label'),
);
$icon_info = $is_lesson
    ? ($is_video_lesson
        ? array('icon' => 'bi-play-btn', 'color' => '#fee2e2', 'icon_color' => '#b91c1c', 'label' => 'Video Lesson')
        : array('icon' => 'bi-file-text', 'color' => '#dbeafe', 'icon_color' => '#1e40af', 'label' => 'Lesson'))
    : ($activity_icons[$item->type] ?? $activity_icons['page']);
$previous_item = $navigation['previous'] ?? null;
$next_item = $navigation['next'] ?? null;
$can_switch_student_mode = isset($original_role_slug) && in_array($original_role_slug, array('teacher', 'course_creator'));
$is_completed = !empty($lesson_progress) && $lesson_progress->status === 'completed';
$is_next_accessible = empty($next_item) || empty($is_student_mode) || !isset($next_item->is_accessible) || $next_item->is_accessible;
?>

<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('course/content/' . $subject->id . (empty($is_student_mode) ? '?edit=1' : '')) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Course Content
            </a>
        </div>

        <div class="data-table">
            <div class="table-header">
                <div class="d-flex align-items-center">
                    <div class="item-view-icon me-3" style="background:<?= $icon_info['color'] ?>;color:<?= $icon_info['icon_color'] ?>;">
                        <i class="bi <?= $icon_info['icon'] ?>"></i>
                    </div>
                    <div>
                        <h5 class="mb-1"><?= $item->title ?></h5>
                        <div class="text-muted small">
                            <?= $subject->code ?> &middot; <?= $module->title ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <?php if ($can_switch_student_mode): ?>
                        <a href="<?= site_url('mode/toggle') ?>" class="btn btn-sm <?= !empty($is_student_mode) ? 'btn-warning' : 'btn-outline-secondary' ?>">
                            <i class="bi <?= !empty($is_student_mode) ? 'bi-person-badge-fill' : 'bi-eye-fill' ?> me-1"></i>
                            <?= !empty($is_student_mode) ? 'Exit Student Mode' : 'View as Student' ?>
                        </a>
                    <?php endif; ?>
                    <?php if (empty($is_student_mode)): ?>
                        <a href="<?= site_url('course/content/' . $subject->id . '?edit=1#module-' . $module->id) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Manage
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-4">
                <?php if (!empty($is_student_mode)): ?>
                    <div class="item-progress-panel mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Course Progress</span>
                            <span><?= (int) ($progress_percent ?? 0) ?>%</span>
                        </div>
                        <div class="progress" style="height:10px;">
                            <div class="progress-bar" role="progressbar" style="width:<?= (int) ($progress_percent ?? 0) ?>%;" aria-valuenow="<?= (int) ($progress_percent ?? 0) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <span class="badge bg-light text-dark border"><?= $icon_info['label'] ?></span>
                    <?php if ($is_lesson && !empty($item->content_type)): ?>
                        <span class="badge bg-light text-dark border"><?= ucfirst($item->content_type) ?></span>
                    <?php endif; ?>
                    <?php if ($is_lesson && !empty($item->duration_minutes)): ?>
                        <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i><?= $item->duration_minutes ?> min</span>
                    <?php endif; ?>
                    <?php if (empty($item->is_published)): ?>
                        <span class="badge bg-secondary">Hidden</span>
                    <?php else: ?>
                        <span class="badge bg-success">Published</span>
                    <?php endif; ?>
                    <?php if (!empty($is_student_mode) && $is_lesson): ?>
                        <?php if ($is_completed): ?>
                            <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i>Completed</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>In Progress</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($item->content)): ?>
                    <div class="item-view-content">
                        <?= $item->content ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5" style="color:#94a3b8;">
                        <i class="bi bi-file-earmark-text" style="font-size:3rem;display:block;margin-bottom:1rem;"></i>
                        <p class="mb-0">No content has been added yet.</p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($is_student_mode) && $is_lesson): ?>
                    <div class="lesson-completion-panel">
                        <?php if ($is_completed): ?>
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle-fill me-2"></i>This lesson is complete.
                            </div>
                        <?php else: ?>
                            <form action="<?= site_url('course/complete_lesson/' . $item->id) ?>" method="post">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check2-circle me-1"></i>Mark Lesson Complete
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="item-view-nav">
                    <?php if ($previous_item): ?>
                        <a href="<?= $previous_item->url ?>" class="item-nav-link item-nav-link-prev">
                            <i class="bi bi-arrow-left"></i>
                            <span>
                                <small>Previous</small>
                                <strong><?= $previous_item->title ?></strong>
                                <em><?= $previous_item->module_title ?></em>
                            </span>
                        </a>
                    <?php else: ?>
                        <span class="item-nav-link item-nav-link-disabled">
                            <i class="bi bi-arrow-left"></i>
                            <span>
                                <small>Previous</small>
                                <strong>No previous content</strong>
                            </span>
                        </span>
                    <?php endif; ?>

                    <?php if ($next_item && $is_next_accessible): ?>
                        <a href="<?= $next_item->url ?>" class="item-nav-link item-nav-link-next">
                            <span>
                                <small>Next</small>
                                <strong><?= $next_item->title ?></strong>
                                <em><?= $next_item->module_title ?></em>
                            </span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php elseif ($next_item): ?>
                        <span class="item-nav-link item-nav-link-disabled item-nav-link-next">
                            <span>
                                <small>Next</small>
                                <strong><?= $next_item->title ?></strong>
                                <em>Complete this lesson to unlock</em>
                            </span>
                            <i class="bi bi-lock-fill"></i>
                        </span>
                    <?php else: ?>
                        <span class="item-nav-link item-nav-link-disabled item-nav-link-next">
                            <span>
                                <small>Next</small>
                                <strong>No next content</strong>
                            </span>
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.item-view-icon {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.item-view-content {
    color: #334155;
    line-height: 1.7;
}
.item-view-content :first-child {
    margin-top: 0;
}
.item-view-content :last-child {
    margin-bottom: 0;
}
.item-view-content img,
.item-view-content iframe,
.item-view-content video {
    max-width: 100%;
}
.item-view-content .lesson-video-embed {
    margin-bottom: 1.5rem;
}
.item-view-content .lesson-video-embed iframe {
    width: 100%;
    height: 100%;
    border: 0;
    border-radius: 8px;
    background: #0f172a;
}
.item-view-content .lesson-video-embed video {
    display: block;
    width: 100%;
    border-radius: 8px;
    background: #0f172a;
}
.item-view-content .lesson-video-notes {
    margin-top: 1.5rem;
}
.item-progress-panel,
.lesson-completion-panel {
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #f8fafc;
}
.lesson-completion-panel {
    margin-top: 2rem;
}
.item-view-nav {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.25rem;
    border-top: 1px solid #e2e8f0;
}
.item-nav-link {
    min-height: 82px;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #1e293b;
    text-decoration: none;
    background: #fff;
    transition: all 0.2s ease;
}
.item-nav-link:not(.item-nav-link-disabled):hover {
    border-color: #818cf8;
    color: #3730a3;
    background: #eef2ff;
}
.item-nav-link i {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    flex-shrink: 0;
}
.item-nav-link span {
    min-width: 0;
    display: flex;
    flex-direction: column;
}
.item-nav-link small {
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
}
.item-nav-link strong,
.item-nav-link em {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.item-nav-link em {
    color: #64748b;
    font-size: 0.8rem;
    font-style: normal;
}
.item-nav-link-next {
    justify-content: flex-end;
    text-align: right;
}
.item-nav-link-disabled {
    color: #94a3b8;
    background: #f8fafc;
}
@media (max-width: 767.98px) {
    .item-view-nav {
        grid-template-columns: 1fr;
    }
    .item-nav-link-next {
        text-align: left;
    }
}
</style>
