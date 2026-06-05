<?php
$subject_system_type = strtolower(isset($subject->system_type) ? $subject->system_type : 'general');
$is_video_lesson = !empty($is_video_lesson);
$is_pdf_lesson = !empty($is_pdf_lesson);
$completion_gate_type = '';
if (empty($is_completed)) {
    if ($is_video_lesson) {
        $completion_gate_type = 'video';
    } elseif ($is_pdf_lesson) {
        $completion_gate_type = 'pdf';
    }
}
$completion_gate_required = $completion_gate_type !== '';
$next_locked = !empty($next_item) && $completion_gate_required;
$student_csrf_token_name = $this->security->get_csrf_token_name();
$student_csrf_hash = $this->security->get_csrf_hash();
$lesson_pdf_url = $is_pdf_lesson ? (string) $lesson->file_path : '';
$completion_pill_text = $completion_gate_type === 'pdf' ? 'Scroll PDF to complete' : 'Watch video to complete';
$completion_note_title = $completion_gate_type === 'pdf'
    ? 'Please read the lesson carefully and make sure you fully understand the content.'
    : 'Finish the video to complete this lesson';
$completion_note_text = $completion_gate_type === 'pdf'
    ? 'Your progress and the next content will unlock only after you reach the end of the PDF.'
    : 'Your progress and the next content will unlock only after the video reaches the end.';
$completion_note_icon = $completion_gate_type === 'pdf' ? 'bi-file-earmark-pdf' : 'bi-play-circle';
?>

<div class="lp-wrap">

    <!-- Breadcrumb -->
    <div class="lp-breadcrumb" style="grid-column: 1 / -1;">
        <a href="<?= site_url('student/content/' . $subject->id) ?>" class="lp-back-link">
            <span class="lp-back-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            Back to Course
        </a>
    </div>

    <!-- Header Card -->
    <div class="lp-header-card" style="grid-column: 1 / -1;">
        <div class="lp-header-left">
            <div class="lp-badge lp-badge--<?= htmlspecialchars($subject_system_type) ?>">
                <?php
                $icons = [
                    'deped'   => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M12 3L2 9l10 6 10-6-10-6zM2 15l10 6 10-6M2 9l10 6 10-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
                    'ched'    => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M22 10v9a1 1 0 01-1 1H3a1 1 0 01-1-1v-9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 2L2 7h20L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
                    'tesda'   => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/></svg>',
                    'general' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
                ];
                echo $icons[$subject_system_type] ?? $icons['general'];
                ?>
                <?= htmlspecialchars(strtoupper($subject_system_type)) ?>
            </div>

            <h1 class="lp-title"><?= htmlspecialchars($lesson->title) ?></h1>

            <div class="lp-meta">
                <span class="lp-meta-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" stroke="currentColor" stroke-width="1.8" />
                    </svg>
                    <?= htmlspecialchars($subject->code) ?>
                </span>
                <?php if (!empty($subject->name)): ?>
                    <span class="lp-meta-sep">·</span>
                    <span class="lp-meta-item"><?= htmlspecialchars($subject->name) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="lp-header-right">
            <div class="lp-progress-indicator">
                <span class="lp-progress-percent"><?= isset($progress_percent) ? $progress_percent : 0 ?>%</span>
                <span class="lp-progress-label">Complete</span>
            </div>
            <div class="lp-completed-pill<?= $completion_gate_required ? ' lp-completed-pill--pending' : '' ?>" id="lessonStatusPill">
                <?php if ($completion_gate_required): ?>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <?= htmlspecialchars($completion_pill_text) ?>
                <?php else: ?>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Completed
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Lesson Body -->
    <div class="lp-body" style="grid-column: 1 / -1;">

        <?php if ($lesson->content_type === 'file' && !empty($lesson->file_path)): ?>
            <div class="lp-file-block">
                <div class="lp-file-toolbar">
                    <span class="lp-file-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.8" />
                            <polyline points="14 2 14 8 20 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        PDF Document
                    </span>
                    <a href="<?= htmlspecialchars($lesson->file_path) ?>" target="_blank" class="lp-download-btn">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5v10M7 15l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5 20h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        Download
                    </a>
                </div>
                <?php if ($is_pdf_lesson): ?>
                    <div class="lp-pdf-viewer">
                        <div class="lp-pdf-viewer-status" id="pdfViewerStatus" data-state="loading">Loading PDF...</div>
                        <div class="lp-pdf-scroll-area" id="pdfScrollArea">
                            <div class="lp-pdf-pages" id="pdfPages"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="lp-iframe-wrap">
                        <iframe src="<?= htmlspecialchars($lesson->file_path) ?>" width="100%" height="640" loading="lazy"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="lp-content-body">
            <?= $lesson->content ?>
        </div>

        <?php if ($completion_gate_required): ?>
            <div class="lp-video-completion-note" id="videoCompletionNote">
                <div class="lp-video-completion-icon">
                    <i class="bi <?= htmlspecialchars($completion_note_icon) ?>"></i>
                </div>
                <div class="lp-video-completion-copy">
                    <strong><?= htmlspecialchars($completion_note_title) ?></strong>
                    <span><?= htmlspecialchars($completion_note_text) ?></span>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Navigation -->
    <div class="lp-nav" style="grid-column: 1 / -1;">
        <?php if ($previous_item): ?>
            <a href="<?= $previous_item->url ?>" class="lp-nav-btn lp-nav-btn--prev">
                <span class="lp-nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <span class="lp-nav-text">
                    <span class="lp-nav-label">Previous <?= ucfirst($previous_item->type) ?></span>
                    <span class="lp-nav-name"><?= htmlspecialchars($previous_item->title) ?></span>
                </span>
            </a>
        <?php else: ?>
            <div></div>
        <?php endif; ?>

        <?php if ($next_item && !$next_locked): ?>
            <a href="<?= $next_item->url ?>" class="lp-nav-btn lp-nav-btn--next">
                <span class="lp-nav-text lp-nav-text--right">
                    <span class="lp-nav-label">Next <?= ucfirst($next_item->type) ?></span>
                    <span class="lp-nav-name"><?= htmlspecialchars($next_item->title) ?></span>
                </span>
                <span class="lp-nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
        <?php elseif ($next_item): ?>
            <div class="lp-nav-btn lp-nav-btn--disabled lp-nav-btn--next">
                <span class="lp-nav-text lp-nav-text--right">
                    <span class="lp-nav-label">Next <?= ucfirst($next_item->type) ?></span>
                    <span class="lp-nav-name"><?= htmlspecialchars($next_item->title) ?></span>
                </span>
                <span class="lp-nav-arrow">
                    <i class="bi bi-lock-fill"></i>
                </span>
            </div>
        <?php endif; ?>
    </div>

