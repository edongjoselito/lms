<div class="mb-3">
    <a href="<?= site_url('grades/class_record/' . $class_program->id) ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to Class Record
    </a>
</div>
<div class="form-card">
    <h5 style="font-weight:700;margin-bottom:0.5rem;">
        <i class="bi bi-pencil-square me-2" style="color:#6366f1;"></i>Encode Grades
    </h5>
    <div class="d-flex gap-3 mb-3" style="font-size:0.85rem;color:#64748b;">
        <span><strong>Student:</strong> <?= htmlspecialchars($enrollment->student_name) ?></span>
        <span><strong>Subject:</strong> <?= htmlspecialchars($class_program->subject_name) ?></span>
        <?php if (isset($semester) && $semester): ?>
            <span><strong>Quarter:</strong> <?= $semester->name ?></span>
        <?php endif; ?>
        <span class="badge-role <?= ($system_type == 'ched') ? 'badge-admin' : 'badge-user' ?>"><?= strtoupper($system_type) ?></span>
    </div>

    <form action="<?= site_url('grades/encode/' . $class_program->id . '/' . $enrollment->id) ?>" method="post">
        <?php foreach ($components as $comp): ?>
            <div class="mb-4 p-3" style="background:#f8fafc;border-radius:12px;">
                <h6 style="font-weight:700;font-size:0.9rem;margin-bottom:0.75rem;">
                    <?= $comp->name ?> (<?= $comp->code ?>) — <?= intval($comp->weight_percentage) ?>%
                </h6>
                <?php
                $comp_entries = isset($entries[$comp->id]) ? $entries[$comp->id] : array();
                $row_count = max(count($comp_entries), 3);
                ?>
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="font-size:0.85rem;">
                        <thead>
                            <tr>
                                <th style="width:40%;">Activity</th>
                                <th style="width:25%;">Score</th>
                                <th style="width:25%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < $row_count; $i++): ?>
                                <?php $entry = isset($comp_entries[$i]) ? $comp_entries[$i] : null; ?>
                                <tr>
                                    <td>
                                        <?php if ($entry): ?>
                                            <input type="hidden" name="component[<?= $comp->id ?>][<?= $i ?>][id]" value="<?= $entry->id ?>">
                                        <?php endif; ?>
                                        <input type="text" class="form-control form-control-sm" name="component[<?= $comp->id ?>][<?= $i ?>][name]"
                                               value="<?= $entry ? htmlspecialchars($entry->activity_name) : '' ?>" placeholder="Activity <?= $i + 1 ?>">
                                    </td>
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="component[<?= $comp->id ?>][<?= $i ?>][score]"
                                               value="<?= $entry ? $entry->score : '' ?>"></td>
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="component[<?= $comp->id ?>][<?= $i ?>][total]"
                                               value="<?= $entry ? $entry->total_score : '' ?>"></td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="mt-3 pt-3" style="border-top:1px solid #e2e8f0;">
            <button type="submit" class="btn-primary-custom"><i class="bi bi-calculator me-1"></i> Save & Compute Grade</button>
            <a href="<?= site_url('grades/class_record/' . $class_program->id) ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
        </div>
    </form>
</div>
