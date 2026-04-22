<div class="enroll-page">
    <div class="breadcrumb">
        <a href="<?= site_url('student/subjects') ?>" class="breadcrumb-item"><i class="bi bi-arrow-left"></i> Back to Subjects</a>
    </div>

    <div class="enroll-card">
        <div class="enroll-header">
            <div class="subject-badge <?= $subject->system_type ?>"><?= strtoupper($subject->system_type) ?></div>
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

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
.enroll-page { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 2rem 1rem; }
.breadcrumb { margin-bottom: 2rem; }
.breadcrumb-item { display: inline-flex; align-items: center; gap: 0.5rem; color: #6366f1; text-decoration: none; font-weight: 500; font-size: 0.9rem; }
.breadcrumb-item:hover { color: #4f46e5; }

.enroll-card { background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); overflow: hidden; }
.enroll-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2.5rem; color: #fff; text-align: center; }
.subject-badge { display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1.5rem; background: rgba(255,255,255,0.2); }
.subject-badge.deped { background: #dbeafe; color: #1e40af; }
.subject-badge.ched { background: #fef3c7; color: #92400e; }
.subject-badge.tesda { background: #dcfce7; color: #166534; }
.enroll-title { font-size: 1.75rem; font-weight: 700; margin: 0 0 1.5rem 0; }
.subject-info { margin-top: 1rem; }
.subject-code { font-size: 0.875rem; font-weight: 600; opacity: 0.9; margin-bottom: 0.5rem; }
.subject-name { font-size: 1.5rem; font-weight: 600; margin: 0 0 0.75rem 0; line-height: 1.2; }
.subject-description { font-size: 0.95rem; opacity: 0.9; margin: 0; max-width: 400px; margin: 0 auto; }

.enroll-body { padding: 2.5rem; }
.info-box { display: flex; align-items: flex-start; gap: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem; }
.info-icon { width: 40px; height: 40px; background: #dbeafe; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #1e40af; font-size: 1.25rem; flex-shrink: 0; }
.info-content h4 { font-size: 1rem; font-weight: 600; color: #1e293b; margin: 0 0 0.25rem 0; }
.info-content p { font-size: 0.875rem; color: #64748b; margin: 0; line-height: 1.5; }

.enroll-form { margin-top: 1.5rem; }
.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.75rem; font-weight: 600; font-size: 0.9rem; color: #1e293b; }
.form-control { width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; transition: all 0.2s ease; }
.form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
.form-text { display: flex; align-items: center; margin-top: 0.5rem; font-size: 0.875rem; color: #64748b; }

.btn-enroll { width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 12px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; }
.btn-enroll:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
.btn-enroll:active { transform: translateY(0); }

.enroll-footer { background: #f8fafc; padding: 1.5rem 2.5rem; text-align: center; border-top: 1px solid #e2e8f0; }
.footer-text { font-size: 0.875rem; color: #64748b; margin: 0; }

@media (max-width: 768px) {
    .enroll-page { padding: 1rem; }
    .enroll-header { padding: 2rem 1.5rem; }
    .enroll-body { padding: 2rem 1.5rem; }
    .enroll-title { font-size: 1.5rem; }
    .subject-name { font-size: 1.25rem; }
}
</style>
