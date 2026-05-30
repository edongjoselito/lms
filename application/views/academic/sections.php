<?php $sy_label = isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A'; ?>

<div class="sections-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Sections</h1>
            <p class="page-subtitle">Manage sections by grade level (S.Y. <?= $sy_label ?>)</p>
        </div>
        <a href="<?= site_url('academic/create_section') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Section
        </a>
    </div>

    <?php if (!empty($grade_levels)): ?>
        <?php foreach ($grade_levels as $gl): ?>
            <?php
            $gl_sections = array();
            if (!empty($sections)) {
                foreach ($sections as $sec) {
                    if (isset($sec->program_id) && $sec->program_id == $gl->id) {
                        $gl_sections[] = $sec;
                    }
                }
            }
            ?>
            <div class="grade-level-section">
                <div class="grade-level-header">
                    <h3 class="grade-level-title">
                        <i class="bi bi-layers me-2"></i><?= isset($gl->name) ? htmlspecialchars($gl->name) : (isset($gl->year_level) ? 'Grade ' . str_pad($gl->year_level, 2, '0', STR_PAD_LEFT) : '-') ?>
                        <span class="badge bg-primary"><?= count($gl_sections) ?> Section<?= count($gl_sections) != 1 ? 's' : '' ?></span>
                    </h3>
                    <a href="<?= site_url('academic/create_section_for_grade/' . $gl->id) ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-plus-lg"></i> Add Section
                    </a>
                </div>
                
                <?php if (!empty($gl_sections)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Section Name</th>
                                    <th>Adviser</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($gl_sections as $sec): ?>
                                    <tr>
                                        <td style="font-weight: 600;"><?= htmlspecialchars($sec->name) ?></td>
                                        <td style="color: #64748b;"><?= isset($sec->adviser_name) && $sec->adviser_name ? $sec->adviser_name : '<span style="color:#cbd5e1;">—</span>' ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="<?= site_url('academic/edit_section/' . $sec->id) ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-section">
                        <p class="text-muted mb-0">No sections created for this grade level yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-layers"></i>
            </div>
            <h3>No grade levels found</h3>
            <p>Create grade levels first to manage sections.</p>
            <a href="<?= site_url('academic/programs') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Grade Level
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.sections-page {
    padding: 1.5rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
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

.grade-level-section {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.grade-level-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.grade-level-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-responsive {
    background: #fff;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    border: none;
    padding: 1rem;
    background: #f8fafc;
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

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.empty-section {
    padding: 2rem 1.5rem;
    text-align: center;
    background: #f8fafc;
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
    
    .grade-level-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }
}
</style>
