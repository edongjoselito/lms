<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('academic/programs') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Programs
            </a>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-mortarboard-fill me-2"></i><?= $program->code ?> - <?= $program->name ?></h5>
                <span class="badge-role badge-admin" style="font-size:0.8rem;"><?= $program->degree_type ?></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-lg-8">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-book-fill me-2"></i>Program Subjects</h5>
                <span class="text-muted" style="font-size:0.85rem;"><?= count($program_subjects) ?> subjects</span>
            </div>
            <div class="table-responsive">
                <?php if (!empty($program_subjects)): ?>
                    <?php 
                    // Group subjects by year_level and semester
                    $grouped = [];
                    foreach ($program_subjects as $s) {
                        $yl = $s->year_level ?: 'Unassigned';
                        $sem = $s->semester_type ?: 'Unassigned';
                        if (!isset($grouped[$yl])) {
                            $grouped[$yl] = [];
                        }
                        if (!isset($grouped[$yl][$sem])) {
                            $grouped[$yl][$sem] = [];
                        }
                        $grouped[$yl][$sem][] = $s;
                    }
                    // Define order for year levels and semesters
                    $year_order = ['1st', '2nd', '3rd', '4th'];
                    $sem_order = ['1st_sem', '2nd_sem'];
                    $sem_labels = ['1st_sem' => 'First', '2nd_sem' => 'Second'];
                    ?>
                    
                    <?php 
                    // First display known year levels in order
                    foreach ($year_order as $yl): 
                        if (isset($grouped[$yl])): 
                    ?>
                        <!-- Year Level Header -->
                        <div style="background:#6366f1;color:white;padding:0.75rem 1rem;font-weight:700;border-radius:8px 8px 0 0;margin-top:1rem;">
                            <?= $yl ?> Year
                        </div>
                        
                        <?php 
                        // Display semesters in order
                        foreach ($sem_order as $sem): 
                            if (isset($grouped[$yl][$sem])): 
                        ?>
                            <!-- Semester Header -->
                            <div style="background:#e0e7ff;padding:0.5rem 1rem;font-weight:600;color:#4338ca;border-bottom:1px solid #c7d2fe;">
                                <?= $sem_labels[$sem] ?> Semester
                            </div>
                            <table class="table" style="margin-bottom:0;">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Units</th>
                                        <th style="width:80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($grouped[$yl][$sem] as $s): ?>
                                        <tr>
                                            <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                                            <td style="font-weight:600;"><?= $s->description ?></td>
                                            <td style="color:#64748b;"><?= $s->units ?></td>
                                            <td>
                                                <a href="<?= site_url('academic/edit_program_subject/' . $program->id . '/' . $s->id) ?>" 
                                                   class="btn-action btn-edit" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <a href="<?= site_url('academic/remove_subject_from_program/' . $program->id . '/' . $s->id) ?>" 
                                                   class="btn-action btn-delete" title="Remove" 
                                                   onclick="return confirm('Remove this subject from the program?');">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php 
                            endif; 
                        endforeach; 
                        ?>
                        
                        <?php if (isset($grouped[$yl]['Unassigned'])): ?>
                            <!-- Unassigned Semester -->
                            <div style="background:#fef3c7;padding:0.5rem 1rem;font-weight:600;color:#92400e;border-bottom:1px solid #fde68a;">
                                Unassigned Semester
                            </div>
                            <table class="table" style="margin-bottom:0;">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Units</th>
                                        <th style="width:80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($grouped[$yl]['Unassigned'] as $s): ?>
                                        <tr>
                                            <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                                            <td style="font-weight:600;"><?= $s->description ?></td>
                                            <td style="color:#64748b;"><?= $s->units ?></td>
                                            <td>
                                                <a href="<?= site_url('academic/edit_program_subject/' . $program->id . '/' . $s->id) ?>" 
                                                   class="btn-action btn-edit" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <a href="<?= site_url('academic/remove_subject_from_program/' . $program->id . '/' . $s->id) ?>" 
                                                   class="btn-action btn-delete" title="Remove" 
                                                   onclick="return confirm('Remove this subject from the program?');">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                    
                    <?php 
                    // Display any other year levels not in the standard order
                    foreach ($grouped as $yl => $semesters): 
                        if (!in_array($yl, $year_order)): 
                    ?>
                        <div style="background:#6366f1;color:white;padding:0.75rem 1rem;font-weight:700;border-radius:8px 8px 0 0;margin-top:1rem;">
                            <?= $yl === 'Unassigned' ? 'Unassigned Year Level' : $yl . ' Year' ?>
                        </div>
                        <?php foreach ($semesters as $sem => $subjects): ?>
                            <div style="background:#e0e7ff;padding:0.5rem 1rem;font-weight:600;color:#4338ca;border-bottom:1px solid #c7d2fe;">
                                <?php if ($sem === 'Unassigned'): ?>
                                    Unassigned Semester
                                <?php elseif (isset($sem_labels[$sem])): ?>
                                    <?= $sem_labels[$sem] ?> Semester
                                <?php else: ?>
                                    <?= $sem ?> Semester
                                <?php endif; ?>
                            </div>
                            <table class="table" style="margin-bottom:0;">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Units</th>
                                        <th style="width:80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subjects as $s): ?>
                                        <tr>
                                            <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                                            <td style="font-weight:600;"><?= $s->description ?></td>
                                            <td style="color:#64748b;"><?= $s->units ?></td>
                                            <td>
                                                <a href="<?= site_url('academic/edit_program_subject/' . $program->id . '/' . $s->id) ?>" 
                                                   class="btn-action btn-edit" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <a href="<?= site_url('academic/remove_subject_from_program/' . $program->id . '/' . $s->id) ?>" 
                                                   class="btn-action btn-delete" title="Remove" 
                                                   onclick="return confirm('Remove this subject from the program?');">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                <?php else: ?>
                    <div class="text-center py-4" style="color:#94a3b8;">No subjects added to this program yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-plus-circle me-2" style="color:#6366f1;"></i>Add Subject
            </h5>
            
            <!-- Create New Subject Form -->
            <form action="<?= site_url('academic/create_program_subject/' . $program->id) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input type="text" class="form-control" name="code" required placeholder="e.g. CS101">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="2" placeholder="Subject description..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <select class="form-select" name="semester_type" required>
                        <option value="">Select Semester</option>
                        <option value="1st_sem">First Semester</option>
                        <option value="2nd_sem">Second Semester</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Year Level</label>
                    <select class="form-select" name="year_level" required>
                        <option value="">Select Year Level</option>
                        <option value="1st">1st Year</option>
                        <option value="2nd">2nd Year</option>
                        <option value="3rd">3rd Year</option>
                        <option value="4th">4th Year</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Units</label>
                    <input type="number" class="form-control" name="units" required min="0">
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="mb-2">
                            <label class="form-label" style="font-size:0.85rem;">Lec Hours</label>
                            <input type="number" class="form-control" name="lec_hours" min="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <label class="form-label" style="font-size:0.85rem;">Lab Hours</label>
                            <input type="number" class="form-control" name="lab_hours" min="0">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-primary-custom w-100 mt-2">
                    <i class="bi bi-plus-lg"></i> Create & Add
                </button>
            </form>
        </div>
    </div>
</div>
