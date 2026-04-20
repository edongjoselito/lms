<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-3">
            <a href="<?= site_url('courses') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Courses
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-mortarboard-fill me-2" style="color:#6366f1;"></i>
                <?= $course ? 'Edit Course' : 'Create Course' ?>
            </h5>
            <form action="<?= $course ? site_url('courses/edit/' . $course->id) : site_url('courses/create') ?>" method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Course Title</label>
                        <input type="text" class="form-control" name="title" value="<?= $course ? htmlspecialchars($course->title) : '' ?>" required placeholder="e.g. English 101: Basic Grammar">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Course Code</label>
                        <input type="text" class="form-control" name="code" value="<?= $course ? htmlspecialchars($course->code) : '' ?>" placeholder="e.g. ENG101">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="What will students learn in this course?"><?= $course ? htmlspecialchars($course->description) : '' ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" value="<?= $course ? htmlspecialchars($course->category) : '' ?>" placeholder="e.g. English, Science, Math">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cover Image</label>
                        <input type="file" class="form-control" name="cover_image" accept="image/*">
                        <?php if ($course && $course->cover_image): ?>
                            <small class="text-muted">Current: <?= basename($course->cover_image) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishSwitch" <?= ($course && $course->is_published) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="publishSwitch">Publish this course (visible to enrolled students)</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Course</button>
                    <a href="<?= site_url('courses') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
