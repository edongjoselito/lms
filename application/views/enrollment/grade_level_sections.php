<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('enrollment') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Enrollment
            </a>
        </div>
        <div class="form-card" style="margin-bottom:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div>
                    <h5 style="font-weight:700;margin-bottom:0.4rem;"><?= htmlspecialchars($grade_level_label) ?> Enrollees</h5>
                    <p style="color:#64748b;margin:0;">Grouped by section, then by Male and Female.</p>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                    <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:999px;padding:0.4rem 0.8rem;font-size:0.8rem;font-weight:600;">
                        <?= count($sections) ?> Section<?= count($sections) != 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (empty($sections)): ?>
    <div class="row">
        <div class="col-12">
            <div class="form-card" style="text-align:center;padding:3rem 1rem;">
                <i class="bi bi-inbox" style="font-size:4rem;color:#cbd5e1;display:block;margin-bottom:1rem;"></i>
                <h5 style="color:#64748b;margin-bottom:0.5rem;">No Enrollees Found</h5>
                <p style="color:#94a3b8;max-width:420px;margin:0 auto;">There are no enrolled students for <?= htmlspecialchars($grade_level_label) ?> yet.</p>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($sections as $section): ?>
        <?php
        $male_count = count($section->male);
        $female_count = count($section->female);
        $unspecified_count = count($section->unspecified);
        $section_total = $male_count + $female_count + $unspecified_count;
        ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="form-card">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.25rem;">
                        <div>
                            <h5 style="font-weight:700;margin-bottom:0.35rem;"><?= htmlspecialchars($section->section_name) ?></h5>
                            <p style="color:#64748b;margin:0;">Total enrollees: <?= $section_total ?></p>
                        </div>
                        <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
                            <span style="background:#dbeafe;color:#1d4ed8;border-radius:999px;padding:0.35rem 0.8rem;font-size:0.78rem;font-weight:700;">Male: <?= $male_count ?></span>
                            <span style="background:#fce7f3;color:#be185d;border-radius:999px;padding:0.35rem 0.8rem;font-size:0.78rem;font-weight:700;">Female: <?= $female_count ?></span>
                            <?php if ($unspecified_count > 0): ?>
                                <span style="background:#f1f5f9;color:#475569;border-radius:999px;padding:0.35rem 0.8rem;font-size:0.78rem;font-weight:700;">Unspecified: <?= $unspecified_count ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div style="border:1px solid #dbeafe;border-radius:16px;overflow:hidden;height:100%;background:#f8fbff;">
                                <div style="padding:0.9rem 1rem;background:#eff6ff;border-bottom:1px solid #dbeafe;">
                                    <h6 style="margin:0;font-weight:700;color:#1d4ed8;"><i class="bi bi-gender-male me-1"></i> Male</h6>
                                </div>
                                <?php if (empty($section->male)): ?>
                                    <div style="padding:1.25rem;color:#94a3b8;text-align:center;">No male enrollees.</div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm" style="margin:0;">
                                            <thead>
                                                <tr>
                                                    <th style="padding:0.75rem 1rem;">Student Number</th>
                                                    <th style="padding:0.75rem 1rem;">Name</th>
                                                    <th style="padding:0.75rem 1rem;">Birth Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($section->male as $student): ?>
                                                    <tr>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($student->student_number ?? '-') ?></td>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars(trim(($student->last_name ?? '') . ', ' . ($student->first_name ?? '') . (!empty($student->middle_name) ? ' ' . substr($student->middle_name, 0, 1) . '.' : ''))) ?></td>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($student->birth_date ?? '-') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div style="border:1px solid #fbcfe8;border-radius:16px;overflow:hidden;height:100%;background:#fffafc;">
                                <div style="padding:0.9rem 1rem;background:#fdf2f8;border-bottom:1px solid #fbcfe8;">
                                    <h6 style="margin:0;font-weight:700;color:#be185d;"><i class="bi bi-gender-female me-1"></i> Female</h6>
                                </div>
                                <?php if (empty($section->female)): ?>
                                    <div style="padding:1.25rem;color:#94a3b8;text-align:center;">No female enrollees.</div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm" style="margin:0;">
                                            <thead>
                                                <tr>
                                                    <th style="padding:0.75rem 1rem;">Student Number</th>
                                                    <th style="padding:0.75rem 1rem;">Name</th>
                                                    <th style="padding:0.75rem 1rem;">Birth Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($section->female as $student): ?>
                                                    <tr>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($student->student_number ?? '-') ?></td>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars(trim(($student->last_name ?? '') . ', ' . ($student->first_name ?? '') . (!empty($student->middle_name) ? ' ' . substr($student->middle_name, 0, 1) . '.' : ''))) ?></td>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($student->birth_date ?? '-') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($section->unspecified)): ?>
                            <div class="col-12">
                                <div style="border:1px dashed #cbd5e1;border-radius:16px;overflow:hidden;background:#f8fafc;">
                                    <div style="padding:0.85rem 1rem;border-bottom:1px dashed #cbd5e1;">
                                        <h6 style="margin:0;font-weight:700;color:#475569;"><i class="bi bi-question-circle me-1"></i> Unspecified Gender</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm" style="margin:0;">
                                            <thead>
                                                <tr>
                                                    <th style="padding:0.75rem 1rem;">Student Number</th>
                                                    <th style="padding:0.75rem 1rem;">Name</th>
                                                    <th style="padding:0.75rem 1rem;">Birth Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($section->unspecified as $student): ?>
                                                    <tr>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($student->student_number ?? '-') ?></td>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars(trim(($student->last_name ?? '') . ', ' . ($student->first_name ?? '') . (!empty($student->middle_name) ? ' ' . substr($student->middle_name, 0, 1) . '.' : ''))) ?></td>
                                                        <td style="padding:0.75rem 1rem;"><?= htmlspecialchars($student->birth_date ?? '-') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<style>
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        min-width: 620px;
    }
}
</style>
