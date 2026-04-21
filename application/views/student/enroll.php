<div class="enroll-page">
    <div class="enroll-container">
        <div class="enroll-header">
            <div class="subject-icon">
                <i class="bi bi-book"></i>
            </div>
            <h1>Enroll in Course</h1>
            <p class="subject-name"><?= htmlspecialchars($subject->name) ?></p>
            <p class="subject-code"><?= htmlspecialchars($subject->code) ?></p>
        </div>
        
        <div class="enroll-form-container">
            <form method="post" class="enroll-form">
                <div class="form-group">
                    <label for="enrollment_key">Enrollment Key</label>
                    <input type="text" id="enrollment_key" name="enrollment_key" class="form-control" placeholder="Enter enrollment key" required>
                    <p class="help-text">Enter the enrollment key provided by your instructor</p>
                </div>
                
                <button type="submit" class="btn-enroll">
                    <i class="bi bi-key"></i> Enroll in Course
                </button>
            </form>
        </div>
        
        <div class="cancel-link">
            <a href="<?= site_url('student/subjects') ?>">
                <i class="bi bi-arrow-left"></i> Back to Subjects
            </a>
        </div>
    </div>
</div>

<style>
.enroll-page { padding: 2rem 0; max-width: 600px; margin: 0 auto; }
.back-link { color: #6366f1; text-decoration: none; font-weight: 500; }
.enroll-card { background: #fff; border-radius: 16px; padding: 2rem; border: 1px solid #e2e8f0; }
.enroll-card h1 { margin: 0 0 0.5rem 0; font-size: 1.5rem; }
.course-code { color: #6366f1; font-weight: 600; margin-bottom: 0.5rem; }
.course-desc { color: #64748b; margin-bottom: 2rem; }
.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
.form-control { width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
.form-text { color: #64748b; font-size: 0.875rem; }
.btn-enroll-submit { width: 100%; padding: 0.875rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: #fff; border: none; border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; }
</style>
