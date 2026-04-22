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
.unenroll-page {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 0 1.5rem;
}

.unenroll-container {
    background: #fff;
    border-radius: 8px;
    padding: 1.75rem;
    max-width: 520px;
    width: 100%;
    border: 1px solid #e4e7ec;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.unenroll-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.warning-icon {
    width: 68px;
    height: 68px;
    background: #fff6df;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #b7791f;
    font-size: 2rem;
    margin-bottom: 1.2rem;
}

.unenroll-header h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.5rem 0;
}

.subject-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.25rem 0;
}

.subject-code {
    font-size: 0.875rem;
    color: #667085;
    margin: 0;
}

.unenroll-warning {
    background: #fff5f5;
    border: 1px solid #ffd6d1;
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.unenroll-warning > p:first-child {
    font-size: 1rem;
    font-weight: 700;
    color: #a83225;
    margin: 0 0 1rem 0;
}

.warning-text {
    font-size: 0.875rem;
    font-weight: 700;
    color: #a83225;
    margin: 0 0 0.5rem 0;
}

.unenroll-warning ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #8b2b22;
    font-size: 0.875rem;
}

.unenroll-warning li {
    margin-bottom: 0.45rem;
}

.unenroll-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-unenroll,
.btn-cancel {
    width: 100%;
    min-height: 46px;
    padding: 0.85rem 1rem;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-unenroll {
    background: #c24132;
    color: #fff;
    border: 1px solid #c24132;
}

.btn-unenroll:hover {
    background: #a83225;
    border-color: #a83225;
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(194, 65, 50, 0.18);
}

.btn-cancel {
    background: #f7f9fc;
    color: #182033;
    border: 1px solid #e4e7ec;
}

.btn-cancel:hover {
    background: #edf4ff;
    color: #2f6fed;
    border-color: #d7e6ff;
}

@media (max-width: 768px) {
    .unenroll-container {
        padding: 1.25rem;
    }
}
</style>
