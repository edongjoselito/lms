<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('academic/grade_levels') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Grade Levels
            </a>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-list-ol me-2"></i><?= $grade_level->code ?> - <?= $grade_level->name ?></h5>
                <span class="badge-role badge-user" style="font-size:0.8rem;"><?= ucfirst(str_replace('_', ' ', $grade_level->category)) ?></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-lg-8">
        <div class="data-table">
            <div class="table-header">
                <h5><i class="bi bi-book-fill me-2"></i>Grade Level Subjects</h5>
                <span class="text-muted" style="font-size:0.85rem;"><?= count($grade_level_subjects) ?> subjects</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Semester</th>
                            <th>Units</th>
                            <th style="width:80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($grade_level_subjects)): ?>
                            <?php foreach ($grade_level_subjects as $s): ?>
                                <tr>
                                    <td><span class="badge-role badge-user"><?= $s->code ?></span></td>
                                    <td style="font-weight:600;"><?= $s->description ?></td>
                                    <td style="color:#64748b;">
                                        <?php if ($s->semester_type == '1st_sem'): ?>First Semester
                                        <?php elseif ($s->semester_type == '2nd_sem'): ?>Second Semester
                                        <?php else: ?>-<?php endif; ?>
                                    </td>
                                    <td style="color:#64748b;"><?= $s->units ?></td>
                                    <td>
                                        <a href="<?= site_url('academic/edit_grade_level_subject/' . $grade_level->id . '/' . $s->id) ?>" 
                                           class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="<?= site_url('academic/remove_subject_from_grade_level/' . $grade_level->id . '/' . $s->id) ?>" 
                                           class="btn-action btn-delete" title="Remove" 
                                           onclick="return confirm('Remove this subject from the grade level?');">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color:#94a3b8;">No subjects added to this grade level yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-plus-circle me-2" style="color:#6366f1;"></i>Add Subject
            </h5>
            
            <ul class="nav nav-tabs" style="margin-bottom:1rem;border-bottom:1px solid #e2e8f0;">
                <li class="nav-item">
                    <a class="nav-link active" href="#" id="tab-create" onclick="switchTab('create'); return false;">Create New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="tab-select" onclick="switchTab('select'); return false;">Select Existing</a>
                </li>
            </ul>
            
            <!-- Create New Subject Form -->
            <form id="form-create" action="<?= site_url('academic/create_grade_level_subject/' . $grade_level->id) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input type="text" class="form-control" name="code" required placeholder="e.g. MATH1">
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
            
            <!-- Select Existing Subject Form -->
            <form id="form-select" action="<?= site_url('academic/grade_level_subjects/' . $grade_level->id) ?>" method="post" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">Select Subject</label>
                    <select class="form-select" name="subject_id" required>
                        <option value="">Choose a subject...</option>
                        <?php foreach ($available_subjects as $s): ?>
                            <option value="<?= $s->id ?>"><?= $s->code ?> - <?= $s->description ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Units</label>
                    <input type="number" class="form-control" name="units" required min="0">
                </div>
                <button type="submit" class="btn-primary-custom w-100">
                    <i class="bi bi-plus-lg"></i> Add to Grade Level
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.getElementById('tab-create').classList.toggle('active', tab === 'create');
    document.getElementById('tab-select').classList.toggle('active', tab === 'select');
    document.getElementById('form-create').style.display = tab === 'create' ? 'block' : 'none';
    document.getElementById('form-select').style.display = tab === 'select' ? 'block' : 'none';
}
</script>
