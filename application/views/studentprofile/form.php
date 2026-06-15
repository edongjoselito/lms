<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('studentprofile') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Student Profiles
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-badge me-2" style="color:#6366f1;"></i>
                <?= isset($profile) ? 'Edit Student Profile' : 'Add Student Profile' ?>
            </h5>
            <?= form_open(isset($profile) ? 'studentprofile/edit/' . $profile->id : 'studentprofile/create', array('id' => 'studentprofile-form')) ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student Number / LRN <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" id="student-number-input" name="student_number" value="<?= isset($profile) ? htmlspecialchars($profile->student_number) : '' ?>" required autocomplete="off">
                        <div class="form-text checker-feedback" data-field="student_number"></div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="<?= isset($profile) ? htmlspecialchars($profile->first_name) : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="<?= isset($profile) ? htmlspecialchars($profile->middle_name) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="<?= isset($profile) ? htmlspecialchars($profile->last_name) : '' ?>" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Birth Date <span style="color:red;">*</span></label>
                        <input type="date" class="form-control" name="birth_date" value="<?= isset($profile) ? htmlspecialchars($profile->birth_date) : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sex</label>
                        <select class="form-select" name="gender">
                            <option value="">Select Sex</option>
                            <option value="Male" <?= isset($profile) && isset($profile->gender) && $profile->gender === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= isset($profile) && isset($profile->gender) && $profile->gender === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="profile-email-input" name="email" value="<?= isset($profile) && isset($profile->email) ? htmlspecialchars($profile->email) : '' ?>" autocomplete="off">
                        <div class="form-text checker-feedback" data-field="email"></div>
                    </div>
                </div>
                <?php if (!isset($profile)): ?>
                <div class="mt-3 p-3" style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;">
                    <p style="margin:0;font-size:0.875rem;color:#0369a1;">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Note:</strong> A user account will be automatically created with:
                        <br>• Login Username: Student Number
                        <br>• Password: Birth Date (YYYY-MM-DD format)
                        <br>• Role: Student
                    </p>
                </div>
                <?php endif; ?>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom" id="studentprofile-submit-btn"><i class="bi bi-check-lg"></i> Save</button>
                    <a href="<?= site_url('studentprofile') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('studentprofile-form');
    if (!form) {
        return;
    }

    var studentNumberInput = document.getElementById('student-number-input');
    var emailInput = document.getElementById('profile-email-input');
    var submitButton = document.getElementById('studentprofile-submit-btn');
    var checkUrl = <?= json_encode(site_url('studentprofile/check_duplicate')) ?>;
    var excludeProfileId = <?= isset($profile) ? (int) $profile->id : 0 ?>;
    var excludeUserId = <?= isset($profile) && isset($profile->user_id) ? (int) $profile->user_id : 0 ?>;
    var timers = {};
    var requestToken = {
        student_number: 0,
        email: 0
    };
    var fieldState = {
        student_number: { exists: false },
        email: { exists: false }
    };

    function getFeedback(field) {
        return form.querySelector('.checker-feedback[data-field="' + field + '"]');
    }

    function setSubmitState() {
        submitButton.disabled = fieldState.student_number.exists || fieldState.email.exists;
    }

    function clearFieldClasses(input) {
        input.classList.remove('is-valid');
        input.classList.remove('is-invalid');
    }

    function setFieldMessage(field, type, message) {
        var input = field === 'student_number' ? studentNumberInput : emailInput;
        var feedback = getFeedback(field);
        if (!input || !feedback) {
            return;
        }

        clearFieldClasses(input);
        feedback.classList.remove('text-danger');
        feedback.classList.remove('text-success');
        feedback.classList.remove('text-muted');
        feedback.textContent = '';

        if (!message) {
            setSubmitState();
            return;
        }

        if (type === 'error') {
            input.classList.add('is-invalid');
            feedback.classList.add('text-danger');
        } else if (type === 'success') {
            input.classList.add('is-valid');
            feedback.classList.add('text-success');
        } else {
            feedback.classList.add('text-muted');
        }

        feedback.textContent = message;
        setSubmitState();
    }

    function buildQuery() {
        return [
            'student_number=' + encodeURIComponent(studentNumberInput ? studentNumberInput.value.trim() : ''),
            'email=' + encodeURIComponent(emailInput ? emailInput.value.trim() : ''),
            'exclude_profile_id=' + encodeURIComponent(excludeProfileId),
            'exclude_user_id=' + encodeURIComponent(excludeUserId)
        ].join('&');
    }

    function runCheck(field) {
        var value = field === 'student_number'
            ? (studentNumberInput ? studentNumberInput.value.trim() : '')
            : (emailInput ? emailInput.value.trim() : '');

        if (value === '') {
            fieldState[field].exists = false;
            setFieldMessage(field, '', '');
            return;
        }

        if (field === 'email' && emailInput && !emailInput.checkValidity()) {
            fieldState[field].exists = false;
            setFieldMessage(field, '', '');
            return;
        }

        setFieldMessage(field, 'info', 'Checking...');

        requestToken[field] += 1;
        var currentToken = requestToken[field];
        var xhr = new XMLHttpRequest();
        xhr.open('GET', checkUrl + '?' + buildQuery(), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) {
                return;
            }

            if (currentToken !== requestToken[field]) {
                return;
            }

            if (xhr.status < 200 || xhr.status >= 300) {
                fieldState[field].exists = false;
                setFieldMessage(field, '', '');
                return;
            }

            var response = null;
            try {
                response = JSON.parse(xhr.responseText);
            } catch (error) {
                fieldState[field].exists = false;
                setFieldMessage(field, '', '');
                return;
            }

            var result = response && response[field] ? response[field] : { exists: false, message: '' };
            fieldState[field].exists = !!result.exists;

            if (result.exists) {
                setFieldMessage(field, 'error', result.message || 'Already exists.');
                return;
            }

            setFieldMessage(field, 'success', field === 'student_number'
                ? 'Student Number / LRN is available.'
                : 'Email is available.');
        };
        xhr.send();
    }

    function queueCheck(field) {
        window.clearTimeout(timers[field]);
        timers[field] = window.setTimeout(function () {
            runCheck(field);
        }, 300);
    }

    if (studentNumberInput) {
        studentNumberInput.addEventListener('input', function () {
            queueCheck('student_number');
        });
        studentNumberInput.addEventListener('blur', function () {
            runCheck('student_number');
        });
    }

    if (emailInput) {
        emailInput.addEventListener('input', function () {
            queueCheck('email');
        });
        emailInput.addEventListener('blur', function () {
            runCheck('email');
        });
    }

    form.addEventListener('submit', function (event) {
        if (!fieldState.student_number.exists && !fieldState.email.exists) {
            return;
        }

        event.preventDefault();

        if (studentNumberInput && fieldState.student_number.exists) {
            studentNumberInput.focus();
            return;
        }

        if (emailInput && fieldState.email.exists) {
            emailInput.focus();
        }
    });

    if (studentNumberInput && studentNumberInput.value.trim() !== '') {
        runCheck('student_number');
    }

    if (emailInput && emailInput.value.trim() !== '') {
        runCheck('email');
    }
});
</script>
