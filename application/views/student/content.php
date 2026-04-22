<?php
$progress_percent = max(0, min(100, (int) ($progress_percent ?? 0)));
$total_lessons = (int) ($total_lessons ?? 0);
$completed_lesson_ids = array_map('intval', $completed_lesson_ids ?? array());
$accessible_lesson_ids = array_map('intval', $accessible_lesson_ids ?? array());
$completed_count = count($completed_lesson_ids);
$subject_title = trim($subject->description ?: $subject->name);
$subject_title = $subject_title !== '' ? $subject_title : 'Course content';
$subject_system_type = strtolower($subject->system_type ?: 'general');
?>
<div class="student-content-page">
    <a href="<?= site_url('student') ?>" class="course-back">
        <i class="bi bi-arrow-left"></i> Dashboard
    </a>

    <div class="content-header">
        <div class="header-main">
            <div class="subject-badge <?= htmlspecialchars($subject_system_type) ?>"><?= htmlspecialchars(strtoupper($subject_system_type)) ?></div>
            <h1 class="subject-title"><?= htmlspecialchars($subject->code) ?> - <?= htmlspecialchars($subject_title) ?></h1>
            <p class="subject-meta">
                <span><i class="bi bi-collection"></i> <?= count($modules) ?> Modules</span>
                <span><i class="bi bi-book"></i> <?= $total_lessons ?> Lessons</span>
            </p>
        </div>
        <div class="header-actions">
            <div class="progress-card">
                <div class="progress-header">
                    <span class="progress-label">Progress</span>
                    <span class="progress-value"><?= $progress_percent ?>%</span>
                </div>
                <div class="progress-track" role="progressbar" aria-valuenow="<?= $progress_percent ?>" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-fill" style="width: <?= $progress_percent ?>%"></div>
                </div>
                <p class="progress-status">
                    <?= $completed_count ?> of <?= $total_lessons ?> lessons completed
                </p>
            </div>
            <a href="<?= site_url('student/unenroll/' . $subject->id) ?>" class="btn-unenroll">
                <i class="bi bi-x-circle"></i> Unenroll
            </a>
        </div>
    </div>
    
    <?php if (empty($modules)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-folder-x"></i>
            </div>
            <h3>No content available</h3>
            <p>This course doesn't have any published content yet.</p>
        </div>
    <?php else: ?>
        <div class="modules-container">
            <?php foreach ($modules as $module_index => $module): ?>
                <div class="module-card">
                    <div class="module-header">
                        <div class="module-icon">
                            <?= $module_index + 1 ?>
                        </div>
                        <div class="module-info">
                            <h2 class="module-title"><?= htmlspecialchars($module->title) ?></h2>
                            <p class="module-meta">
                                <span><i class="bi bi-file-text"></i> <?= count($module->lessons) ?> Lessons</span>
                            </p>
                        </div>
                    </div>
                    
                    <?php if (!empty($module->lessons)): ?>
                        <div class="lessons-list">
                            <?php 
                            foreach ($module->lessons as $lesson): 
                                $lesson_id = (int) $lesson->id;
                                $is_completed = in_array($lesson_id, $completed_lesson_ids, true);
                                $is_locked = !in_array($lesson_id, $accessible_lesson_ids, true);
                                $lesson_excerpt = trim(substr(strip_tags($lesson->content), 0, 120));
                                $lesson_excerpt = $lesson_excerpt !== '' ? $lesson_excerpt . '...' : 'Lesson content';
                            ?>
                                <?php if ($is_locked): ?>
                                    <div class="lesson-card locked">
                                        <div class="lesson-icon">
                                            <i class="bi bi-lock"></i>
                                        </div>
                                        <div class="lesson-content">
                                            <h3 class="lesson-title"><?= htmlspecialchars($lesson->title) ?></h3>
                                            <p class="lesson-desc"><?= htmlspecialchars($lesson_excerpt) ?></p>
                                            <span class="locked-badge">
                                                <i class="bi bi-lock"></i> Complete previous lesson first
                                            </span>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= site_url('student/lesson/' . $subject->id . '/' . $lesson->id) ?>" class="lesson-card <?= $is_completed ? 'completed' : '' ?>">
                                        <div class="lesson-icon">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                        <div class="lesson-content">
                                            <h3 class="lesson-title"><?= htmlspecialchars($lesson->title) ?></h3>
                                            <p class="lesson-desc"><?= htmlspecialchars($lesson_excerpt) ?></p>
                                        </div>
                                        <div class="lesson-actions">
                                            <?php if ($is_completed): ?>
                                                <span class="completed-badge">
                                                    <i class="bi bi-check-circle"></i> Completed
                                                </span>
                                            <?php else: ?>
                                                <span class="view-badge">
                                                    <i class="bi bi-arrow-right"></i> View
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-lessons">
                            <i class="bi bi-inbox"></i>
                            <p>No lessons in this module yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.student-content-page {
    width: 100%;
    padding: 0.5rem 0 1.5rem;
}

.course-back {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 1rem;
    color: #2f6fed;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 700;
}

.course-back:hover {
    color: #1f5ecf;
}

.content-header {
    padding: 0.25rem 0 0.5rem;
    color: #182033;
    margin-bottom: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.header-main {
    flex: 1;
    min-width: 0;
}

.subject-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.32rem 0.7rem;
    border-radius: 10px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 0.9rem;
    letter-spacing: 0;
}

.subject-badge.deped {
    background: #edf4ff;
    color: #2f6fed;
}

.subject-badge.ched {
    background: #fff6df;
    color: #9a6700;
}

.subject-badge.tesda {
    background: #e9f8f0;
    color: #0f8b5f;
}

.subject-badge.general {
    background: #eef2f7;
    color: #475467;
}

.subject-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 0.75rem 0;
    line-height: 1.25;
    overflow-wrap: anywhere;
}

