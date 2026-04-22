<div class="student-content-page">
    <div class="breadcrumb">
        <a href="<?= site_url('student/subjects') ?>" class="breadcrumb-item"><i class="bi bi-arrow-left"></i> Back to Subjects</a>
    </div>
    
    <div class="content-header">
        <div class="header-main">
            <div class="subject-badge <?= $subject->system_type ?>"><?= strtoupper($subject->system_type) ?></div>
            <h1 class="subject-title"><?= htmlspecialchars($subject->code) ?> - <?= htmlspecialchars($subject->description) ?></h1>
            <p class="subject-meta">
                <span><i class="bi bi-collection"></i> <?= count($modules) ?> Modules</span>
                <span><i class="bi bi-book"></i> <?= $total_lessons ?? 0 ?> Lessons</span>
            </p>
        </div>
        <div class="header-actions">
            <div class="progress-card">
                <div class="progress-header">
                    <span class="progress-label">Progress</span>
                    <span class="progress-value"><?= $progress_percent ?>%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: <?= $progress_percent ?>%"></div>
                </div>
                <p class="progress-status">
                    <?= count($completed_lesson_ids) ?> of <?= $total_lessons ?? 0 ?> lessons completed
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
            <?php foreach ($modules as $module): ?>
                <div class="module-card">
                    <div class="module-header">
                        <div class="module-icon">
                            <i class="bi bi-folder"></i>
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
                            $lesson_index = 0;
                            foreach ($module->lessons as $lesson): 
                                $is_completed = in_array($lesson->id, $completed_lesson_ids);
                                $is_locked = false;
                                
                                // Check if this is not the first lesson and previous lesson is not completed
                                if ($lesson_index > 0) {
                                    $previous_lesson = $module->lessons[$lesson_index - 1];
                                    if (!in_array($previous_lesson->id, $completed_lesson_ids)) {
                                        $is_locked = true;
                                    }
                                }
                                $lesson_index++;
                            ?>
                                <?php if ($is_locked): ?>
                                    <div class="lesson-card locked">
                                        <div class="lesson-icon">
                                            <i class="bi bi-lock"></i>
                                        </div>
                                        <div class="lesson-content">
                                            <h3 class="lesson-title"><?= htmlspecialchars($lesson->title) ?></h3>
                                            <p class="lesson-desc"><?= substr(strip_tags($lesson->content), 0, 120) ?>...</p>
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
                                            <p class="lesson-desc"><?= substr(strip_tags($lesson->content), 0, 120) ?>...</p>
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

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
.student-content-page { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; }
.breadcrumb { margin-bottom: 2rem; }
.breadcrumb-item { display: inline-flex; align-items: center; gap: 0.5rem; color: #6366f1; text-decoration: none; font-weight: 500; font-size: 0.9rem; }
.breadcrumb-item:hover { color: #4f46e5; }

.content-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 2.5rem; color: #fff; margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; gap: 2rem; flex-wrap: wrap; }
.header-main { flex: 1; min-width: 0; }
.subject-badge { display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem; background: rgba(255,255,255,0.2); }
.subject-badge.deped { background: #dbeafe; color: #1e40af; }
.subject-badge.ched { background: #fef3c7; color: #92400e; }
.subject-badge.tesda { background: #dcfce7; color: #166534; }
.subject-title { font-size: 2rem; font-weight: 700; margin: 0 0 0.75rem 0; line-height: 1.2; }
.subject-meta { display: flex; gap: 1.5rem; margin: 0; font-size: 0.9rem; opacity: 0.9; }
.subject-meta span { display: inline-flex; align-items: center; gap: 0.5rem; }

.header-actions { display: flex; flex-direction: column; gap: 1rem; }
.progress-card { background: rgba(255,255,255,0.15); border-radius: 16px; padding: 1.5rem; min-width: 250px; }
.progress-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
.progress-label { font-size: 0.875rem; font-weight: 500; opacity: 0.9; }
.progress-value { font-size: 1.5rem; font-weight: 700; }
.progress-track { height: 8px; background: rgba(255,255,255,0.2); border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem; }
.progress-fill { height: 100%; background: #fff; border-radius: 4px; transition: width 0.3s ease; }
.progress-status { margin: 0; font-size: 0.8rem; opacity: 0.8; }
.btn-unenroll { padding: 0.875rem 1.5rem; background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); border-radius: 10px; font-size: 0.875rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease; }
.btn-unenroll:hover { background: rgba(255,255,255,0.3); border-color: rgba(255,255,255,0.5); }

.modules-container { display: flex; flex-direction: column; gap: 1.5rem; }
.module-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.module-header { display: flex; align-items: center; gap: 1rem; padding: 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.module-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; flex-shrink: 0; }
.module-info { flex: 1; min-width: 0; }
.module-title { font-size: 1.25rem; font-weight: 600; color: #1e293b; margin: 0 0 0.25rem 0; }
.module-meta { margin: 0; font-size: 0.85rem; color: #64748b; }
.module-meta span { display: inline-flex; align-items: center; gap: 0.5rem; }

.lessons-list { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
.lesson-card { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; transition: all 0.2s ease; text-decoration: none; color: inherit; }
.lesson-card:hover { background: #f1f5f9; border-color: #cbd5e1; transform: translateY(-2px); }
.lesson-card.completed { background: #f0fdf4; border-color: #bbf7d0; }
.lesson-card.locked { background: #f1f5f9; border-color: #cbd5e1; opacity: 0.6; pointer-events: none; }
.lesson-icon { width: 40px; height: 40px; background: #dbeafe; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #1e40af; font-size: 1.25rem; flex-shrink: 0; }
.lesson-card.completed .lesson-icon { background: #dcfce7; color: #166534; }
.lesson-card.locked .lesson-icon { background: #e2e8f0; color: #64748b; }
.lesson-content { flex: 1; min-width: 0; }
.lesson-title { font-size: 1rem; font-weight: 600; color: #1e293b; margin: 0 0 0.25rem 0; }
.lesson-desc { margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.4; }
.lesson-actions { flex-shrink: 0; }
.completed-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #22c55e; color: #fff; border-radius: 10px; font-size: 0.875rem; font-weight: 600; }
.view-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 10px; font-size: 0.875rem; font-weight: 600; }
.locked-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #94a3b8; color: #fff; border-radius: 10px; font-size: 0.875rem; font-weight: 600; }

.no-lessons { text-align: center; padding: 3rem 1.5rem; color: #94a3b8; }
.no-lessons i { font-size: 3rem; margin-bottom: 1rem; display: block; }
.no-lessons p { margin: 0; }

.empty-state { text-align: center; padding: 5rem 2rem; background: #f8fafc; border-radius: 20px; border: 2px dashed #e2e8f0; }
.empty-icon { width: 100px; height: 100px; background: #e2e8f0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; }
.empty-icon i { font-size: 3rem; color: #64748b; }
.empty-state h3 { font-size: 1.5rem; color: #1e293b; margin: 0 0 0.5rem 0; }
.empty-state p { margin: 0; color: #64748b; }

@media (max-width: 768px) {
    .content-header { flex-direction: column; align-items: stretch; }
    .progress-card { min-width: auto; }
    .subject-title { font-size: 1.5rem; }
}
</style>

<script>
function markLessonComplete(lessonId) {
    const btn = event.target.closest('.btn-complete');
    btn.innerHTML = '<i class="bi bi-spinner"></i> Saving...';
    btn.disabled = true;
    
    fetch('<?= site_url('student/mark_lesson/' . $subject->id . '/' . lessonId) ?>', {
        method: 'POST'
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
