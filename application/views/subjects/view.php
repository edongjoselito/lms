<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('subjects') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Subjects
            </a>
        </div>

        <div class="form-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 style="font-weight:700;margin-bottom:0.5rem;">
                        <span class="badge-role badge-user"><?= $subject->code ?></span>
                    </h5>
                    <p style="color:#64748b;margin:0;"><?= $subject->program_code ? $subject->program_code . ' - ' . $subject->program_name : ($subject->grade_level_name ?: '-') ?></p>
                </div>
                <div>
                    <a href="<?= site_url('subjects/edit/' . $subject->id) ?>" class="btn-primary-custom btn-sm">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div style="background:#f8fafc;padding:1.5rem;border-radius:10px;">
                        <h6 style="color:#64748b;margin-bottom:1rem;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Subject Information</h6>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div>
                                <small style="color:#94a3b8;">Program/Grade Level</small>
                                <div style="font-weight:600;"><?= $subject->program_code ? $subject->program_code . ' - ' . $subject->program_name : ($subject->grade_level_name ?: '-') ?></div>
                            </div>
                            <div>
                                <small style="color:#94a3b8;">Semester</small>
                                <div style="font-weight:600;"><?= $subject->semester_type ? $subject->semester_type . ' Semester' : '-' ?></div>
                            </div>
                            <div>
                                <small style="color:#94a3b8;">Units</small>
                                <div style="font-weight:600;"><?= $subject->units ?></div>
                            </div>
                            <div>
                                <small style="color:#94a3b8;">Lecture Hours</small>
                                <div style="font-weight:600;"><?= $subject->lec_hours ?></div>
                            </div>
                            <div>
                                <small style="color:#94a3b8;">Lab Hours</small>
                                <div style="font-weight:600;"><?= $subject->lab_hours ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background:#f8fafc;padding:1.5rem;border-radius:10px;">
                        <h6 style="color:#64748b;margin-bottom:1rem;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Description</h6>
                        <p style="color:#334155;line-height:1.6;"><?= nl2br(htmlspecialchars($subject->description ?: 'No description provided.')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
