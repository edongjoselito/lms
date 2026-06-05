<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$lesson_notes = isset($notes) && is_array($notes) ? $notes : array();
$ln_can_create = !empty($can_create_note);
$ln_create_url = isset($create_url) && $create_url ? $create_url : site_url('course/create_lesson_note/' . (int) $lesson->id);
$ln_update_base_url = isset($update_base_url) && $update_base_url ? $update_base_url : site_url('course/update_lesson_note/' . (int) $lesson->id);
$ln_delete_base_url = isset($delete_base_url) && $delete_base_url ? $delete_base_url : site_url('course/delete_lesson_note/' . (int) $lesson->id);
$ln_route_query_suffix = isset($route_query_suffix) ? (string) $route_query_suffix : '';
?>

<div class="ln-page">
    <div class="ln-top-actions">
        <a href="<?= $back_url ?>" class="ln-back">
            <i class="bi bi-arrow-left-short"></i> <?= htmlspecialchars($back_label) ?>
        </a>
    </div>

    <div class="ln-hero">
        <div class="ln-hero-bg"></div>
        <div class="ln-hero-content">
            <div class="ln-hero-left">
                <div class="ln-hero-avatar">LN</div>
                <div class="ln-hero-info">
                    <div class="ln-hero-meta">
                        <span class="ln-tag ln-tag-code"><?= htmlspecialchars($subject->code) ?></span>
                        <span class="ln-tag ln-tag-module"><?= htmlspecialchars($module->title) ?></span>
                    </div>
                    <h1 class="ln-hero-title"><?= htmlspecialchars($lesson->title) ?></h1>
                    <p class="ln-hero-desc">Shared Lesson Notes</p>
                </div>
            </div>
            <div class="ln-hero-stats">
                <div class="ln-hero-stat">
                    <div class="ln-hero-stat-num"><?= count($lesson_notes) ?></div>
                    <div class="ln-hero-stat-lbl">Notes</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ln-layout">
        <div class="ln-card">
            <div class="ln-card-head">
                <div class="ln-card-title">
                    <i class="bi bi-journal-text"></i>
                    <span>Lesson Notes</span>
                    <span class="ln-count-pill"><?= count($lesson_notes) ?></span>
                </div>
                <div class="ln-head-actions">
                    <span class="ln-helper-note">Notes are shared with other authorized users in your school. Only the author can edit or delete a note.</span>
                    <?php if ($ln_can_create): ?>
                        <button type="button" class="ln-add-btn" data-bs-toggle="modal" data-bs-target="#lessonNoteModal">
                            <i class="bi bi-plus-lg"></i> Add Note
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($lesson_notes)): ?>
                <div class="ln-list">
                    <?php foreach ($lesson_notes as $note): ?>
                        <div class="ln-item">
                            <div class="ln-item-main">
                                <div class="ln-item-top">
                                    <div class="ln-author">
                                        <span class="ln-author-avatar"><?= strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', (string) ($note->creator_name ?: 'U')), 0, 2)) ?></span>
                                        <div class="ln-author-copy">
                                            <strong><?= htmlspecialchars($note->creator_name !== '' ? $note->creator_name : 'Unknown User') ?></strong>
                                            <span>Added <?= htmlspecialchars($note->created_at_label) ?><?= !empty($note->is_updated) ? ' • Updated ' . htmlspecialchars($note->updated_at_label) : '' ?></span>
                                        </div>
                                    </div>
                                    <?php if (!empty($note->can_manage)): ?>
                                        <div class="ln-item-actions">
                                            <button
                                                type="button"
                                                class="ln-action-btn ln-action-edit js-edit-lesson-note"
                                                data-note-id="<?= (int) $note->id ?>"
                                                data-note-text="<?= htmlspecialchars($note->note_text, ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <a href="<?= $ln_delete_base_url . '/' . (int) $note->id . $ln_route_query_suffix ?>" class="ln-action-btn ln-action-del" onclick="return confirm('Delete this lesson note?');">
                                                <i class="bi bi-trash3-fill"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ln-note-body"><?= nl2br(htmlspecialchars($note->note_text)) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="ln-empty">
                    <div class="ln-empty-icon">
                        <i class="bi bi-journal-plus"></i>
                    </div>
                    <div class="ln-empty-title">No lesson notes yet</div>
                    <div class="ln-empty-sub"><?= $ln_can_create ? 'Click "Add Note" to add the first note for this lesson.' : 'No notes have been added for this lesson yet.' ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($ln_can_create): ?>
    <div class="modal fade" id="lessonNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lessonNoteModalTitle">Add Lesson Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open($ln_create_url, ['id' => 'lessonNoteForm']) ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="note_text" id="lesson_note_text" rows="6" required placeholder="Write your lesson note here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="lessonNoteSubmitBtn">Add Note</button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
