<div class="student-dashboard">
    <div class="page-header">
        <div>
            <h1 class="page-title">Welcome, <?= htmlspecialchars($this->session->userdata('first_name') ?: 'Student') ?></h1>
            <p class="page-subtitle">Track your learning progress and access your courses</p>
        </div>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= count($enrolled_subjects) ?></div>
                <div class="stat-label">Enrolled Courses</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $this->Lesson_model->get_total_completed_lessons($this->session->userdata('student_id')) ?></div>
                <div class="stat-label">Completed Lessons</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= count($available_subjects) ?></div>
                <div class="stat-label">Available Courses</div>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2 class="section-title">My Courses</h2>
        <?php if (!empty($enrolled_subjects)): ?>
            <div class="courses-grid">
                <?php foreach ($enrolled_subjects as $subject): ?>
                    <div class="course-card">
                        <?php if (!empty($subject->cover_photo)): ?>
                            <div class="course-cover">
                                <img src="<?= base_url('uploads/covers/' . $subject->cover_photo) ?>" alt="<?= htmlspecialchars($subject->name) ?>">
                            </div>
                        <?php endif; ?>
                        <div class="course-body">
                            <div class="course-code"><?= $subject->code ?></div>
                            <h3 class="course-name"><?= htmlspecialchars($subject->name) ?></h3>
                            <div class="course-meta">
                                <span class="course-badge <?= $subject->system_type ?>"><?= strtoupper($subject->system_type) ?></span>
                                <?php if (!empty($subject->section_name)): ?>
                                    <span class="course-section">
                                        <i class="bi bi-people-fill"></i> <?= htmlspecialchars($subject->section_name) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <a href="<?= site_url('student/content/' . $subject->id) ?>" class="btn-continue">
                                Continue Learning <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-book"></i>
                </div>
                <h3>No courses enrolled yet</h3>
                <p>Browse available courses and enroll to start learning</p>
                <a href="<?= site_url('student/subjects') ?>" class="btn-primary-modern">
                    <i class="bi bi-book-fill"></i> Browse Courses
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($available_subjects)): ?>
    <div class="dashboard-section">
        <h2 class="section-title">Available Courses</h2>
        <div class="courses-grid">
            <?php foreach ($available_subjects as $subject): ?>
                <div class="course-card course-card-available">
                    <?php if (!empty($subject->cover_photo)): ?>
                        <div class="course-cover">
                            <img src="<?= base_url('uploads/covers/' . $subject->cover_photo) ?>" alt="<?= htmlspecialchars($subject->name) ?>">
                        </div>
                    <?php endif; ?>
                    <div class="course-body">
                        <div class="course-code"><?= $subject->code ?></div>
                        <h3 class="course-name"><?= htmlspecialchars($subject->name) ?></h3>
                        <div class="course-meta">
                            <span class="course-badge <?= $subject->system_type ?>"><?= strtoupper($subject->system_type) ?></span>
                        </div>
                        <?php if ($subject->requires_key): ?>
                            <a href="<?= site_url('student/enroll/' . $subject->id) ?>" class="btn-enroll">
                                <i class="bi bi-key-fill"></i> Enroll with Key
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url('student/enroll/' . $subject->id) ?>" class="btn-enroll">
                                <i class="bi bi-plus-lg"></i> Enroll
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.student-dashboard {
    padding: 1.5rem 0;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
}

.page-subtitle {
    color: #64748b;
    margin: 0;
    font-size: 0.95rem;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-icon-blue {
    background: #dbeafe;
    color: #1e40af;
}

.stat-icon-green {
    background: #dcfce7;
    color: #166534;
}

.stat-icon-purple {
    background: #ede9fe;
    color: #6d28d9;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.875rem;
}

.dashboard-section {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 1.5rem 0;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.course-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: all 0.3s;
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.course-cover {
    height: 160px;
    overflow: hidden;
}

.course-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-body {
    padding: 1.25rem;
}

.course-code {
    color: #6366f1;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.course-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 1rem 0;
    line-height: 1.4;
}

.course-meta {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.course-badge {
    padding: 0.25rem 0.625rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
}

.course-badge.deped {
    background: #dbeafe;
    color: #1e40af;
}

.course-badge.ched {
    background: #fef3c7;
    color: #92400e;
}

.course-section {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    color: #64748b;
    font-size: 0.85rem;
}

.btn-continue, .btn-enroll {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-continue {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff;
}

.btn-continue:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

.btn-enroll {
    background: #f1f5f9;
    color: #1e293b;
}

.btn-enroll:hover {
    background: #e2e8f0;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8fafc;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #e2e8f0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.empty-icon i {
    font-size: 2rem;
    color: #64748b;
}

.empty-state h3 {
    font-size: 1.25rem;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    color: #64748b;
    margin: 0 0 1.5rem 0;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff;
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

@media (max-width: 768px) {
    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .courses-grid {
        grid-template-columns: 1fr;
    }
}
</style>