.subject-meta {
    display: flex;
    gap: 1rem;
    margin: 0;
    font-size: 0.9rem;
    color: #667085;
    flex-wrap: wrap;
}

.subject-meta span,
.module-meta span {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
}

.header-actions {
    display: flex;
    align-items: stretch;
    gap: 0.75rem;
}

.progress-card {
    background: #f8fafc;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    padding: 1rem;
    min-width: 250px;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 700;
    color: #475467;
}

.progress-value {
    font-size: 1.35rem;
    font-weight: 700;
    color: #182033;
}

.progress-track {
    height: 8px;
    background: #e4e7ec;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 0.65rem;
}

.progress-fill {
    height: 100%;
    background: #10b981;
    border-radius: 999px;
    transition: width 0.3s ease;
}

.progress-status {
    margin: 0;
    font-size: 0.8rem;
    color: #667085;
}

.btn-unenroll {
    min-width: 118px;
    padding: 0.8rem 1rem;
    background: #fff5f5;
    color: #c24132;
    border: 1px solid #ffd6d1;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-unenroll:hover {
    background: #ffe8e5;
    color: #a83225;
}

.modules-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.module-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e4e7ec;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.module-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: #fff;
    border-bottom: 1px solid #edf0f4;
}

.module-icon {
    width: 44px;
    height: 44px;
    background: #edf4ff;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2f6fed;
    font-size: 1rem;
    font-weight: 800;
    flex-shrink: 0;
}

.module-info {
    flex: 1;
    min-width: 0;
}

.module-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.25rem 0;
}

.module-meta {
    margin: 0;
    font-size: 0.85rem;
    color: #667085;
}

.lessons-list {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.lesson-card {
    display: flex;
    align-items: center;
    gap: 0.95rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #edf0f4;
    transition: all 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.lesson-card:hover {
    background: #fff;
    border-color: #d7e6ff;
    box-shadow: 0 10px 22px rgba(24, 32, 51, 0.06);
    transform: translateY(-1px);
}

.lesson-card.completed {
    background: #f5fbf8;
    border-color: #cfeede;
}

.lesson-card.locked {
    background: #f7f8fa;
    border-color: #e4e7ec;
    opacity: 0.72;
    pointer-events: none;
}

.lesson-icon {
    width: 42px;
    height: 42px;
    background: #edf4ff;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2f6fed;
    font-size: 1.15rem;
    flex-shrink: 0;
}

.lesson-card.completed .lesson-icon {
    background: #e9f8f0;
    color: #0f8b5f;
}

.lesson-card.locked .lesson-icon {
    background: #eef2f7;
    color: #667085;
}

.lesson-content {
    flex: 1;
    min-width: 0;
}

.lesson-title {
    font-size: 1rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.25rem 0;
}

.lesson-desc {
    margin: 0;
    font-size: 0.85rem;
    color: #667085;
    line-height: 1.45;
}

.lesson-actions {
    flex-shrink: 0;
}

.completed-badge,
.view-badge,
.locked-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 0.8rem;
    border-radius: 12px;
    font-size: 0.82rem;
    font-weight: 700;
}

.completed-badge {
    background: #10b981;
    color: #fff;
}

.view-badge {
    background: #2f6fed;
    color: #fff;
}

.locked-badge {
    background: #eef2f7;
    color: #667085;
}

.no-lessons {
    text-align: center;
    padding: 2.5rem 1.5rem;
    color: #98a2b3;
}

.no-lessons i {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
    display: block;
}

.no-lessons p {
    margin: 0;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: #fff;
    border-radius: 8px;
    border: 1px dashed #cfd6e3;
}

.empty-icon {
    width: 72px;
    height: 72px;
    background: #edf4ff;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.empty-icon i {
    font-size: 2rem;
    color: #2f6fed;
}

.empty-state h3 {
    font-size: 1.25rem;
    color: #182033;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    margin: 0;
    color: #667085;
}

@media (max-width: 768px) {
    .content-header {
        flex-direction: column;
        align-items: stretch;
    }

    .header-actions {
        flex-direction: column;
    }

    .progress-card {
        min-width: auto;
    }

    .subject-title {
        font-size: 1.45rem;
    }

    .lesson-card {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .lesson-actions {
        width: 100%;
    }

    .completed-badge,
    .view-badge,
    .locked-badge {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function markLessonComplete(lessonId) {
    const btn = typeof event !== 'undefined' ? event.target.closest('.btn-complete') : null;
    if (btn) {
        btn.innerHTML = '<i class="bi bi-spinner"></i> Saving...';
        btn.disabled = true;
    }
    const markLessonUrl = '<?= site_url('student/mark_lesson/' . $subject->id) ?>/' + encodeURIComponent(lessonId);
    
    fetch(markLessonUrl, {
        method: 'POST'
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
            return;
        }

        toast.error(data.message || 'Failed to mark lesson as complete.');
        if (btn) {
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Mark Complete';
            btn.disabled = false;
        }
    }).catch(() => {
        toast.error('Failed to mark lesson as complete.');
        if (btn) {
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Mark Complete';
            btn.disabled = false;
        }
    });
}
</script>