<?php if ($ln_can_create): ?>
document.querySelectorAll('.js-edit-lesson-note').forEach(function(button) {
    button.addEventListener('click', function() {
        document.getElementById('lesson_note_text').value = button.getAttribute('data-note-text') || '';
        document.getElementById('lessonNoteModalTitle').textContent = 'Edit Lesson Note';
        document.getElementById('lessonNoteSubmitBtn').textContent = 'Update Note';
        document.getElementById('lessonNoteForm').action = '<?= $ln_update_base_url ?>/' + button.getAttribute('data-note-id') + '<?= $ln_route_query_suffix ?>';
        new bootstrap.Modal(document.getElementById('lessonNoteModal')).show();
    });
});

document.getElementById('lessonNoteModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('lesson_note_text').value = '';
    document.getElementById('lessonNoteModalTitle').textContent = 'Add Lesson Note';
    document.getElementById('lessonNoteSubmitBtn').textContent = 'Add Note';
    document.getElementById('lessonNoteForm').action = '<?= $ln_create_url ?>';
});
<?php endif; ?>
</script>

<style>
.ln-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

.ln-top-actions {
    margin-bottom: 1.5rem;
}

.ln-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}
.ln-back:hover { background: #dbeafe; color: #1d4ed8; text-decoration: none; }

.ln-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}
.ln-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 54%, #3b82f6 100%);
}
.ln-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}
.ln-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}
.ln-hero-avatar {
    width: 68px;
    height: 68px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    background: rgba(255,255,255,0.92);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.2);
}
.ln-hero-meta {
    display: flex;
    gap: 0.55rem;
    flex-wrap: wrap;
    margin-bottom: 0.65rem;
}
.ln-tag {
    display: inline-flex;
    align-items: center;
    padding: 0.28rem 0.75rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    background: rgba(255,255,255,0.16);
    color: #fff;
}
.ln-hero-title {
    margin: 0;
    color: #fff;
    font-size: clamp(1.5rem, 2vw, 2.1rem);
    font-weight: 800;
}
.ln-hero-desc {
    margin: 0.45rem 0 0;
    color: rgba(255,255,255,0.78);
}
.ln-hero-stats {
    display: flex;
    gap: 0.85rem;
}
.ln-hero-stat {
    min-width: 110px;
    padding: 0.95rem 1rem;
    border-radius: 18px;
    background: rgba(255,255,255,0.12);
    color: #fff;
    text-align: center;
    backdrop-filter: blur(8px);
}
.ln-hero-stat-num {
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1;
}
.ln-hero-stat-lbl {
    margin-top: 0.35rem;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.78);
}

.ln-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    box-shadow: 0 18px 50px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}
.ln-card-head {
    padding: 1.35rem 1.5rem;
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    align-items: flex-start;
    border-bottom: 1px solid #edf2f7;
    flex-wrap: wrap;
}
.ln-card-title {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
}
.ln-count-pill {
    padding: 0.12rem 0.55rem;
    border-radius: 999px;
    background: #dbeafe;
    color: #1d4ed8;
    font-size: 0.75rem;
}
.ln-head-actions {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}
.ln-helper-note {
    color: #64748b;
    font-size: 0.85rem;
}
.ln-add-btn {
    border: 0;
    border-radius: 12px;
    background: #2563eb;
    color: #fff;
    padding: 0.72rem 1rem;
    font-weight: 700;
    box-shadow: 0 10px 24px rgba(37,99,235,0.22);
}

.ln-list {
    padding: 1.25rem;
    display: grid;
    gap: 1rem;
}
.ln-item {
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1rem 1.05rem;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}
.ln-item-top {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    align-items: flex-start;
    margin-bottom: 0.9rem;
}
.ln-author {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    min-width: 0;
}
.ln-author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #dbeafe;
    color: #1d4ed8;
    font-size: 0.9rem;
    font-weight: 800;
    flex-shrink: 0;
}
.ln-author-copy {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.ln-author-copy strong {
    color: #0f172a;
    font-size: 0.95rem;
}
.ln-author-copy span {
    color: #64748b;
    font-size: 0.8rem;
}
.ln-item-actions {
    display: flex;
    gap: 0.5rem;
}
.ln-action-btn {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: 1px solid #dbe3f0;
    background: #fff;
    color: #334155;
}
.ln-action-edit:hover {
    color: #1d4ed8;
    border-color: #bfdbfe;
}
.ln-action-del:hover {
    color: #dc2626;
    border-color: #fecaca;
}
.ln-note-body {
    color: #334155;
    font-size: 0.95rem;
    line-height: 1.75;
    white-space: normal;
    word-break: break-word;
}

.ln-empty {
    padding: 3rem 1.5rem;
    text-align: center;
}
.ln-empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 1rem;
    border-radius: 20px;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.9rem;
}
.ln-empty-title {
    font-weight: 800;
    font-size: 1.1rem;
    color: #0f172a;
}
.ln-empty-sub {
    margin-top: 0.35rem;
    color: #64748b;
    max-width: 460px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 767.98px) {
    .ln-hero-content,
    .ln-card-head,
    .ln-item-top {
        flex-direction: column;
        align-items: stretch;
    }
    .ln-head-actions {
        justify-content: flex-start;
    }
}
</style>
