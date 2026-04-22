<div class="course-dashboard-page">
    <div class="course-hero">
        <div>
            <span class="page-eyebrow">Course workspace</span>
            <h1 class="page-title">Course Creator Dashboard</h1>
            <p class="page-subtitle"><?= htmlspecialchars($current_school ? $current_school->name : 'No school selected') ?></p>
        </div>
        <a href="<?= site_url('course/subjects') ?>" class="hero-action" aria-label="Manage subjects" title="Manage subjects">
            <i class="bi bi-journal-bookmark"></i>
        </a>
    </div>

    <div class="dashboard-stats">
        <div class="dashboard-stat">
            <div class="stat-icon stat-icon-blue">
                <i class="bi bi-book"></i>
            </div>
            <div>
                <div class="stat-value"><?= $total_subjects ?></div>
                <div class="stat-label">Total Subjects</div>
            </div>
        </div>
        <div class="dashboard-stat">
            <div class="stat-icon stat-icon-green">
                <i class="bi bi-list-ol"></i>
            </div>
            <div>
                <div class="stat-value"><?= $total_grade_levels ?></div>
                <div class="stat-label">Grade Levels</div>
            </div>
        </div>
        <div class="dashboard-stat">
            <div class="stat-icon stat-icon-gold">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div>
                <div class="stat-value"><?= $total_programs ?></div>
                <div class="stat-label">Programs</div>
            </div>
        </div>
    </div>

    <div class="course-dashboard-grid">
        <section class="panel-card">
            <div class="panel-header">
                <h2><i class="bi bi-lightning-charge"></i> Quick Actions</h2>
            </div>
            <div class="quick-actions">
                <a href="<?= site_url('course/subjects') ?>" class="quick-action">
                    <span><i class="bi bi-book"></i></span>
                    <div>
                        <strong>Manage Subjects & Content</strong>
                        <small>Open course builders and modules</small>
                    </div>
                </a>
                <a href="<?= site_url('academic/subjects') ?>" class="quick-action">
                    <span><i class="bi bi-plus-circle"></i></span>
                    <div>
                        <strong>Add New Subject</strong>
                        <small>Create a subject record first</small>
                    </div>
                </a>
            </div>
        </section>

        <section class="panel-card">
            <div class="panel-header">
                <h2><i class="bi bi-clock-history"></i> Subjects Overview</h2>
                <?php if (count($subjects) > 5): ?>
                    <a href="<?= site_url('course/subjects') ?>">View all</a>
                <?php endif; ?>
            </div>

            <?php if (!empty($subjects)): ?>
                <div class="subject-list">
                    <?php foreach (array_slice($subjects, 0, 5) as $s): ?>
                        <div class="subject-row">
                            <div>
                                <span class="subject-code"><?= htmlspecialchars($s->code) ?></span>
                                <h3><?= htmlspecialchars($s->description) ?></h3>
                            </div>
                            <a href="<?= site_url('course/content/' . $s->id) ?>" class="icon-action" title="Manage Content" aria-label="Manage Content">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-folder2-open"></i>
                    <h3>No subjects found</h3>
                    <p>Add a subject before building modules and lessons.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<style>
.course-dashboard-page {
    padding: 0.5rem 0 1.5rem;
}

.course-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.25rem 0 0.5rem;
    margin-bottom: 1.5rem;
}

.page-eyebrow {
    display: inline-flex;
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
    margin-bottom: 1rem;
}

.dashboard-stat,
.panel-card {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
}

.dashboard-stat {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
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

.stat-value {
    font-size: 2rem;
    line-height: 1;
    color: #182033;
    font-weight: 700;
}

.stat-label {
    margin-top: 0.3rem;
    color: #667085;
    font-size: 0.875rem;
}

.course-dashboard-grid {
    display: grid;
    grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
    gap: 1rem;
}

.panel-card {
    overflow: hidden;
}

.panel-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #edf0f4;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.panel-header h2 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    color: #182033;
    font-weight: 700;
    margin: 0;
}

.panel-header h2 i,
.panel-header a {
    color: #2f6fed;
}

.panel-header a {
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 700;
}

.quick-actions,
.subject-list {
    padding: 1rem;
}

.quick-action,
.subject-row {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.9rem;
    border-radius: 8px;
    border: 1px solid #edf0f4;
    background: #f8fafc;
    text-decoration: none;
    color: inherit;
}

.quick-action + .quick-action,
.subject-row + .subject-row {
    margin-top: 0.75rem;
}

.quick-action span {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    background: #edf4ff;
    color: #2f6fed;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.quick-action strong,
.subject-row h3 {
    color: #182033;
    font-weight: 700;
}

.quick-action small {
    display: block;
    margin-top: 0.15rem;
    color: #667085;
}

.quick-action:hover,
.subject-row:hover {
    background: #fff;
    border-color: #d7e6ff;
}

.subject-row {
    justify-content: space-between;
}

.subject-code {
    display: inline-flex;
    margin-bottom: 0.35rem;
    color: #2f6fed;
    font-size: 0.75rem;
    font-weight: 700;
}

.subject-row h3 {
    font-size: 0.95rem;
    line-height: 1.35;
    margin: 0;
}

.icon-action {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    border: 1px solid #d7e6ff;
    background: #edf4ff;
    color: #2f6fed;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-decoration: none;
}

.icon-action:hover {
    color: #fff;
    background: #2f6fed;
    border-color: #2f6fed;
}

.empty-state {
    padding: 2rem 1rem;
    text-align: center;
    color: #667085;
}

.empty-state i {
    font-size: 2.25rem;
    color: #98a2b3;
    display: block;
    margin-bottom: 0.75rem;
}

.empty-state h3 {
    color: #182033;
    font-size: 1.05rem;
    font-weight: 700;
    margin: 0 0 0.35rem 0;
}

.empty-state p {
    margin: 0;
}

@media (max-width: 992px) {
    .course-dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .course-hero {
        align-items: flex-start;
    }
}
</style>
