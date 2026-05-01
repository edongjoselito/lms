<div class="student-dashboard">
    <div class="page-header student-hero">
        <div>
            <span class="page-eyebrow">Learning overview</span>
            <h1 class="page-title">Welcome, <?= htmlspecialchars($this->session->userdata('first_name') ?: 'Student') ?></h1>
            <p class="page-subtitle">Track your learning progress and access your courses</p>
        </div>
        <a href="<?= site_url('student/subjects') ?>" class="hero-action" aria-label="Browse subjects" title="Browse subjects">
            <i class="bi bi-compass"></i>
        </a>
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
            <div class="stat-icon stat-icon-gold">
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
                    <?php $system_type = strtolower($subject->system_type ?: 'general'); ?>
                    <div class="course-card">
                        <div class="course-cover <?= empty($subject->cover_photo) ? 'course-cover-fallback' : '' ?>">
                            <?php if (!empty($subject->cover_photo)): ?>
                                <img src="<?= base_url('uploads/covers/' . $subject->cover_photo) ?>" alt="<?= htmlspecialchars($subject->name) ?>">
                            <?php else: ?>
                                <span><?= htmlspecialchars($subject->code ?: 'Course') ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="course-body">
                            <div class="course-code"><?= htmlspecialchars($subject->code) ?></div>
                            <h3 class="course-name"><?= htmlspecialchars($subject->name) ?></h3>
                            <div class="course-meta">
                                <span class="course-badge <?= htmlspecialchars($system_type) ?>"><?= htmlspecialchars(strtoupper($system_type)) ?></span>
                                <?php if (!empty($subject->section_name)): ?>
                                    <span class="course-section">
                                        <i class="bi bi-people-fill"></i> <?= htmlspecialchars($subject->section_name) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <a href="<?= site_url('student/content/' . $subject->id) ?>" class="btn-continue">
                                Open Course <i class="bi bi-arrow-right"></i>
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
                    <div class="course-cover <?= empty($subject->cover_photo) ? 'course-cover-fallback' : '' ?>">
                        <?php if (!empty($subject->cover_photo)): ?>
                            <img src="<?= base_url('uploads/covers/' . $subject->cover_photo) ?>" alt="<?= htmlspecialchars($subject->name) ?>">
                        <?php else: ?>
                            <span><?= htmlspecialchars($subject->code ?: 'Course') ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="course-body">
                        <div class="course-code"><?= htmlspecialchars($subject->code) ?></div>
                        <h3 class="course-name"><?= htmlspecialchars($subject->name) ?></h3>
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
    padding: 0.5rem 0 1.5rem;
}

.page-header {
    margin-bottom: 1.5rem;
}

.student-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.25rem 0 0.5rem;
}

.page-eyebrow {
    display: inline-flex;
    align-items: center;
    margin-bottom: 0.45rem;
    color: #2f6fed;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0;
}

.page-title {
    font-size: 1.7rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 0.35rem 0;
}

.page-subtitle {
    color: #667085;
    margin: 0;
    font-size: 0.95rem;
}

.hero-action {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: #edf4ff;
    color: #2f6fed;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: 1px solid #d7e6ff;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.hero-action i {
    font-size: 1.15rem;
}

.hero-action:hover {
    background: #2f6fed;
    color: #fff;
    border-color: #2f6fed;
    transform: translateY(-1px);
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border-radius: 8px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid #e4e7ec;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-icon-blue {
    background: #edf4ff;
    color: #2f6fed;
}

.stat-icon-green {
    background: #e9f8f0;
    color: #0f8b5f;
}

.stat-icon-gold {
    background: #fff6df;
    color: #b7791f;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #182033;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #667085;
    font-size: 0.875rem;
}

.dashboard-section {
    margin-bottom: 2.25rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #182033;
    margin: 0 0 1rem 0;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.course-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e4e7ec;
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.course-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(24, 32, 51, 0.08);
}

.course-cover {
    height: 150px;
    overflow: hidden;
    background: #edf4ff;
}

.course-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-cover-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2f6fed;
    background: #eef6f2;
}

.course-cover-fallback span {
    max-width: 78%;
    padding: 0.6rem 0.8rem;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(47, 111, 237, 0.14);
    color: #182033;
    font-size: 0.95rem;
    font-weight: 700;
    text-align: center;
    overflow-wrap: anywhere;
}

.course-body {
    padding: 1.25rem;
}

.course-code {
    color: #2f6fed;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0;
    margin-bottom: 0.5rem;
}

.course-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #182033;
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
    padding: 0.28rem 0.65rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 700;
}

.course-badge.deped {
    background: #edf4ff;
    color: #2f6fed;
}

.course-badge.ched {
    background: #fff6df;
    color: #9a6700;
}

.course-badge.tesda {
    background: #e9f8f0;
    color: #0f8b5f;
}

.course-badge.general {
    background: #eef2f7;
    color: #475467;
}

.course-section {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    color: #667085;
    font-size: 0.85rem;
}

.btn-continue,
.btn-enroll {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}

.btn-continue {
    background: #2f6fed;
    color: #fff;
    border: 1px solid #2f6fed;
}

.btn-continue:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(47, 111, 237, 0.2);
    color: #fff;
}

.btn-enroll {
    background: #f7f9fc;
    color: #182033;
    border: 1px solid #e4e7ec;
}

.btn-enroll:hover {
    background: #edf4ff;
    color: #2f6fed;
    border-color: #d7e6ff;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: #fff;
    border-radius: 8px;
    border: 1px dashed #cfd6e3;
}

.empty-icon {
    width: 72px;
    height: 72px;
    background: #edf4ff;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.empty-icon i {
    font-size: 2rem;
    color: #2f6fed;
}

.empty-state h3 {
    font-size: 1.25rem;
    color: #182033;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    color: #667085;
    margin: 0 0 1.5rem 0;
}

.btn-primary-modern {
    background: #2f6fed;
    color: #fff;
    padding: 0.625rem 1.25rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
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
    box-shadow: 0 10px 20px rgba(47, 111, 237, 0.2);
    color: #fff;
}

@media (max-width: 768px) {
    .student-hero {
        align-items: flex-start;
        padding: 0;
    }

    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .courses-grid {
        grid-template-columns: 1fr;
    }
}
</style>
