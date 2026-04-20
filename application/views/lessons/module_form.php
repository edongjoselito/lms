<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-folder-plus me-2" style="color:#6366f1;"></i>
                <?= $module ? 'Edit Module' : 'Add Module' ?>
            </h5>
            <form action="<?= $module ? site_url('lessons/edit_module/' . $module->id) : site_url('lessons/create_module/' . $course->id) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Module Title</label>
                    <input type="text" class="form-control" name="title" value="<?= $module ? htmlspecialchars($module->title) : '' ?>" required placeholder="e.g. Module 1: Introduction">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Brief description of this module"><?= $module ? htmlspecialchars($module->description) : '' ?></textarea>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishSwitch" <?= ($module && $module->is_published) || !$module ? 'checked' : '' ?>>
                    <label class="form-check-label" for="publishSwitch">Published</label>
                </div>
                <div class="pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Module</button>
                    <a href="<?= site_url('courses/view/' . $course->id) ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
