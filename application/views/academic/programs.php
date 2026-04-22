<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="programs-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Programs</h1>
            <p class="page-subtitle">Manage academic programs and grade levels</p>
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
                $program_subjects = isset($p->type) ? array() : $this->Academic_model->get_subjects_by_program($p->id);
                ?>
                <div class="program-card">
                    <div class="program-header">
                        <div class="program-badge type-<?= $type ?>"><?= $p->code ?></div>
                        <div class="program-type-badge type-<?= $type ?>">
                            <?= $type == 'grade_level' ? 'Grade Level' : 'Program' ?>
                        </div>
                        <div class="program-actions">
                            <a href="<?= site_url('academic/edit_program/' . $p->id) ?>" class="btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= site_url('academic/delete_program/' . $p->id) ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Delete this program?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                    <div class="program-body">
                        <h3 class="program-name"><?= htmlspecialchars($p->name) ?></h3>
                        <p class="program-description"><?= htmlspecialchars($p->description) ?></p>
                    </div>
                    <div class="program-footer">
                        <div class="program-stats">
                            <?php if ($type == 'program'): ?>
                                <span class="stat-item">
                                    <i class="bi bi-mortarboard"></i> <?= ucfirst($p->degree_type) ?>
                                </span>
                                <span class="stat-item">
                                    <i class="bi bi-clock"></i> <?= $p->years_to_complete ?> years
                                </span>
                                <span class="stat-item">
                                    <i class="bi bi-calculator"></i> <?= $p->total_units ?> units
                                </span>
                            <?php else: ?>
                                <span class="stat-item">
                                    <i class="bi bi-layers"></i> <?= ucfirst($p->category) ?>
                                </span>
                                <span class="stat-item">
                                    <i class="bi bi-sort-numeric-down"></i> Level <?= $p->level_order ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-mortarboard"></i>
            </div>
            <h3>No programs found</h3>
            <p>Create your first academic program or grade level to get started.</p>
            <a href="<?= site_url('academic/create_program') ?>" class="btn-add-program">
                <i class="bi bi-plus-lg"></i> Add Program
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.programs-page { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 2rem 1rem; }
.page-header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
.page-title { font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0; }
.page-subtitle { font-size: 1rem; color: #64748b; margin: 0.5rem 0 0 0; }
.btn-add-program { padding: 0.875rem 1.75rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease; }
.btn-add-program:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }

.programs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; }
.program-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; transition: all 0.2s ease; }
.program-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-2px); }
.program-header { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; gap: 0.5rem; }
.program-badge { padding: 0.5rem 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 8px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.5px; }
.program-badge.type-grade_level { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.program-type-badge { padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.program-type-badge.type-program { background: #ede9fe; color: #6d28d9; }
.program-type-badge.type-grade_level { background: #d1fae5; color: #047857; }
.program-actions { display: flex; gap: 0.5rem; }
.btn-icon { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; }
.btn-icon:hover { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
.btn-icon.btn-delete { color: #ef4444; }
.btn-icon.btn-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }

.program-body { padding: 1.5rem; }
.program-name { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0; }
.program-description { font-size: 0.9rem; color: #64748b; margin: 0; line-height: 1.5; }

.program-footer { padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; }
.program-stats { display: flex; flex-wrap: wrap; gap: 1rem; }
.stat-item { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748b; }
.stat-item i { font-size: 1rem; }

.empty-state { text-align: center; padding: 5rem 2rem; background: #f8fafc; border-radius: 20px; border: 2px dashed #e2e8f0; }
.empty-icon { width: 100px; height: 100px; background: #e2e8f0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; }
.empty-icon i { font-size: 3rem; color: #64748b; }
.empty-state h3 { font-size: 1.5rem; color: #1e293b; margin: 0 0 0.5rem 0; }
.empty-state p { margin: 0 0 2rem 0; color: #64748b; }

@media (max-width: 768px) {
    .programs-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: stretch; }
    .program-stats { flex-direction: column; gap: 0.5rem; }
}
</style>
