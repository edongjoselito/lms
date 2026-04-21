<div class="subjects-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Subjects</h1>
            <p class="page-subtitle">Manage and organize your academic subjects</p>
        </div>
        <div class="header-actions">
            <div class="filter-tabs">
                <a href="<?= site_url('academic/subjects') ?>" class="filter-tab <?= empty($filter_type) ? 'active' : '' ?>">All</a>
                <a href="<?= site_url('academic/subjects?system_type=deped') ?>" class="filter-tab <?= ($filter_type == 'deped') ? 'active' : '' ?>">DepEd</a>
                <a href="<?= site_url('academic/subjects?system_type=ched') ?>" class="filter-tab <?= ($filter_type == 'ched') ? 'active' : '' ?>">CHED</a>
            </div>
            <a href="<?= site_url('academic/create_subject') ?>" class="btn-primary-modern">
                <i class="bi bi-plus-lg"></i> Add Subject
            </a>
        </div>
    </div>

    <?php if (!empty($subjects)): ?>
        <div class="subjects-grid">
            <?php foreach ($subjects as $s): ?>
                <div class="subject-card">
                    <div class="subject-card-header">
                        <div class="subject-badge <?= $s->system_type ?>">
                            <?= strtoupper($s->system_type) ?>
                        </div>
                        <div class="subject-actions">
                            <a href="<?= site_url('academic/edit_subject/' . $s->id) ?>" class="action-btn" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </div>
                    </div>
                    <div class="subject-card-body">
                        <div class="subject-code"><?= $s->code ?></div>
                        <h3 class="subject-name"><?= htmlspecialchars($s->name) ?></h3>
                        <div class="subject-meta">
                            <div class="meta-item">
                                <i class="bi bi-mortarboard"></i>
                                <span>
                                    <?php if ($s->system_type == 'deped'): ?>
                                        <?= isset($s->grade_level_name) ? $s->grade_level_name : '-' ?>
                                    <?php else: ?>
                                        <?= isset($s->program_code) ? $s->program_code : '-' ?><?= $s->year_level ? ' / Year ' . $s->year_level : '' ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span><?= $s->units ?: '-' ?> Units</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-book"></i>
            </div>
            <h3>No subjects found</h3>
            <p>Get started by adding your first subject</p>
            <a href="<?= site_url('academic/create_subject') ?>" class="btn-primary-modern">
                <i class="bi bi-plus-lg"></i> Add Subject
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.subjects-page {
    padding: 1.5rem 0 !important;
}

.page-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    margin-bottom: 2rem !important;
    flex-wrap: wrap !important;
    gap: 1.5rem !important;
}

.page-title {
    font-size: 1.75rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    margin: 0 0 0.5rem 0 !important;
}

.page-subtitle {
    color: #64748b !important;
    margin: 0 !important;
    font-size: 0.95rem !important;
}

.header-actions {
    display: flex !important;
    align-items: center !important;
    gap: 1rem !important;
    flex-wrap: wrap !important;
}

.filter-tabs {
    display: flex !important;
    gap: 0.5rem !important;
    background: #f1f5f9 !important;
    padding: 0.25rem !important;
    border-radius: 10px !important;
}

.filter-tab {
    padding: 0.5rem 1.25rem !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    color: #64748b !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    transition: all 0.2s !important;
}

.filter-tab:hover {
    color: #334155 !important;
}

.filter-tab.active {
    background: #fff !important;
    color: #1e293b !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%) !important;
    color: #fff !important;
    padding: 0.625rem 1.25rem !important;
    border-radius: 10px !important;
    text-decoration: none !important;
    font-weight: 500 !important;
    font-size: 0.875rem !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    transition: all 0.2s !important;
    border: none !important;
    cursor: pointer !important;
}

.btn-primary-modern:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4) !important;
}

.subjects-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)) !important;
    gap: 1.5rem !important;
}

.subject-card {
    background: #fff !important;
    border-radius: 16px !important;
    border: 1px solid #e2e8f0 !important;
    overflow: hidden !important;
    transition: all 0.3s !important;
}

.subject-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    border-color: #cbd5e1 !important;
}

.subject-card-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 1rem 1.25rem !important;
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
}

.subject-badge {
    padding: 0.375rem 0.75rem !important;
    border-radius: 8px !important;
    font-size: 0.7rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.5px !important;
}

.subject-badge.deped {
    background: #dbeafe !important;
    color: #1e40af !important;
}

.subject-badge.ched {
    background: #fef3c7 !important;
    color: #92400e !important;
}

.subject-actions {
    display: flex !important;
    gap: 0.5rem !important;
}

.action-btn {
    width: 32px !important;
    height: 32px !important;
    border-radius: 8px !important;
    background: #fff !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-decoration: none !important;
    transition: all 0.2s !important;
}

.action-btn:hover {
    background: #6366f1 !important;
    color: #fff !important;
    border-color: #6366f1 !important;
}

.subject-card-body {
    padding: 1.25rem !important;
}

.subject-code {
    color: #6366f1 !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 0.5rem !important;
}

.subject-name {
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    color: #1e293b !important;
    margin: 0 0 1rem 0 !important;
    line-height: 1.4 !important;
}

.subject-meta {
    display: flex !important;
    flex-direction: column !important;
    gap: 0.5rem !important;
}

.meta-item {
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    color: #64748b !important;
    font-size: 0.85rem !important;
}

.meta-item i {
    color: #94a3b8 !important;
}

.empty-state {
    text-align: center !important;
    padding: 4rem 2rem !important;
    background: #f8fafc !important;
    border-radius: 16px !important;
    border: 2px dashed #e2e8f0 !important;
}

.empty-icon {
    width: 80px !important;
    height: 80px !important;
    background: #e2e8f0 !important;
    border-radius: 50% !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 1.5rem !important;
}

.empty-icon i {
    font-size: 2rem !important;
    color: #64748b !important;
}

.empty-state h3 {
    font-size: 1.25rem !important;
    color: #1e293b !important;
    margin: 0 0 0.5rem 0 !important;
}

.empty-state p {
    color: #64748b !important;
    margin: 0 0 1.5rem 0 !important;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column !important;
    }
    
    .header-actions {
        width: 100% !important;
        justify-content: space-between !important;
    }
    
    .subjects-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
