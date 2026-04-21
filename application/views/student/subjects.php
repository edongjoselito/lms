<div class="subjects-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Subjects</h1>
            <p class="page-subtitle">Browse courses with content</p>
        </div>
        <div class="header-actions">
            <div class="filter-tabs">
                <a href="<?= site_url('student/subjects') ?>" class="filter-tab <?= empty($filter_type) ? 'active' : '' ?>">All</a>
                <a href="<?= site_url('student/subjects?system_type=deped') ?>" class="filter-tab <?= ($filter_type == 'deped') ? 'active' : '' ?>">DepEd</a>
                <a href="<?= site_url('student/subjects?system_type=ched') ?>" class="filter-tab <?= ($filter_type == 'ched') ? 'active' : '' ?>">CHED</a>
            </div>
        </div>
    </div>
    <?php if (!empty($subjects)): ?>
        <?php foreach ($subjects as $program_code => $program): ?>
            <div class="program-section">
                <h2 class="program-title">
                    <i class="bi bi-mortarboard"></i>
                    <?= $program['program_name'] ?: $program_code ?>
                </h2>
                <div class="subjects-grid">
                    <?php foreach ($program['subjects'] as $s): ?>
                        <?php $is_enrolled = in_array($s->id, array_column($enrolled_subjects, 'id')); ?>
                        <div class="subject-card">
                            <div class="subject-card-header">
                                <div class="subject-badge <?= $s->system_type ?>"><?= strtoupper($s->system_type) ?></div>
                                <?php if ($is_enrolled): ?>
                                    <span class="enrolled-badge"><i class="bi bi-check2"></i> Enrolled</span>
                                <?php endif; ?>
                            </div>
                            <div class="subject-card-body">
                                <div class="subject-code"><?= $s->code ?></div>
                                <h3 class="subject-name"><?= htmlspecialchars($s->name) ?></h3>
                                <div class="subject-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-mortarboard"></i>
                                        <span><?= $s->units ?: '-' ?> Units</span>
                                    </div>
                                </div>
                                <a href="<?= site_url('student/content/' . $s->id) ?>" class="btn-action">View Course</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-book"></i>
            </div>
            <h3>No subjects with content found</h3>
            <p>No courses with published content available yet</p>
        </div>
    <?php endif; ?>
</div>

<style>
.subjects-page { padding: 1.5rem 0; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
.page-title { font-size: 1.75rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0; }
.page-subtitle { color: #64748b; margin: 0; font-size: 0.95rem; }
.header-actions { display: flex; align-items: center; gap: 1rem; }
.filter-tabs { display: flex; gap: 0.5rem; background: #f1f5f9; padding: 0.25rem; border-radius: 10px; }
.filter-tab { padding: 0.5rem 1.25rem; border-radius: 8px; text-decoration: none; color: #64748b; font-size: 0.875rem; font-weight: 500; }
.filter-tab.active { background: #fff; color: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.program-section { margin-bottom: 3rem; }
.program-title { font-size: 1.5rem; font-weight: 600; color: #1e293b; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem; }
.subjects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
.subject-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
.subject-card-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.subject-badge { padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.7rem; font-weight: 700; }
.subject-badge.deped { background: #dbeafe; color: #1e40af; }
.subject-badge.ched { background: #fef3c7; color: #92400e; }
.enrolled-badge { background: #dcfce7; color: #166534; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.7rem; font-weight: 600; }
.subject-card-body { padding: 1.25rem; }
.subject-code { color: #6366f1; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem; }
.subject-name { font-size: 1.125rem; font-weight: 600; color: #1e293b; margin: 0 0 1rem 0; }
.subject-meta { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem; }
.meta-item { display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-size: 0.85rem; }
.btn-action { width: 100%; padding: 0.75rem; border-radius: 10px; text-decoration: none; font-weight: 500; text-align: center; display: block; background: #6366f1; color: #fff; }
.empty-state { text-align: center; padding: 4rem 2rem; background: #f8fafc; border-radius: 16px; border: 2px dashed #e2e8f0; }
.empty-icon { width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; }
.empty-icon i { font-size: 2rem; color: #64748b; }
.empty-state h3 { font-size: 1.25rem; color: #1e293b; margin: 0 0 0.5rem 0; }
.empty-state p { color: #64748b; margin: 0; }
</style>
