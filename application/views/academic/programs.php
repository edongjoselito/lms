<div class="programs-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Academic Programs</h1>
            <p class="page-subtitle">Manage programs, grade levels, and their subjects</p>
        </div>
        <a href="<?= site_url('academic/create_program') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Program
        </a>
    </div>

    <?php if (!empty($programs)): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 15%;">Code</th>
                        <th style="width: 40%;">Name</th>
                        <th style="width: 35%;">Details</th>
                        <th style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programs as $p): ?>
                        <?php
                        $type = isset($p->type) ? $p->type : 'program';
                        $subject_count = ($type === 'program') ? count($this->Academic_model->get_subjects_by_program($p->id)) : 0;
                        ?>
                        <tr>
                            <td>
                                <span class="code-badge"><?= isset($p->code) ? htmlspecialchars($p->code) : (isset($p->year_level) ? 'G' . str_pad($p->year_level, 2, '0', STR_PAD_LEFT) : '-') ?></span>
                            </td>
                            <td>
                                <div class="program-name-cell">
                                    <?= isset($p->name) ? htmlspecialchars($p->name) : (isset($p->year_level) ? 'Grade ' . str_pad($p->year_level, 2, '0', STR_PAD_LEFT) : '-') ?>
                                    <?php if ($type == 'grade_level'): ?>
                                        <span class="badge bg-primary ms-2">Grade Level</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($type == 'program'): ?>
                                    <span class="detail-item">
                                        <i class="bi bi-journal-bookmark text-primary"></i>
                                        <?= $subject_count ?> subjects
                                    </span>
                                <?php else: ?>
                                    <span class="detail-item">
                                        <i class="bi bi-layers text-primary"></i>
                                        <?= ucfirst($p->category) ?>
                                    </span>
                                    <span class="detail-item">
                                        <i class="bi bi-sort-numeric-down text-primary"></i>
                                        Level <?= $p->level_order ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('academic/edit_program/' . $p->id) ?>">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <?php if ($type == 'program'): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?= site_url('academic/program_subjects/' . $p->id) ?>">
                                                    <i class="bi bi-book-half me-2"></i> Manage Subjects
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <a class="dropdown-item" href="<?= site_url('academic/create_section_for_grade/' . $p->id) ?>">
                                                    <i class="bi bi-people me-2"></i> Add Section
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= site_url('academic/delete_program/' . $p->id) ?>" onclick="return confirm('Delete this program?');">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-mortarboard"></i>
            </div>
            <h3>No programs yet</h3>
            <p>Create your first academic program or grade level to get started.</p>
            <a href="<?= site_url('academic/create_program') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Program
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.programs-page {
    padding: 1.5rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.page-subtitle {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0.3rem 0 0;
}

.table-responsive {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    overflow: visible;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    border: none;
    padding: 1rem;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

.code-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: #fff;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
    min-width: 60px;
    text-align: center;
}

.program-name-cell {
    font-weight: 600;
    color: #0f172a;
    font-size: 0.95rem;
}

.detail-item {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
    color: #475569;
    margin-right: 1rem;
}

.detail-item i {
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-buttons .btn {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #dbeafe;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.empty-icon i {
    font-size: 2rem;
    color: #2563eb;
}

.empty-state h3 {
    font-size: 1.25rem;
    color: #1e293b;
    margin: 0 0 0.5rem;
    font-weight: 600;
}

.empty-state p {
    margin: 0 0 1.5rem;
    color: #64748b;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
