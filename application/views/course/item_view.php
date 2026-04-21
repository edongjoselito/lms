<?php
$is_lesson = $item_type === 'lesson';
$activity_icons = array(
    'assignment' => array('icon' => 'bi-clipboard-check', 'color' => '#dcfce7', 'icon_color' => '#166534', 'label' => 'Assignment'),
    'quiz' => array('icon' => 'bi-question-circle', 'color' => '#fef3c7', 'icon_color' => '#92400e', 'label' => 'Quiz'),
    'forum' => array('icon' => 'bi-chat-dots', 'color' => '#ede9fe', 'icon_color' => '#6d28d9', 'label' => 'Forum'),
    'resource' => array('icon' => 'bi-link', 'color' => '#e0f2fe', 'icon_color' => '#0369a1', 'label' => 'Resource'),
    'page' => array('icon' => 'bi-file-earmark', 'color' => '#f3f4f6', 'icon_color' => '#374151', 'label' => 'Page'),
    'label' => array('icon' => 'bi-tag', 'color' => '#fce7f3', 'icon_color' => '#be185d', 'label' => 'Label'),
);
$icon_info = $is_lesson
    ? array('icon' => 'bi-file-text', 'color' => '#dbeafe', 'icon_color' => '#1e40af', 'label' => 'Lesson')
    : ($activity_icons[$item->type] ?? $activity_icons['page']);
?>

<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('course/content/' . $subject->id . '?edit=1') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
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
                <a href="<?= site_url('course/content/' . $subject->id . '?edit=1#module-' . $module->id) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Manage
                </a>
            </div>

            <div class="p-4">
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
</style>
