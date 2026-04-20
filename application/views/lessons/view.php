<div class="mb-3">
    <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
    </a>
</div>

<div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
    <div class="p-4" style="border-bottom:1px solid #e2e8f0;background:#f8fafc;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h4 style="font-weight:700;margin:0 0 0.25rem 0;"><?= htmlspecialchars($lesson->title) ?></h4>
                <div style="color:#94a3b8;font-size:0.85rem;">
                    <?= htmlspecialchars($lesson->module_title) ?> &middot; <?= ucfirst($lesson->content_type) ?>
                    <?= $lesson->duration_minutes ? ' &middot; ' . $lesson->duration_minutes . ' min' : '' ?>
                </div>
            </div>
            <?php
            $icons = array('text' => 'bi-file-text-fill', 'file' => 'bi-paperclip', 'video' => 'bi-play-circle-fill', 'link' => 'bi-link-45deg');
            $icon = isset($icons[$lesson->content_type]) ? $icons[$lesson->content_type] : 'bi-file-text-fill';
            ?>
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-size:1.25rem;">
                <i class="bi <?= $icon ?>"></i>
            </div>
        </div>
    </div>

    <div class="p-4">
        <?php if ($lesson->content_type === 'text'): ?>
            <div class="lesson-content" style="line-height:1.8;color:#334155;">
                <?= $lesson->content ?>
            </div>

        <?php elseif ($lesson->content_type === 'video'): ?>
            <?php if ($lesson->external_url): ?>
                <?php if (strpos($lesson->external_url, 'youtube.com') !== false || strpos($lesson->external_url, 'youtu.be') !== false): ?>
                    <?php
                    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $lesson->external_url, $m);
                    $vid = isset($m[1]) ? $m[1] : '';
                    ?>
                    <div class="ratio ratio-16x9 mb-3" style="border-radius:12px;overflow:hidden;">
                        <iframe src="https://www.youtube.com/embed/<?= $vid ?>" allowfullscreen></iframe>
                    </div>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($lesson->external_url) ?>" target="_blank" class="btn-primary-custom"><i class="bi bi-play-circle me-1"></i> Watch Video</a>
                <?php endif; ?>
            <?php elseif ($lesson->file_path): ?>
                <video controls style="width:100%;border-radius:12px;">
                    <source src="<?= base_url($lesson->file_path) ?>">
                </video>
            <?php endif; ?>
            <?php if ($lesson->content): ?>
                <div class="mt-3" style="line-height:1.8;color:#334155;"><?= $lesson->content ?></div>
            <?php endif; ?>

        <?php elseif ($lesson->content_type === 'file'): ?>
            <?php if ($lesson->file_path): ?>
                <?php $ext = pathinfo($lesson->file_path, PATHINFO_EXTENSION); ?>
                <?php if (in_array(strtolower($ext), array('jpg','jpeg','png','gif','webp'))): ?>
                    <img src="<?= base_url($lesson->file_path) ?>" class="img-fluid" style="border-radius:12px;max-height:600px;">
                <?php elseif (strtolower($ext) === 'pdf'): ?>
                    <div class="ratio" style="--bs-aspect-ratio:130%;border-radius:12px;overflow:hidden;">
                        <iframe src="<?= base_url($lesson->file_path) ?>"></iframe>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url($lesson->file_path) ?>" class="btn-primary-custom" download><i class="bi bi-download me-1"></i> Download File (<?= strtoupper($ext) ?>)</a>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($lesson->content): ?>
                <div class="mt-3" style="line-height:1.8;color:#334155;"><?= $lesson->content ?></div>
            <?php endif; ?>

        <?php elseif ($lesson->content_type === 'link'): ?>
            <a href="<?= htmlspecialchars($lesson->external_url) ?>" target="_blank" class="btn-primary-custom mb-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> Open Link
            </a>
            <?php if ($lesson->content): ?>
                <div class="mt-3" style="line-height:1.8;color:#334155;"><?= $lesson->content ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- PDF / H5P Attachment (supplementary material) -->
        <?php if ($lesson->attachment_path):
            $ext = strtolower(pathinfo($lesson->attachment_path, PATHINFO_EXTENSION));
            $is_h5p = ($ext === 'h5p');
        ?>
        <div class="mt-4 pt-4" style="border-top:1px solid #e2e8f0;">
            <h6 style="font-weight:700;color:#0f172a;margin-bottom:0.75rem;">
                <i class="bi <?= $is_h5p ? 'bi-puzzle-fill' : 'bi-file-earmark-pdf-fill' ?> me-2" style="color:<?= $is_h5p ? '#f59e0b' : '#ef4444' ?>;"></i><?= $is_h5p ? 'H5P' : 'PDF' ?> Attachment
            </h6>
            <?php if (!$is_h5p): ?>
            <div class="ratio" style="--bs-aspect-ratio:130%;border-radius:12px;overflow:hidden;background:#f8fafc;">
                <iframe src="<?= base_url($lesson->attachment_path) ?>"></iframe>
            </div>
            <?php else: ?>
            <div class="p-3" style="background:#f8fafc;border-radius:12px;">
                <p style="color:#64748b;font-size:0.85rem;margin:0;">H5P interactive content. Download to view in an H5P-compatible player.</p>
            </div>
            <?php endif; ?>
            <a href="<?= base_url($lesson->attachment_path) ?>" target="_blank" class="btn btn-light btn-sm mt-2" style="border-radius:10px;font-size:0.8rem;">
                <i class="bi bi-download me-1"></i> Download <?= $is_h5p ? 'H5P' : 'PDF' ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Navigation -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="<?= site_url('courses/view/' . $course->id) ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">
        <i class="bi bi-arrow-left me-1"></i> Back to Course
    </a>
    <?php if ($next_lesson_id): ?>
    <a href="<?= site_url('lessons/view/' . $next_lesson_id) ?>" class="btn-primary-custom">
        Next Lesson <i class="bi bi-arrow-right ms-1"></i>
    </a>
    <?php endif; ?>
</div>