</div>

<style>
    /* ── Apple-Level Design System ──────────────────────────────────── */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    .lp-wrap {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem 1.5rem 4rem;
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
        color: #1d1d1f;
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 1.5rem;
    }

    /* ── Breadcrumb ────────────────────────────────────── */
    .lp-breadcrumb {
        margin-bottom: 1.5rem;
    }

    .lp-back-link {
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

    .lp-back-link:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #2563eb;
    }

    .lp-back-icon {
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

    /* ── Header Card ───────────────────────────────────── */
    .lp-header-card {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.5rem;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .lp-header-left {
        flex: 1;
        min-width: 0;
    }

    /* ── Badge ─────────────────────────────────────────── */
    .lp-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .lp-badge--deped {
        background: #dbeafe;
        color: #3b82f6;
    }

    .lp-badge--ched {
        background: #fff4e5;
        color: #f5a623;
    }

    .lp-badge--tesda {
        background: #e6f9e6;
        color: #34c759;
    }

    .lp-badge--general {
        background: #f5f5f7;
        color: #86868b;
    }

    /* ── Title & Meta ──────────────────────────────────── */
    .lp-title {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 700;
        color: #1d1d1f;
        margin: 0 0 0.75rem;
        line-height: 1.25;
        letter-spacing: -0.02em;
        overflow-wrap: anywhere;
    }

    .lp-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 0.875rem;
        color: #86868b;
    }

    .lp-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .lp-meta-sep {
        color: #d2d2d7;
    }

    /* ── Completed Pill ────────────────────────────────── */
    .lp-completed-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 100px;
        background: #e6f9e6;
        color: #34c759;
        font-size: 0.875rem;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .lp-completed-pill--pending {
        background: #fff7ed;
        color: #c2410c;
    }

    /* ── Body (file + content) ─────────────────────────── */
    .lp-body {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    /* ── Progress Indicator ─────────────────────────────── */
    .lp-progress-indicator {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 2px;
        margin-right: 1rem;
    }

    .lp-progress-percent {
        font-size: 1.5rem;
        font-weight: 800;
        color: #3b82f6;
        line-height: 1;
    }

    .lp-progress-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #86868b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* ── File Block ────────────────────────────────────── */
    .lp-file-block {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .lp-file-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: #fafafa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .lp-file-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #86868b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .lp-download-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: #fff;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.15s, transform 0.1s;
    }

    .lp-download-btn:hover {
        background: #2752c9;
        transform: translateY(-1px);
        color: #fff;
    }

    .lp-iframe-wrap {
        background: #f1f5f9;
    }

    .lp-iframe-wrap iframe {
        display: block;
        border: none;
    }

    .lp-pdf-viewer {
        background: #eef2f7;
    }

    .lp-pdf-viewer-status {
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        font-size: 0.84rem;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
    }

    .lp-pdf-viewer-status[data-state="loading"] {
        color: #475569;
    }

    .lp-pdf-viewer-status[data-state="ready"] {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .lp-pdf-viewer-status[data-state="complete"] {
        background: #f0fdf4;
        color: #166534;
    }

    .lp-pdf-viewer-status[data-state="error"] {
        background: #fef2f2;
        color: #b91c1c;
    }

    .lp-pdf-scroll-area {
        padding: 1.25rem;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.35), rgba(255, 255, 255, 0.05)),
            #e2e8f0;
    }

    .lp-pdf-pages {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
    }

    .lp-pdf-page {
        width: fit-content;
        max-width: 100%;
        padding: 0.8rem;
        border-radius: 16px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
    }

    .lp-pdf-page canvas {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .lp-pdf-page-number {
        margin-top: 0.7rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-align: center;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    /* ── Rich Content ──────────────────────────────────── */
    .lp-content-body {
        padding: 2.25rem 2.5rem;
        line-height: 1.8;
        color: #1e293b;
        font-size: 1rem;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .lp-content-body>*:first-child {
        margin-top: 0;
    }

    .lp-content-body>*:last-child {
        margin-bottom: 0;
    }

    .lp-content-body h1,
    .lp-content-body h2,
    .lp-content-body h3,
    .lp-content-body h4 {
        color: #0f172a;
        font-weight: 700;
        line-height: 1.3;
        margin: 2rem 0 0.75rem;
    }

    .lp-content-body h1 {
        font-size: 1.6rem;
    }

    .lp-content-body h2 {
        font-size: 1.35rem;
        border-bottom: 1px solid #e5e9f0;
        padding-bottom: 0.5rem;
    }

    .lp-content-body h3 {
        font-size: 1.1rem;
    }

    .lp-content-body h4 {
        font-size: 1rem;
    }

    .lp-content-body p {
        margin-bottom: 1.35rem;
    }

    .lp-content-body a {
        color: #3b67e8;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .lp-content-body a:hover {
        color: #2752c9;
    }

    .lp-content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        border: 1px solid #e5e9f0;
        margin: 1.75rem 0;
        display: block;
    }

    .lp-content-body pre {
        background: #0f172a;
        color: #e2e8f0;
        padding: 1.25rem 1.5rem;
        border-radius: 10px;
        overflow-x: auto;
        margin: 1.5rem 0;
        font-size: 0.88rem;
    }

    .lp-content-body code {
        background: #f5f5f7;
        color: #ff3b30;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.875rem;
    }

    .lp-video-completion-note {
        margin: 0 2.5rem 2rem;
        padding: 1rem 1.1rem;
        border: 1px solid #fdba74;
        border-radius: 14px;
        background: #fff7ed;
        display: flex;
        gap: 0.9rem;
        align-items: flex-start;
    }

    .lp-video-completion-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(249, 115, 22, 0.12);
        color: #ea580c;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .lp-video-completion-copy {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        color: #9a3412;
    }

    .lp-video-completion-copy strong {
        font-size: 0.92rem;
    }

    .lp-video-completion-copy span {
        font-size: 0.88rem;
        line-height: 1.5;
    }

    /* ── Navigation ─────────────────────────────────────── */
    .lp-nav {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .lp-nav-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 1rem 1.25rem;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .lp-nav-btn:hover {
        background: #fafafa;
        border-color: rgba(0, 0, 0, 0.12);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .lp-nav-btn--disabled,
    .lp-nav-btn--disabled:hover {
        background: #f8fafc;
        color: #94a3b8;
        border-color: #e2e8f0;
        box-shadow: none;
        transform: none;
        cursor: not-allowed;
    }

    .lp-nav-btn--disabled .lp-nav-arrow {
        background: #e2e8f0;
        color: #64748b;
    }

    .lp-nav-btn--disabled .lp-nav-name,
    .lp-nav-btn--disabled .lp-nav-label {
        color: #94a3b8;
    }

    .lp-nav-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #dbeafe;
        border-radius: 8px;
        color: #3b82f6;
        flex-shrink: 0;
    }

    .lp-nav-btn:hover .lp-nav-arrow {
        background: #d4ebff;
    }

    .lp-nav-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .lp-nav-text--right {
        text-align: right;
        flex: 1;
    }

    .lp-nav-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #86868b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .lp-nav-name {
        font-size: 0.9375rem;
        font-weight: 500;
        color: #1d1d1f;
        line-height: 1.35;
        overflow-wrap: anywhere;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .lp-nav-btn--next {
        justify-content: flex-end;
    }

    /* ── Responsive ────────────────────────────────────── */
    @media (max-width: 768px) {
        .lp-wrap {
            padding: 1rem;
        }

        .lp-header-card {
            flex-direction: column;
            gap: 1rem;
            padding: 1.5rem;
        }

        .lp-header-right {
            align-self: flex-start;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .lp-progress-indicator {
            align-items: flex-start;
            margin-right: 0;
        }

        .lp-content-body {
            padding: 1.5rem;
        }

        .lp-pdf-scroll-area {
            padding: 0.85rem;
        }

        .lp-pdf-page {
            padding: 0.5rem;
        }

        .lp-nav {
            grid-template-columns: 1fr;
        }

        .lp-nav-btn--next {
            justify-content: flex-start;
        }

        .lp-nav-text--right {
            text-align: left;
        }

        .lp-file-toolbar {
            padding: 0.875rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .lp-title {
            font-size: 1.375rem;
        }

        .lp-content-body {
            padding: 1.25rem 1rem;
        }
    }

    /* ── Print ─────────────────────────────────────────── */
    @media print {

        .lp-breadcrumb,
        .lp-completed-pill,
        .lp-download-btn,
        .lp-nav {
            display: none;
        }

        .lp-body,
        .lp-header-card {
            border: none;
            box-shadow: none;
        }
    }
</style>

<?php if ($is_pdf_lesson || $completion_gate_type === 'video'): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var csrfTokenName = <?= json_encode($student_csrf_token_name) ?>;
    var csrfHash = <?= json_encode($student_csrf_hash) ?>;
    var markLessonUrl = <?= json_encode(site_url('student/mark_lesson/' . $subject->id . '/' . $lesson->id)) ?>;
    var completionGateType = <?= json_encode($completion_gate_type) ?>;
    var isPdfLesson = <?= json_encode($is_pdf_lesson) ?>;
    var pdfUrl = <?= json_encode($lesson_pdf_url) ?>;
    var completionRequested = false;

    function refreshCsrfToken(tokenName, tokenHash) {
        if (tokenName) {
            csrfTokenName = tokenName;
        }
        if (tokenHash) {
            csrfHash = tokenHash;
        }
    }

    function markLessonComplete(completionFieldName) {
        if (completionRequested || !completionFieldName) {
            return;
        }

        completionRequested = true;

        var formData = new FormData();
        formData.append(csrfTokenName, csrfHash);
        formData.append(completionFieldName, '1');

        fetch(markLessonUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            cache: 'no-store'
        }).then(function (response) {
            return response.json().catch(function () {
                return {
                    success: false,
                    message: 'Unable to update lesson progress.'
                };
            }).then(function (data) {
                return {
                    ok: response.ok,
                    data: data
                };
            });
        }).then(function (result) {
            refreshCsrfToken(result.data.csrf_token_name, result.data.csrf_hash);

            if (!result.ok || !result.data.success) {
                throw new Error(result.data.message || 'Unable to update lesson progress.');
            }

            if (window.toast && typeof window.toast.success === 'function') {
                window.toast.success(result.data.message || 'Lesson marked as complete.');
            }

            window.setTimeout(function () {
                window.location.reload();
            }, 350);
        }).catch(function (error) {
            completionRequested = false;
            if (window.toast && typeof window.toast.error === 'function') {
                window.toast.error(error.message || 'Unable to update lesson progress.');
            }
        });
    }

    function markVideoLessonComplete() {
        markLessonComplete('video_completed');
    }

    function updatePdfViewerStatus(message, state) {
        var pdfViewerStatus = document.getElementById('pdfViewerStatus');
        if (!pdfViewerStatus) {
            return;
        }

        pdfViewerStatus.textContent = message;
        if (state) {
            pdfViewerStatus.setAttribute('data-state', state);
        }
    }

    function ensurePdfJs(callback) {
        if (window.pdfjsLib) {
            callback();
            return;
        }

        var existingScript = document.querySelector('script[data-pdfjs-loader="1"]');
        if (existingScript) {
            existingScript.addEventListener('load', callback, { once: true });
            return;
        }

        var pdfScript = document.createElement('script');
        pdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        pdfScript.setAttribute('data-pdfjs-loader', '1');
        pdfScript.onload = callback;
        pdfScript.onerror = function () {
            updatePdfViewerStatus('Unable to load the PDF viewer.', 'error');
        };
        document.head.appendChild(pdfScript);
    }

    function initPdfViewer() {
        if (!isPdfLesson || !pdfUrl || !window.pdfjsLib) {
            return;
        }

        var pdfScrollArea = document.getElementById('pdfScrollArea');
        var pdfPages = document.getElementById('pdfPages');
        if (!pdfScrollArea || !pdfPages) {
            return;
        }

        window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        updatePdfViewerStatus('Loading PDF...', 'loading');

        function attachPdfCompletionTracking() {
            if (completionGateType !== 'pdf') {
                updatePdfViewerStatus('PDF loaded.', 'complete');
                return;
            }

            updatePdfViewerStatus('Please read the lesson carefully and make sure you fully understand the content.', 'ready');
            var lastPage = pdfPages.lastElementChild;
            if (!lastPage) {
                return;
            }

            function completePdfLesson() {
                if (completionRequested) {
                    return;
                }

                updatePdfViewerStatus('Saving lesson progress...', 'ready');
                markLessonComplete('pdf_scrolled');
            }

            function isLastPageVisible() {
                var rect = lastPage.getBoundingClientRect();
                var viewportHeight = window.innerHeight || document.documentElement.clientHeight || 0;
                var visibleTop = Math.max(rect.top, 0);
                var visibleBottom = Math.min(rect.bottom, viewportHeight);
                var visibleHeight = Math.max(0, visibleBottom - visibleTop);
                var requiredHeight = Math.min(rect.height * 0.65, 320);
                return visibleHeight >= requiredHeight;
            }

            function handleViewportCheck() {
                if (completionRequested) {
                    return;
                }

                if (isLastPageVisible()) {
                    window.removeEventListener('scroll', handleViewportCheck);
                    window.removeEventListener('resize', handleViewportCheck);
                    completePdfLesson();
                }
            }

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting && entry.intersectionRatio >= 0.65) {
                            observer.disconnect();
                            completePdfLesson();
                        }
                    });
                }, {
                    root: null,
                    threshold: [0.65]
                });
                observer.observe(lastPage);
            } else {
                window.addEventListener('scroll', handleViewportCheck, { passive: true });
                window.addEventListener('resize', handleViewportCheck, { passive: true });
            }

            handleViewportCheck();
        }

        window.pdfjsLib.getDocument({
            url: pdfUrl,
            withCredentials: true
        }).promise.then(async function (pdfDocument) {
            pdfPages.innerHTML = '';

            for (var pageNumber = 1; pageNumber <= pdfDocument.numPages; pageNumber++) {
                var page = await pdfDocument.getPage(pageNumber);
                var baseViewport = page.getViewport({ scale: 1 });
                var availableWidth = Math.max((pdfScrollArea.clientWidth || 960) - 36, 320);
                var scale = availableWidth / baseViewport.width;
                var viewport = page.getViewport({ scale: scale });
                var outputScale = window.devicePixelRatio || 1;

                var pageWrap = document.createElement('div');
                pageWrap.className = 'lp-pdf-page';

                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d', { alpha: false });
                canvas.width = Math.floor(viewport.width * outputScale);
                canvas.height = Math.floor(viewport.height * outputScale);
                canvas.style.width = Math.floor(viewport.width) + 'px';
                canvas.style.height = Math.floor(viewport.height) + 'px';
                context.setTransform(outputScale, 0, 0, outputScale, 0, 0);

                await page.render({
                    canvasContext: context,
                    viewport: viewport
                }).promise;

                var pageNumberLabel = document.createElement('div');
                pageNumberLabel.className = 'lp-pdf-page-number';
                pageNumberLabel.textContent = 'Page ' + pageNumber + ' of ' + pdfDocument.numPages;

                pageWrap.appendChild(canvas);
                pageWrap.appendChild(pageNumberLabel);
                pdfPages.appendChild(pageWrap);
            }

            attachPdfCompletionTracking();
        }).catch(function () {
            updatePdfViewerStatus('Unable to display this PDF in the lesson viewer.', 'error');
        });
    }

    if (completionGateType === 'video') {
        var html5Video = document.querySelector('.lesson-video-embed video');
        if (html5Video) {
            html5Video.addEventListener('ended', markVideoLessonComplete, { once: true });
        }

        var youtubeIframe = document.querySelector('.lesson-video-embed iframe[src*="youtube.com/embed/"]');
        if (youtubeIframe) {
            var src = youtubeIframe.getAttribute('src') || '';
            if (src.indexOf('enablejsapi=1') === -1) {
                youtubeIframe.src = src + (src.indexOf('?') === -1 ? '?' : '&') + 'enablejsapi=1&rel=0';
            }

            window.onYouTubeIframeAPIReady = function () {
                new YT.Player(youtubeIframe, {
                    events: {
                        onStateChange: function (event) {
                            if (event.data === YT.PlayerState.ENDED) {
                                markVideoLessonComplete();
                            }
                        }
                    }
                });
            };

            if (window.YT && typeof window.YT.Player === 'function') {
                window.onYouTubeIframeAPIReady();
            } else {
                var youtubeScript = document.createElement('script');
                youtubeScript.src = 'https://www.youtube.com/iframe_api';
                document.head.appendChild(youtubeScript);
            }
        }

        var vimeoIframe = document.querySelector('.lesson-video-embed iframe[src*="player.vimeo.com/video/"]');
        if (vimeoIframe) {
            var vimeoSrc = vimeoIframe.getAttribute('src') || '';
            if (vimeoSrc.indexOf('api=1') === -1) {
                vimeoIframe.src = vimeoSrc + (vimeoSrc.indexOf('?') === -1 ? '?' : '&') + 'api=1';
            }

            function initVimeoTracking() {
                if (!window.Vimeo || typeof window.Vimeo.Player !== 'function') {
                    return;
                }

                var player = new window.Vimeo.Player(vimeoIframe);
                player.on('ended', markVideoLessonComplete);
            }

            if (window.Vimeo && typeof window.Vimeo.Player === 'function') {
                initVimeoTracking();
            } else {
                var vimeoScript = document.createElement('script');
                vimeoScript.src = 'https://player.vimeo.com/api/player.js';
                vimeoScript.onload = initVimeoTracking;
                document.head.appendChild(vimeoScript);
            }
        }
    }

    if (isPdfLesson) {
        ensurePdfJs(initPdfViewer);
    }
});
</script>
<?php endif; ?>
