<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-file-earmark-plus me-2" style="color:#6366f1;"></i>
                <?= $lesson ? 'Edit Lesson' : 'Add Lesson' ?>
                <span style="color:#94a3b8;font-weight:400;font-size:0.85rem;"> &mdash; <?= htmlspecialchars($module->title) ?></span>
            </h5>
            <form action="<?= $lesson ? site_url('lessons/edit_lesson/' . $lesson->id) : site_url('lessons/create_lesson/' . $module->id) ?>" method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Lesson Title</label>
                        <input type="text" class="form-control" name="title" value="<?= $lesson ? htmlspecialchars($lesson->title) : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Content Type</label>
                        <select class="form-select" name="content_type" id="contentType" onchange="toggleContentFields()">
                            <option value="text" <?= ($lesson && $lesson->content_type == 'text') ? 'selected' : '' ?>>Text / Rich Content</option>
                            <option value="file" <?= ($lesson && $lesson->content_type == 'file') ? 'selected' : '' ?>>File Upload</option>
                            <option value="video" <?= ($lesson && $lesson->content_type == 'video') ? 'selected' : '' ?>>Video</option>
                            <option value="link" <?= ($lesson && $lesson->content_type == 'link') ? 'selected' : '' ?>>External Link</option>
                        </select>
                    </div>
                    <div class="col-12" id="contentField">
                        <label class="form-label">Content</label>
                        <textarea class="form-control" name="content" id="lessonContent" rows="10" placeholder="Lesson content..."><?= $lesson ? htmlspecialchars($lesson->content) : '' ?></textarea>
                    </div>
                    <div class="col-12" id="fileField" style="display:none;">
                        <label class="form-label">Upload File</label>
                        <input type="file" class="form-control" name="lesson_file">
                        <?php if ($lesson && $lesson->file_path): ?>
                            <small class="text-muted">Current: <?= basename($lesson->file_path) ?></small>
                        <?php endif; ?>
                        <div style="color:#94a3b8;font-size:0.78rem;margin-top:0.25rem;">PDF, DOC, PPT, XLS, MP4, MP3, ZIP, Images (max 50MB)</div>
                    </div>
                    <div class="col-12" id="urlField" style="display:none;">
                        <label class="form-label">External URL</label>
                        <input type="url" class="form-control" name="external_url" value="<?= $lesson ? htmlspecialchars($lesson->external_url) : '' ?>" placeholder="https://">
                    </div>
                    <div class="col-12">
                        <label class="form-label">PDF / H5P Attachment (Optional)</label>
                        <input type="file" class="form-control" name="attachment_pdf" accept=".pdf,.h5p">
                        <?php if ($lesson && $lesson->attachment_path): ?>
                            <small class="text-muted">Current: <?= basename($lesson->attachment_path) ?></small>
                        <?php endif; ?>
                        <div style="color:#94a3b8;font-size:0.78rem;margin-top:0.25rem;">Upload a PDF or H5P file as supplementary material (max 50MB)</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control" name="duration_minutes" value="<?= $lesson ? $lesson->duration_minutes : '' ?>" min="1" placeholder="Optional">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishSwitch" <?= ($lesson && $lesson->is_published) || !$lesson ? 'checked' : '' ?>>
                            <label class="form-check-label" for="publishSwitch">Published</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Lesson</button>
                    <a href="<?= site_url('courses/view/' . $course->id) ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
function toggleContentFields() {
    var type = document.getElementById('contentType').value;
    document.getElementById('contentField').style.display = (type === 'text') ? '' : 'none';
    document.getElementById('fileField').style.display = (type === 'file' || type === 'video') ? '' : 'none';
    document.getElementById('urlField').style.display = (type === 'link' || type === 'video') ? '' : 'none';
}
toggleContentFields();

$(document).ready(function() {
    $('#lessonContent').summernote({
        height: 300,
        placeholder: 'Write your lesson content here...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>
