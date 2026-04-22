<?php $subject_system_type = strtolower($subject->system_type ?: 'general'); ?>
<div class="enroll-page">
    <div class="enroll-breadcrumb">
        <a href="<?= site_url('student/subjects') ?>" class="breadcrumb-item"><i class="bi bi-arrow-left"></i> Back to Subjects</a>
    </div>

    <div class="enroll-card">
        <div class="enroll-header">
            <div class="subject-badge <?= htmlspecialchars($subject_system_type) ?>"><?= htmlspecialchars(strtoupper($subject_system_type)) ?></div>
            <h1 class="enroll-title">Enroll in Course</h1>
            <div class="subject-info">
                <div class="subject-code"><?= htmlspecialchars($subject->code) ?></div>
                <h2 class="subject-name"><?= htmlspecialchars($subject->name) ?></h2>
                <?php if (!empty($subject->description)): ?>
                    <p class="subject-description"><?= htmlspecialchars($subject->description) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="enroll-body">
            <div class="info-box">
                <div class="info-icon">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div class="info-content">
                    <h4>Enrollment Key Required</h4>
                    <p>Please enter the enrollment key provided by your instructor to access this course.</p>
                </div>
            </div>

            <form method="post" class="enroll-form">
                <div class="form-group">
                    <label for="enrollment_key">
                        <i class="bi bi-key me-2"></i>Enrollment Key
                    </label>
                    <input type="text" id="enrollment_key" name="enrollment_key" class="form-control" placeholder="Enter enrollment key" required autocomplete="off">
                    <div class="form-text">
                        <i class="bi bi-shield-lock me-1"></i>Enter the unique enrollment key for this course
                    </div>
                </div>

                <button type="submit" class="btn-enroll">
                    <i class="bi bi-check-circle me-2"></i>Enroll Now
                </button>
            </form>
        </div>

        <div class="enroll-footer">
            <p class="footer-text">Need help? Contact your instructor for the enrollment key.</p>
        </div>
    </div>
</div>

<style>
.enroll-page {
    max-width: 720px;
    margin: 0 auto;
    padding: 0.5rem 0 1.5rem;
}

.enroll-breadcrumb {
    margin-bottom: 1rem;
}

.breadcrumb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #2f6fed;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
}

.breadcrumb-item:hover {
    color: #1f5ecf;
}

.enroll-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e4e7ec;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
    overflow: hidden;
}

.enroll-header {
    padding: 1.75rem;
    color: #182033;
    text-align: left;
    border-bottom: 1px solid #edf0f4;
}

.subject-badge {
    display: inline-flex;
    padding: 0.32rem 0.7rem;
    border-radius: 10px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 1rem;
    letter-spacing: 0;
}

.subject-badge.deped {
    background: #edf4ff;
    color: #2f6fed;
}

.subject-badge.ched {
    background: #fff6df;
    color: #9a6700;
}

.subject-badge.tesda {
    background: #e9f8f0;
    color: #0f8b5f;
}

.subject-badge.general {
    background: #eef2f7;
    color: #475467;
}

.enroll-title {
    font-size: 1.65rem;
    font-weight: 700;
    margin: 0 0 1.25rem 0;
}

.subject-info {
    margin-top: 0.25rem;
}

.subject-code {
    font-size: 0.82rem;
    font-weight: 700;
    color: #2f6fed;
    margin-bottom: 0.45rem;
}

.subject-name {
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0 0 0.7rem 0;
    line-height: 1.25;
}

.subject-description {
    font-size: 0.95rem;
    color: #667085;
    margin: 0;
    max-width: 520px;
}

.enroll-body {
    padding: 1.75rem;
}

.info-box {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    background: #f8fafc;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    padding: 1.1rem;
    margin-bottom: 1.5rem;
}

.info-icon {
    width: 42px;
    height: 42px;
    background: #edf4ff;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2f6fed;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.info-content h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.25rem 0;
}

.info-content p {
    font-size: 0.875rem;
    color: #667085;
    margin: 0;
    line-height: 1.5;
}

.enroll-form {
    margin-top: 1.25rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.6rem;
    font-weight: 700;
    font-size: 0.9rem;
    color: #182033;
}

.form-control {
    width: 100%;
    padding: 0.9rem 1rem;
    border: 1px solid #e4e7ec;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    outline: none;
    border-color: #2f6fed;
    box-shadow: 0 0 0 3px rgba(47, 111, 237, 0.12);
}

.form-text {
    display: flex;
    align-items: center;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #667085;
}

.btn-enroll {
    width: 100%;
    min-height: 46px;
    padding: 0.85rem 1rem;
    background: #2f6fed;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-enroll:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(47, 111, 237, 0.2);
}

.btn-enroll:active {
    transform: translateY(0);
}

.enroll-footer {
    background: #f8fafc;
    padding: 1.25rem 1.75rem;
    border-top: 1px solid #edf0f4;
}

.footer-text {
    font-size: 0.875rem;
    color: #667085;
    margin: 0;
}

@media (max-width: 768px) {
    .enroll-header,
    .enroll-body,
    .enroll-footer {
        padding: 1.25rem;
    }

    .enroll-title {
        font-size: 1.45rem;
    }

    .subject-name {
        font-size: 1.2rem;
    }
}
</style>
