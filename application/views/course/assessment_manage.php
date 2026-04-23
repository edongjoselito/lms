<?php
if (!function_exists('assessment_datetime_value')) {
    function assessment_datetime_value($value)
    {
        if (empty($value)) return '';
        $time = strtotime($value);
        return $time ? date('Y-m-d\TH:i', $time) : '';
    }
}
?>

<div class="assessment-page">
    <div class="mb-3">
        <a href="<?= site_url('course/content/' . $subject->id . '?edit=1#module-' . $module->id) ?>" class="assessment-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Course Content
        </a>
    </div>

    <div class="data-table mb-4">
        <div class="table-header d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <div class="text-muted small mb-1"><?= htmlspecialchars($subject->code) ?> &middot; <?= htmlspecialchars($module->title) ?></div>
                <h5 class="mb-2"><?= htmlspecialchars($quiz->title) ?></h5>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border"><?= htmlspecialchars(ucfirst($quiz->quiz_type)) ?></span>
                    <span class="badge bg-light text-dark border"><?= count($questions) ?> Questions</span>
                    <span class="badge bg-light text-dark border"><?= number_format((float) $quiz->total_points, 2) ?> Points</span>
                    <span class="badge bg-<?= $quiz->is_published ? 'success' : 'secondary' ?>"><?= $quiz->is_published ? 'Published' : 'Hidden' ?></span>
                </div>
            </div>
        </div>

        <div class="p-4">
            <form action="<?= site_url('course/edit_assessment/' . $quiz->id) ?>" method="post" class="assessment-panel mb-4">
                <h6 class="mb-3"><i class="bi bi-sliders me-2"></i>Assessment Settings</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($quiz->title, ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="quiz_type">
                            <option value="quiz" <?= $quiz->quiz_type === 'quiz' ? 'selected' : '' ?>>Quiz</option>
                            <option value="exam" <?= $quiz->quiz_type === 'exam' ? 'selected' : '' ?>>Exam</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Max Attempts</label>
                        <input type="number" class="form-control" name="max_attempts" min="1" value="<?= (int) $quiz->max_attempts ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Time Limit</label>
                        <input type="number" class="form-control" name="time_limit_minutes" min="0" value="<?= (int) $quiz->time_limit_minutes ?>" placeholder="Minutes">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Available From</label>
                        <input type="datetime-local" class="form-control" name="available_from" value="<?= assessment_datetime_value($quiz->available_from) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Available Until</label>
                        <input type="datetime-local" class="form-control" name="available_until" value="<?= assessment_datetime_value($quiz->available_until) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description / Instructions</label>
                        <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($quiz->description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="assessmentPublished" <?= $quiz->is_published ? 'checked' : '' ?>>
                                <label class="form-check-label" for="assessmentPublished">Published</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shuffle_questions" value="1" id="assessmentShuffle" <?= $quiz->shuffle_questions ? 'checked' : '' ?>>
                                <label class="form-check-label" for="assessmentShuffle">Shuffle questions</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="show_results" value="1" id="assessmentShowResults" <?= $quiz->show_results ? 'checked' : '' ?>>
                                <label class="form-check-label" for="assessmentShowResults">Show results</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save Settings</button>
                    </div>
                </div>
            </form>

            <form action="<?= site_url('course/upload_assessment_questions/' . $quiz->id) ?>" method="post" enctype="multipart/form-data" class="assessment-panel mb-4">
                <h6 class="mb-3"><i class="bi bi-upload me-2"></i>Import Question Bank</h6>
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Format</label>
                        <select class="form-select" name="import_format">
                            <option value="gift">GIFT</option>
                            <option value="xml">Moodle XML</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Question File</label>
                        <input type="file" class="form-control" name="question_file" accept=".gift,.txt,.xml,text/plain,text/xml,application/xml" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-cloud-arrow-up me-1"></i>Import</button>
                    </div>
                </div>
                <div class="form-text mt-2">Supported: multiple choice, true/false, identification, essay.</div>
            </form>

            <div class="assessment-panel mb-4">
                <h6 class="mb-3"><i class="bi bi-list-check me-2"></i>Questions</h6>
                <?php if (empty($questions)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
                        No questions imported yet.
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($questions as $idx => $question): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <div class="small text-muted mb-1">
                                            #<?= $idx + 1 ?> &middot; <?= htmlspecialchars(str_replace('_', ' ', ucfirst($question->question_type))) ?> &middot; <?= number_format((float) $question->points, 2) ?> pts
                                        </div>
                                        <div class="fw-semibold"><?= htmlspecialchars($question->question_text) ?></div>
                                        <?php if (!empty($question->choices)): ?>
                                            <ul class="mt-2 mb-0 small">
                                                <?php foreach ($question->choices as $choice): ?>
                                                    <li>
                                                        <?= $choice->is_correct ? '<strong>[Correct]</strong> ' : '' ?>
                                                        <?= htmlspecialchars($choice->choice_text) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= site_url('course/delete_assessment_question/' . $question->id) ?>" class="btn btn-sm btn-outline-danger align-self-start" onclick="return confirm('Delete this question?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="assessment-panel">
                <h6 class="mb-3"><i class="bi bi-people me-2"></i>Attempts</h6>
                <?php if (empty($attempts)): ?>
                    <p class="text-muted mb-0">No student attempts yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Attempt</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Submitted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attempts as $attempt): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($attempt->student_name ?? $attempt->email ?? 'Student') ?></td>
                                        <td><?= (int) $attempt->attempt_number ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($attempt->status) ?></span></td>
                                        <td><?= $attempt->score === null ? '-' : number_format((float) $attempt->score, 2) . ' / ' . number_format((float) $attempt->total_points, 2) ?></td>
                                        <td><?= $attempt->submitted_at ? htmlspecialchars($attempt->submitted_at) : '-' ?></td>
                                        <td><a href="<?= site_url('course/assessment_result/' . $attempt->id) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.assessment-page .assessment-back {
    color: #2f6fed;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
}
.assessment-panel {
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    background: #fff;
    padding: 1rem;
}
.assessment-panel h6 {
    font-weight: 700;
    color: #182033;
}
</style>
