<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('courses/view/' . $course->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to <?= htmlspecialchars($course->title) ?>
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-clipboard-plus me-2" style="color:#6366f1;"></i>
                <?= $quiz ? 'Edit Assessment' : 'Create Assessment' ?>
            </h5>
            <form action="<?= $quiz ? site_url('quizzes/edit/' . $quiz->id) : site_url('quizzes/create/' . $course->id) ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="<?= $quiz ? htmlspecialchars($quiz->title) : '' ?>" required placeholder="e.g. Quiz 1: Basic Concepts">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="quiz_type" required>
                            <option value="quiz" <?= ($quiz && $quiz->quiz_type == 'quiz') ? 'selected' : '' ?>>Quiz</option>
                            <option value="exam" <?= ($quiz && $quiz->quiz_type == 'exam') ? 'selected' : '' ?>>Exam</option>
                            <option value="assignment" <?= ($quiz && $quiz->quiz_type == 'assignment') ? 'selected' : '' ?>>Assignment</option>
                            <option value="activity" <?= ($quiz && $quiz->quiz_type == 'activity') ? 'selected' : '' ?>>Activity</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description / Instructions</label>
                        <textarea class="form-control" name="description" rows="3"><?= $quiz ? htmlspecialchars($quiz->description) : '' ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Time Limit (minutes)</label>
                        <input type="number" class="form-control" name="time_limit_minutes" value="<?= $quiz ? $quiz->time_limit_minutes : '' ?>" min="1" placeholder="No limit">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Max Attempts</label>
                        <input type="number" class="form-control" name="max_attempts" value="<?= $quiz ? $quiz->max_attempts : '1' ?>" min="1" max="10">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Available From</label>
                        <input type="datetime-local" class="form-control" name="available_from" value="<?= $quiz && $quiz->available_from ? date('Y-m-d\TH:i', strtotime($quiz->available_from)) : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Available Until</label>
                        <input type="datetime-local" class="form-control" name="available_until" value="<?= $quiz && $quiz->available_until ? date('Y-m-d\TH:i', strtotime($quiz->available_until)) : '' ?>">
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="shuffle_questions" value="1" id="shuffleSwitch" <?= ($quiz && $quiz->shuffle_questions) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="shuffleSwitch">Shuffle Questions</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_results" value="1" id="resultsSwitch" <?= ($quiz && $quiz->show_results) || !$quiz ? 'checked' : '' ?>>
                                <label class="form-check-label" for="resultsSwitch">Show Results to Students</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishSwitch" <?= ($quiz && $quiz->is_published) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="publishSwitch">Published</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('courses/view/' . $course->id) ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
