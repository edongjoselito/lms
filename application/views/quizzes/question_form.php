<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-3">
            <a href="<?= site_url('quizzes/questions/' . $quiz->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Questions
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-question-circle me-2" style="color:#6366f1;"></i>
                <?= $question ? 'Edit Question' : 'Add Question' ?>
            </h5>
            <form action="<?= $question ? site_url('quizzes/edit_question/' . $question->id) : site_url('quizzes/add_question/' . $quiz->id) ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Question Type</label>
                        <select class="form-select" name="question_type" id="questionType" onchange="toggleChoiceFields()" required>
                            <option value="multiple_choice" <?= ($question && $question->question_type == 'multiple_choice') ? 'selected' : '' ?>>Multiple Choice</option>
                            <option value="true_false" <?= ($question && $question->question_type == 'true_false') ? 'selected' : '' ?>>True / False</option>
                            <option value="identification" <?= ($question && $question->question_type == 'identification') ? 'selected' : '' ?>>Identification</option>
                            <option value="essay" <?= ($question && $question->question_type == 'essay') ? 'selected' : '' ?>>Essay</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Points</label>
                        <input type="number" class="form-control" name="points" value="<?= $question ? $question->points : '1' ?>" min="0.5" step="0.5" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Question Text</label>
                        <textarea class="form-control" name="question_text" rows="3" required><?= $question ? htmlspecialchars($question->question_text) : '' ?></textarea>
                    </div>
                </div>

                <!-- Multiple Choice / True-False Choices -->
                <div id="choicesSection" class="mt-4">
                    <label class="form-label" style="font-weight:600;">Answer Choices</label>
                    <div id="choicesContainer">
                        <?php
                        $existing_choices = isset($choices) ? $choices : array();
                        if ($question && $question->question_type == 'true_false' && empty($existing_choices)) {
                            $existing_choices = array(
                                (object)array('choice_text' => 'True', 'is_correct' => 1),
                                (object)array('choice_text' => 'False', 'is_correct' => 0),
                            );
                        }
                        $correct_idx = 0;
                        foreach ($existing_choices as $ci => $ch) {
                            if ($ch->is_correct) $correct_idx = $ci;
                        }
                        if (empty($existing_choices)) {
                            for ($x = 0; $x < 4; $x++) $existing_choices[] = (object)array('choice_text' => '', 'is_correct' => 0);
                        }
                        ?>
                        <?php foreach ($existing_choices as $ci => $ch): ?>
                        <div class="d-flex align-items-center gap-2 mb-2 choice-row">
                            <input type="radio" name="correct_choice" value="<?= $ci ?>" <?= ($ci == $correct_idx) ? 'checked' : '' ?> class="form-check-input" style="flex-shrink:0;">
                            <input type="text" class="form-control" name="choice_text[]" value="<?= htmlspecialchars($ch->choice_text) ?>" placeholder="Choice <?= $ci + 1 ?>">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.choice-row').remove()" style="border-radius:8px;flex-shrink:0;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addChoice()" style="border-radius:8px;">
                        <i class="bi bi-plus me-1"></i> Add Choice
                    </button>
                </div>

                <!-- Identification Answer -->
                <div id="identificationSection" class="mt-4" style="display:none;">
                    <label class="form-label" style="font-weight:600;">Correct Answer(s)</label>
                    <p style="font-size:0.78rem;color:#94a3b8;">Enter acceptable answers. All will be treated as correct.</p>
                    <div id="identAnswers">
                        <?php if ($question && $question->question_type == 'identification' && !empty($existing_choices)): ?>
                            <?php foreach ($existing_choices as $ch): ?>
                            <div class="mb-2"><input type="text" class="form-control" name="choice_text[]" value="<?= htmlspecialchars($ch->choice_text) ?>"></div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="mb-2"><input type="text" class="form-control" name="choice_text[]" placeholder="Correct answer"></div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="document.getElementById('identAnswers').insertAdjacentHTML('beforeend','<div class=\'mb-2\'><input type=\'text\' class=\'form-control\' name=\'choice_text[]\' placeholder=\'Alternative answer\'></div>')" style="border-radius:8px;">
                        <i class="bi bi-plus me-1"></i> Add Alternative Answer
                    </button>
                </div>

                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save Question</button>
                    <a href="<?= site_url('quizzes/questions/' . $quiz->id) ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var choiceIdx = <?= count($existing_choices) ?>;
function addChoice() {
    var html = '<div class="d-flex align-items-center gap-2 mb-2 choice-row">' +
        '<input type="radio" name="correct_choice" value="' + choiceIdx + '" class="form-check-input" style="flex-shrink:0;">' +
        '<input type="text" class="form-control" name="choice_text[]" placeholder="Choice ' + (choiceIdx+1) + '">' +
        '<button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest(\'.choice-row\').remove()" style="border-radius:8px;flex-shrink:0;"><i class="bi bi-x"></i></button>' +
        '</div>';
    document.getElementById('choicesContainer').insertAdjacentHTML('beforeend', html);
    choiceIdx++;
}

function toggleChoiceFields() {
    var type = document.getElementById('questionType').value;
    document.getElementById('choicesSection').style.display = (type === 'multiple_choice' || type === 'true_false') ? '' : 'none';
    document.getElementById('identificationSection').style.display = (type === 'identification') ? '' : 'none';

    if (type === 'true_false') {
        document.getElementById('choicesContainer').innerHTML =
            '<div class="d-flex align-items-center gap-2 mb-2 choice-row"><input type="radio" name="correct_choice" value="0" checked class="form-check-input" style="flex-shrink:0;"><input type="text" class="form-control" name="choice_text[]" value="True" readonly></div>' +
            '<div class="d-flex align-items-center gap-2 mb-2 choice-row"><input type="radio" name="correct_choice" value="1" class="form-check-input" style="flex-shrink:0;"><input type="text" class="form-control" name="choice_text[]" value="False" readonly></div>';
    }
}
toggleChoiceFields();
</script>
