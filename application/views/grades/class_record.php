<div class="mb-3">
    <a href="<?= site_url('grades') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>
<div class="data-table">
    <div class="table-header">
        <h5>
            <i class="bi bi-journal-check me-2"></i>
            <?= htmlspecialchars($class_program->subject_name) ?>
            <small style="color:#94a3b8;font-weight:400;font-size:0.8rem;">— <?= $class_program->section_name ?></small>
        </h5>
        <?php if (isset($semester) && $semester): ?>
            <span class="badge-role badge-user"><?= $semester->name ?></span>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>LRN / ID</th>
                    <?php foreach ($components as $comp): ?>
                        <th class="text-center"><?= $comp->code ?> (<?= intval($comp->weight_percentage) ?>%)</th>
                    <?php endforeach; ?>
                    <th class="text-center">Initial</th>
                    <th class="text-center">Transmuted</th>
                    <th class="text-center">Remarks</th>
                    <th style="width:80px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php $i = 1; foreach ($students as $s): ?>
                        <?php $g = isset($grades[$s->id]) ? $grades[$s->id] : null; ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($s->student_name) ?></td>
                            <td style="color:#64748b;font-size:0.85rem;"><?= $s->lrn ?: ($s->stud_id_num ?: '—') ?></td>
                            <?php foreach ($components as $comp): ?>
                                <td class="text-center" style="color:#64748b;">
                                    <?php
                                    $val = '—';
                                    if ($g) {
                                        $code = strtolower($comp->code);
                                        $field = $code . '_score';
                                        if (isset($g->$field) && $g->$field !== null) $val = number_format($g->$field, 1);
                                    }
                                    echo $val;
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="text-center" style="color:#64748b;"><?= $g && $g->initial_grade ? number_format($g->initial_grade, 2) : '—' ?></td>
                            <td class="text-center" style="font-weight:700;<?= ($g && $g->transmuted_grade && $g->transmuted_grade < 75) ? 'color:#dc2626;' : 'color:#15803d;' ?>">
                                <?= $g && $g->transmuted_grade ? $g->transmuted_grade : '—' ?>
                            </td>
                            <td class="text-center">
                                <?php if ($g && $g->remarks && $g->remarks !== 'in_progress'): ?>
                                    <span class="badge-status <?= ($g->remarks == 'passed') ? 'badge-active' : 'badge-inactive' ?>">
                                        <?= ucfirst($g->remarks) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color:#94a3b8;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= site_url('grades/encode/' . $class_program->id . '/' . $s->id) ?>" class="btn-action" title="Encode Grades">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?= 7 + count($components) ?>" class="text-center py-5" style="color:#94a3b8;">No students enrolled in this section.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
