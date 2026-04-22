<?php $subject_system_type = strtolower($subject->system_type ?: 'general'); ?>
<div class="lesson-page">
    <div class="lesson-breadcrumb">
        <a href="<?= site_url('student/content/' . $subject->id) ?>" class="breadcrumb-item">
            <i class="bi bi-arrow-left"></i> Back to Course
        </a>
    </div>
    
    <div class="lesson-header">
        <div class="lesson-info">
            <span class="lesson-badge <?= htmlspecialchars($subject_system_type) ?>"><?= htmlspecialchars(strtoupper($subject_system_type)) ?></span>
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

<style>
.lesson-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0.5rem 0 1.5rem;
}

.lesson-breadcrumb {
    margin-bottom: 1rem;
}

.breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #2f6fed;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
}

.breadcrumb-item:hover {
    color: #1f5ecf;
}

.lesson-header {
    padding: 0.25rem 0 0.5rem;
    margin-bottom: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.lesson-info {
    flex: 1;
    min-width: 0;
}

.lesson-badge {
    display: inline-flex;
    padding: 0.32rem 0.7rem;
    border-radius: 10px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 0.9rem;
    letter-spacing: 0;
}

.lesson-badge.deped {
    background: #edf4ff;
    color: #2f6fed;
}

.lesson-badge.ched {
    background: #fff6df;
    color: #9a6700;
}

.lesson-badge.tesda {
    background: #e9f8f0;
    color: #0f8b5f;
}

.lesson-badge.general {
    background: #eef2f7;
    color: #475467;
}

.lesson-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.7rem 0;
    line-height: 1.25;
    overflow-wrap: anywhere;
}

.lesson-meta {
    display: flex;
    gap: 1rem;
    margin: 0;
    font-size: 0.9rem;
    color: #667085;
    flex-wrap: wrap;
}

.lesson-meta span {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
}

.lesson-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.completed-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #e9f8f0;
    color: #0f8b5f;
    border: 1px solid #cfeede;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
}

.lesson-content {
    background: #fff;
    border-radius: 8px;
    padding: 2.5rem;
    border: 1px solid #e4e7ec;
    line-height: 1.75;
    color: #182033;
    margin-bottom: 1.25rem;
    max-width: 100%;
    overflow-wrap: anywhere;
    word-break: break-word;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.lesson-content > * {
    max-width: 100%;
}

.lesson-file-display {
    margin-bottom: 2rem;
}

.lesson-file-display iframe {
    border-radius: 14px;
    border: 1px solid #e4e7ec;
}

.file-download-link {
    text-align: center;
    margin-top: 1rem;
}

.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.1rem;
    background: #2f6fed;
    color: #fff;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.2s ease;
}

.btn-download:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(47, 111, 237, 0.2);
}

.lesson-navigation {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1.25rem;
}

.nav-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.15rem;
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    flex: 1;
    min-width: 0;
}

.nav-btn:hover {
    border-color: #d7e6ff;
    box-shadow: 0 10px 22px rgba(24, 32, 51, 0.06);
    transform: translateY(-1px);
}

.nav-btn-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 0;
}

.nav-btn-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #667085;
    text-transform: uppercase;
    letter-spacing: 0;
}

.nav-btn-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #182033;
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.nav-btn i {
    font-size: 1.2rem;
    color: #2f6fed;
    flex-shrink: 0;
}

.nav-prev {
    justify-content: flex-start;
}

.nav-next {
    justify-content: flex-end;
    text-align: right;
}

.nav-next .nav-btn-content {
    align-items: flex-end;
}

.lesson-content h1,
.lesson-content h2,
.lesson-content h3 {
    color: #182033;
    margin-top: 2rem;
}

.lesson-content h1:first-child,
.lesson-content h2:first-child,
.lesson-content h3:first-child {
    margin-top: 0;
}

.lesson-content p {
    margin-bottom: 1.4rem;
}

.lesson-content img {
    max-width: 100%;
    height: auto;
    border-radius: 14px;
    margin: 1.75rem 0;
}

.lesson-content pre {
    background: #151821;
    color: #fff;
    padding: 1.25rem;
    border-radius: 14px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.lesson-content code {
    background: #f1f5f9;
    padding: 0.2rem 0.45rem;
    border-radius: 8px;
    font-size: 0.875rem;
    color: #182033;
}

.lesson-content table {
    display: block;
    width: 100%;
    overflow-x: auto;
}

.lesson-content ul,
.lesson-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.lesson-content li {
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .lesson-header {
        flex-direction: column;
        align-items: stretch;
    }

    .lesson-title {
        font-size: 1.45rem;
    }

    .lesson-content {
        padding: 1.5rem;
    }

    .lesson-navigation {
        flex-direction: column;
    }

    .nav-next .nav-btn-content {
        align-items: flex-start;
    }

    .nav-next {
        text-align: left;
        justify-content: flex-start;
    }
}
</style>
