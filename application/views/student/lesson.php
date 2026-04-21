<div class="lesson-page">
    <div class="breadcrumb">
        <a href="<?= site_url('student/content/' . $subject->id) ?>" class="breadcrumb-item">
            <i class="bi bi-arrow-left"></i> Back to Course
        </a>
    </div>
    
    <div class="lesson-header">
        <div class="lesson-info">
            <span class="lesson-badge <?= $subject->system_type ?>"><?= strtoupper($subject->system_type) ?></span>
            <h1 class="lesson-title"><?= htmlspecialchars($lesson->title) ?></h1>
            <p class="lesson-meta">
                <span><i class="bi bi-book"></i> <?= htmlspecialchars($subject->code) ?></span>
            </p>
        </div>
        <div class="lesson-actions">
            <?php if ($is_completed): ?>
                <span class="completed-badge">
                    <i class="bi bi-check-circle"></i> Completed
                </span>
            <?php else: ?>
                <button onclick="markLessonComplete(<?= $lesson->id ?>)" class="btn-complete">
                    <i class="bi bi-check-circle"></i> Mark Complete
                </button>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="lesson-content">
        <?= $lesson->content ?>
    </div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
.lesson-page { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; max-width: 1000px; margin: 0 auto; padding: 2rem 1rem; }
.breadcrumb { margin-bottom: 2rem; }
.breadcrumb-item { display: inline-flex; align-items: center; gap: 0.5rem; color: #6366f1; text-decoration: none; font-weight: 500; font-size: 0.9rem; }
.breadcrumb-item:hover { color: #4f46e5; }

.lesson-header { background: #fff; border-radius: 20px; padding: 2.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; gap: 2rem; flex-wrap: wrap; }
.lesson-info { flex: 1; min-width: 0; }
.lesson-badge { display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem; }
.lesson-badge.deped { background: #dbeafe; color: #1e40af; }
.lesson-badge.ched { background: #fef3c7; color: #92400e; }
.lesson-title { font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0 0 0.75rem 0; line-height: 1.2; }
.lesson-meta { display: flex; gap: 1.5rem; margin: 0; font-size: 0.9rem; color: #64748b; }
.lesson-meta span { display: inline-flex; align-items: center; gap: 0.5rem; }

.lesson-actions { display: flex; flex-direction: column; gap: 1rem; }
.btn-complete { padding: 1rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.2s ease; }
.btn-complete:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }
.completed-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2rem; background: #22c55e; color: #fff; border-radius: 12px; font-size: 1rem; font-weight: 600; }

.lesson-content { background: #fff; border-radius: 20px; padding: 3rem; border: 1px solid #e2e8f0; line-height: 1.8; color: #1e293b; }
.lesson-content h1, .lesson-content h2, .lesson-content h3 { color: #1e293b; margin-top: 2rem; }
.lesson-content h1:first-child, .lesson-content h2:first-child, .lesson-content h3:first-child { margin-top: 0; }
.lesson-content p { margin-bottom: 1.5rem; }
.lesson-content img { max-width: 100%; height: auto; border-radius: 12px; margin: 2rem 0; }
.lesson-content pre { background: #1e293b; color: #fff; padding: 1.5rem; border-radius: 12px; overflow-x: auto; margin: 1.5rem 0; }
.lesson-content code { background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem; color: #1e293b; }
.lesson-content ul, .lesson-content ol { margin: 1.5rem 0; padding-left: 2rem; }
.lesson-content li { margin-bottom: 0.5rem; }

@media (max-width: 768px) {
    .lesson-header { flex-direction: column; align-items: stretch; }
    .lesson-title { font-size: 1.5rem; }
    .lesson-content { padding: 2rem 1.5rem; }
}
</style>

<script>
function markLessonComplete(lessonId) {
    const btn = event.target.closest('.btn-complete');
    btn.innerHTML = '<i class="bi bi-spinner"></i> Saving...';
    btn.disabled = true;
    
    fetch('<?= site_url('student/mark_lesson/' . $subject->id . '/' . $lesson->id) ?>', {
        method: 'POST'
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
