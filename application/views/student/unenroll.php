<div class="unenroll-page">
    <div class="unenroll-container">
        <div class="unenroll-header">
            <div class="warning-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h1>Unenroll from Course</h1>
            <p class="subject-name"><?= htmlspecialchars($subject->name) ?></p>
            <p class="subject-code"><?= htmlspecialchars($subject->code) ?></p>
        </div>
        
        <div class="unenroll-warning">
            <p>Are you sure you want to unenroll from this course?</p>
            <p class="warning-text">This action will:</p>
            <ul>
                <li>Remove your access to all course content</li>
                <li>Delete your lesson progress and completions</li>
                <li>Require you to re-enroll to access the course again</li>
            </ul>
        </div>
        
        <div class="unenroll-actions">
            <form method="post" class="unenroll-form">
                <button type="submit" class="btn-unenroll">
                    <i class="bi bi-x-circle"></i> Yes, Unenroll
                </button>
            </form>
            <a href="<?= site_url('student/content/' . $subject->id) ?>" class="btn-cancel">
                <i class="bi bi-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
</div>

<style>
.unenroll-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.unenroll-container { background: #fff; border-radius: 24px; padding: 3rem; max-width: 480px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
.unenroll-header { text-align: center; margin-bottom: 2rem; }
.warning-icon { width: 80px; height: 80px; background: #fef3c7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #d97706; font-size: 2.5rem; margin-bottom: 1.5rem; }
.unenroll-header h1 { font-size: 1.75rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0; }
.subject-name { font-size: 1.125rem; font-weight: 600; color: #1e293b; margin: 0 0 0.25rem 0; }
.subject-code { font-size: 0.875rem; color: #64748b; margin: 0; }

.unenroll-warning { background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; }
.unenroll-warning > p:first-child { font-size: 1rem; font-weight: 600; color: #991b1b; margin: 0 0 1rem 0; }
.warning-text { font-size: 0.875rem; font-weight: 600; color: #991b1b; margin: 0 0 0.5rem 0; }
.unenroll-warning ul { margin: 0; padding-left: 1.5rem; color: #7f1d1d; font-size: 0.875rem; }
.unenroll-warning li { margin-bottom: 0.5rem; }

.unenroll-actions { display: flex; flex-direction: column; gap: 1rem; }
.btn-unenroll { width: 100%; padding: 1rem 2rem; background: #dc2626; color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease; }
.btn-unenroll:hover { background: #b91c1c; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(220, 38, 38, 0.4); }
.btn-cancel { width: 100%; padding: 1rem 2rem; background: #f1f5f9; color: #475569; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease; }
.btn-cancel:hover { background: #e2e8f0; }
</style>
