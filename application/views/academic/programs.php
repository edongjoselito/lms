<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="programs-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Academic Programs</h1>
            <p class="page-subtitle">Manage programs, grade levels, and their subjects</p>
        </div>
        <a href="<?= site_url('academic/create_program') ?>" class="btn-add-program">
            <i class="bi bi-plus-lg"></i> Add Program
        </a>
    </div>

    <?php if (!empty($programs)): ?>
        <div class="programs-grid">
            <?php foreach ($programs as $p): ?>
                <?php
                $type = isset($p->type) ? $p->type : 'program';
                $subject_count = ($type === 'program') ? count($this->Academic_model->get_subjects_by_program($p->id)) : 0;
                $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', trim($p->name)), 0, 2)));
                ?>
                <div class="program-card type-<?= $type ?>">
                    <div class="card-accent"></div>

                    <div class="card-top">
                        <div class="card-avatar">
                            <?= htmlspecialchars($initials) ?>
                        </div>
                        <div class="card-meta-badges">
                            <span class="type-chip type-<?= $type ?>">
                                <?= $type == 'grade_level' ? 'Grade Level' : 'Program' ?>
                            </span>
                            <?php if ($type == 'program' && !empty($p->degree_type)): ?>
                                <span class="degree-chip"><?= ucfirst($p->degree_type) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <a href="<?= site_url('academic/edit_program/' . $p->id) ?>" class="btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= site_url('academic/delete_program/' . $p->id) ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Delete this program?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="program-code"><?= htmlspecialchars($p->code) ?></div>
                        <h3 class="program-name"><?= htmlspecialchars($p->name) ?></h3>
                        <p class="program-description"><?= htmlspecialchars($p->description) ?: '<span class="no-desc">No description provided.</span>' ?></p>
                    </div>

                    <div class="card-stats">
                        <?php if ($type == 'program'): ?>
                            <div class="stat-pill">
                                <span class="stat-icon"><i class="bi bi-clock-history"></i></span>
                                <span><?= $p->years_to_complete ?> yrs</span>
                            </div>
                            <div class="stat-pill">
                                <span class="stat-icon"><i class="bi bi-calculator"></i></span>
                                <span><?= $p->total_units ?> units</span>
                            </div>
                            <div class="stat-pill subjects-pill">
                                <span class="stat-icon"><i class="bi bi-journal-bookmark"></i></span>
                                <span><?= $subject_count ?> subjects</span>
                            </div>
                        <?php else: ?>
                            <div class="stat-pill">
                                <span class="stat-icon"><i class="bi bi-layers"></i></span>
                                <span><?= ucfirst($p->category) ?></span>
                            </div>
                            <div class="stat-pill">
                                <span class="stat-icon"><i class="bi bi-sort-numeric-down"></i></span>
                                <span>Level <?= $p->level_order ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($type == 'program'): ?>
                    <div class="card-footer">
                        <a href="<?= site_url('academic/program_subjects/' . $p->id) ?>" class="btn-manage-subjects">
                            <i class="bi bi-book-half"></i>
                            Manage Subjects
                            <span class="subject-count-badge"><?= $subject_count ?></span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-mortarboard"></i>
            </div>
            <h3>No programs yet</h3>
            <p>Create your first academic program or grade level to get started.</p>
            <a href="<?= site_url('academic/create_program') ?>" class="btn-add-program">
                <i class="bi bi-plus-lg"></i> Add Program
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.programs-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 2rem 1rem;
}

/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2.5rem;
    flex-wrap: wrap;
}
.page-title {
    font-size: 1.85rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.02em;
}
.page-subtitle {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0.3rem 0 0;
}
.btn-add-program {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
}
.btn-add-program:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.45);
    color: #fff;
    text-decoration: none;
}

/* Grid */
.programs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

/* Card */
.program-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8edf5;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.22s ease;
    position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.program-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    border-color: #d1d5db;
}

/* Accent bar */
.card-accent {
    height: 5px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
}
.program-card.type-grade_level .card-accent {
    background: linear-gradient(90deg, #10b981, #059669);
}

/* Card top row */
.card-top {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.25rem 1.25rem 0;
}

/* Avatar */
.card-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff;
    font-size: 1rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 0.5px;
}
.program-card.type-grade_level .card-avatar {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* Badges */
.card-meta-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    flex: 1;
}
.type-chip {
    padding: 0.25rem 0.65rem;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.type-chip.type-program { background: #ede9fe; color: #6d28d9; }
.type-chip.type-grade_level { background: #d1fae5; color: #047857; }
.degree-chip {
    padding: 0.25rem 0.65rem;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 600;
    background: #f0f9ff;
    color: #0369a1;
    text-transform: capitalize;
}

/* Actions */
.card-actions {
    display: flex;
    gap: 0.4rem;
    flex-shrink: 0;
}
.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
}
.btn-icon:hover { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
.btn-icon.btn-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }

/* Card body */
.card-body {
    padding: 1rem 1.25rem 0.75rem;
    flex: 1;
}
.program-code {
    font-size: 0.72rem;
    font-weight: 700;
    color: #6366f1;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.25rem;
}
.program-card.type-grade_level .program-code { color: #059669; }
.program-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 0.45rem;
    line-height: 1.3;
}
.program-description {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0;
    line-height: 1.55;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.no-desc { font-style: italic; color: #94a3b8; }

/* Stats row */
.card-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-top: 1px solid #f1f5f9;
}
.stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.7rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    color: #475569;
}
.stat-icon { font-size: 0.82rem; color: #94a3b8; }
.subjects-pill { border-color: #e0e7ff; background: #f5f3ff; color: #5b21b6; }
.subjects-pill .stat-icon { color: #7c3aed; }

/* Footer */
.card-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid #f1f5f9;
}
.btn-manage-subjects {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.65rem 1rem;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
    position: relative;
}
.btn-manage-subjects:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
    color: #fff;
    text-decoration: none;
}
.subject-count-badge {
    position: absolute;
    right: 0.9rem;
    background: rgba(255,255,255,0.25);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.55rem;
    border-radius: 20px;
    min-width: 22px;
    text-align: center;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    background: #f8fafc;
    border-radius: 20px;
    border: 2px dashed #e2e8f0;
}
.empty-icon {
    width: 90px;
    height: 90px;
    background: #ede9fe;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}
.empty-icon i { font-size: 2.5rem; color: #7c3aed; }
.empty-state h3 { font-size: 1.4rem; color: #1e293b; margin: 0 0 0.5rem; font-weight: 700; }
.empty-state p { margin: 0 0 1.75rem; color: #64748b; }

@media (max-width: 768px) {
    .programs-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: stretch; }
}
</style>
