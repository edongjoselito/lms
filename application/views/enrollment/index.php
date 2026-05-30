<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <h5 style="font-weight:700;margin-bottom:0.5rem;">Enrollment Dashboard</h5>
            <p style="color:#64748b;margin:0;">View all enrolled students and enrollment statistics</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="form-card" style="background:linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);color:white;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.875rem;opacity:0.9;margin-bottom:0.5rem;">Total Enrolled</div>
                    <div style="font-size:2rem;font-weight:700;"><?= $stats['total_enrolled'] ?></div>
                </div>
                <i class="bi bi-people-fill" style="font-size:3rem;opacity:0.3;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-card" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%);color:white;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.875rem;opacity:0.9;margin-bottom:0.5rem;">Total Sections</div>
                    <div style="font-size:2rem;font-weight:700;"><?= $stats['total_sections'] ?></div>
                </div>
                <i class="bi bi-grid-fill" style="font-size:3rem;opacity:0.3;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-card" style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);color:white;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.875rem;opacity:0.9;margin-bottom:0.5rem;">Grade Levels</div>
                    <div style="font-size:2rem;font-weight:700;"><?= $stats['total_grade_levels'] ?></div>
                </div>
                <i class="bi bi-layers-fill" style="font-size:3rem;opacity:0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                <h5 style="font-weight:700;margin:0;">Enrolled Students</h5>
            </div>
            <?php if (empty($enrollments)): ?>
                <div style="text-align:center;padding:3rem 1rem;">
                    <i class="bi bi-inbox" style="font-size:4rem;color:#cbd5e1;display:block;margin-bottom:1rem;"></i>
                    <h5 style="color:#64748b;margin-bottom:0.5rem;">No Enrollments Yet</h5>
                    <p style="color:#94a3b8;max-width:400px;margin:0 auto;">No students have been enrolled yet. Go to Student Profiles to enroll students.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive" style="overflow:visible;">
                    <table class="table table-hover" style="margin:0;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Student Number</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Name</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Birth Date</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Grade Level</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Section</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">School Year</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;">Status</th>
                                <th style="font-weight:600;color:#475569;padding:0.75rem 1rem;text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $e): ?>
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:0.75rem 1rem;font-weight:500;"><?= htmlspecialchars($e->student_number) ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?= htmlspecialchars($e->last_name . ', ' . $e->first_name) ?>
                                        <?php if ($e->middle_name): ?>
                                            <?= ' ' . htmlspecialchars(substr($e->middle_name, 0, 1) . '.') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($e->birth_date) ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?php if ($e->grade_level_id): ?>
                                            <?= 'Grade ' . str_pad($e->grade_level_id, 2, '0', STR_PAD_LEFT) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;"><?= isset($e->section_name) ? htmlspecialchars($e->section_name) : '-' ?></td>
                                    <td style="padding:0.75rem 1rem;"><?= isset($e->school_year_name) ? htmlspecialchars($e->school_year_name) : '-' ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?php if ($e->status == 'enrolled'): ?>
                                            <span style="background:#dcfce7;color:#15803d;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:500;">Enrolled</span>
                                        <?php else: ?>
                                            <span style="background:#fee2e2;color:#dc2626;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:500;"><?= ucfirst($e->status) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;text-align:right;">
                                        <div class="dropdown" style="position:relative;">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" style="border-radius:8px;padding:0.4rem 0.8rem;font-size:0.8rem;">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="z-index:9999;">
                                                <li><a class="dropdown-item" href="<?= site_url('enrollment/edit/' . $e->id) ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item text-danger" href="<?= site_url('enrollment/delete/' . $e->id) ?>" onclick="return confirm('Delete this enrollment?');"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($grade_level_counts)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">Enrollment by Grade Level</h5>
            <div class="row g-3">
                <?php foreach ($grade_level_counts as $glc): ?>
                    <div class="col-md-3 col-sm-6">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem;text-align:center;">
                            <div style="font-size:2rem;font-weight:700;color:#6366f1;margin-bottom:0.5rem;"><?= $glc->count ?></div>
                            <div style="font-size:0.85rem;color:#64748b;font-weight:500;">
                                <?php if ($glc->grade_level_id): ?>
                                    Grade <?= str_pad($glc->grade_level_id, 2, '0', STR_PAD_LEFT) ?>
                                <?php else: ?>
                                    Unassigned
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
