<?php $subject_system_type = strtolower($subject->system_type ?: 'general'); ?>

<div class="lp-wrap">

    <!-- Breadcrumb -->
    <div class="lp-breadcrumb">
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
    <div class="lp-header-card">
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
            <div class="lp-completed-pill">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                    <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Completed
            </div>
        </div>
    </div>

    <!-- Lesson Body -->
    <div class="lp-body">

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
                <div class="lp-iframe-wrap">
                    <iframe src="<?= htmlspecialchars($lesson->file_path) ?>" width="100%" height="640" loading="lazy"></iframe>
                </div>
            </div>
        <?php endif; ?>

        <div class="lp-content-body">
            <?= $lesson->content ?>
        </div>

    </div>

    <!-- Navigation -->
    <div class="lp-nav">
        <?php if ($previous_lesson): ?>
            <a href="<?= site_url('student/lesson/' . $subject->id . '/' . $previous_lesson->id) ?>" class="lp-nav-btn lp-nav-btn--prev">
                <span class="lp-nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <span class="lp-nav-text">
                    <span class="lp-nav-label">Previous Lesson</span>
                    <span class="lp-nav-name"><?= htmlspecialchars($previous_lesson->title) ?></span>
                </span>
            </a>
        <?php else: ?>
            <div></div>
        <?php endif; ?>

        <?php if ($next_lesson): ?>
            <a href="<?= site_url('student/lesson/' . $subject->id . '/' . $next_lesson->id) ?>" class="lp-nav-btn lp-nav-btn--next">
                <span class="lp-nav-text lp-nav-text--right">
                    <span class="lp-nav-label">Next Lesson</span>
                    <span class="lp-nav-name"><?= htmlspecialchars($next_lesson->title) ?></span>
                </span>
                <span class="lp-nav-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
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
        max-width: 960px;
        margin: 0 auto;
        padding: 1.5rem 1.5rem 4rem;
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
        color: #1d1d1f;
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

    /* ── Body (file + content) ─────────────────────────── */
    .lp-body {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
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
        }

        .lp-content-body {
            padding: 1.5rem;
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