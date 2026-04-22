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
            <span class="completed-badge">
                <i class="bi bi-check-circle"></i> Completed
            </span>
        </div>
    </div>
    
    <div class="lesson-content">
        <?php if ($lesson->content_type === 'file' && !empty($lesson->file_path)): ?>
            <div class="lesson-file-display">
                <iframe src="<?= htmlspecialchars($lesson->file_path) ?>" width="100%" height="600px" style="border: none;"></iframe>
                <p class="file-download-link">
                    <a href="<?= htmlspecialchars($lesson->file_path) ?>" target="_blank" class="btn-download">
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                </p>
            </div>
        <?php endif; ?>
        <?= $lesson->content ?>
    </div>

    <div class="lesson-navigation">
        <?php if ($previous_lesson): ?>
            <a href="<?= site_url('student/lesson/' . $subject->id . '/' . $previous_lesson->id) ?>" class="nav-btn nav-prev">
                <i class="bi bi-arrow-left"></i>
                <div class="nav-btn-content">
                    <span class="nav-btn-label">Previous</span>
                    <span class="nav-btn-title"><?= htmlspecialchars($previous_lesson->title) ?></span>
                </div>
            </a>
        <?php endif; ?>

        <?php if ($next_lesson): ?>
            <a href="<?= site_url('student/lesson/' . $subject->id . '/' . $next_lesson->id) ?>" class="nav-btn nav-next">
                <div class="nav-btn-content">
                    <span class="nav-btn-label">Next</span>
                    <span class="nav-btn-title"><?= htmlspecialchars($next_lesson->title) ?></span>
                </div>
                <i class="bi bi-arrow-right"></i>
            </a>
        <?php endif; ?>
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
.lesson-badge.tesda { background: #dcfce7; color: #166534; }
.lesson-title { font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0 0 0.75rem 0; line-height: 1.2; }
.lesson-meta { display: flex; gap: 1.5rem; margin: 0; font-size: 0.9rem; color: #64748b; }
.lesson-meta span { display: inline-flex; align-items: center; gap: 0.5rem; }

.lesson-actions { display: flex; flex-direction: column; gap: 1rem; }
.btn-complete { padding: 1rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.2s ease; }
.btn-complete:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }
.completed-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2rem; background: #22c55e; color: #fff; border-radius: 12px; font-size: 1rem; font-weight: 600; }

.lesson-content { background: #fff; border-radius: 20px; padding: 3rem; border: 1px solid #e2e8f0; line-height: 1.8; color: #1e293b; margin-bottom: 2rem; }

.lesson-file-display { margin-bottom: 2rem; }
.lesson-file-display iframe { border-radius: 12px; border: 1px solid #e2e8f0; }
.file-download-link { text-align: center; margin-top: 1rem; }
.btn-download { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #6366f1; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s ease; }
.btn-download:hover { background: #4f46e5; transform: translateY(-1px); }

.lesson-navigation { display: flex; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
.nav-btn { display: flex; align-items: center; gap: 1rem; padding: 1.5rem 2rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; text-decoration: none; transition: all 0.2s ease; }
.nav-btn:hover { border-color: #6366f1; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15); transform: translateY(-2px); }
.nav-btn-content { display: flex; flex-direction: column; gap: 0.25rem; }
.nav-btn-label { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.nav-btn-title { font-size: 0.95rem; font-weight: 600; color: #1e293b; line-height: 1.3; }
.nav-btn i { font-size: 1.25rem; color: #6366f1; }
.nav-prev { justify-content: flex-start; }
.nav-next { justify-content: flex-end; text-align: right; }
.nav-next .nav-btn-content { align-items: flex-end; }
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
