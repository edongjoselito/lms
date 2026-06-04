<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="tar-page">
    <div class="tar-toolbar">
        <a href="<?= site_url('academic/programs') ?>" class="tar-btn tar-btn-light">
            <i class="bi bi-arrow-left"></i> Back to Programs
        </a>
        <button type="button" class="tar-btn tar-btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Report
        </button>
    </div>

    <div class="tar-hero">
        <div class="tar-hero-main">
            <div class="tar-kicker">Academic Report</div>
            <h1 class="tar-title">Teacher Assignment Report</h1>
            <p class="tar-subtitle">List of teachers assigned per subject, grouped by grade level.</p>
        </div>
        <div class="tar-stats">
            <div class="tar-stat">
                <div class="tar-stat-value"><?= (int) $report_group_total ?></div>
                <div class="tar-stat-label">Grade Levels</div>
            </div>
            <div class="tar-stat">
                <div class="tar-stat-value"><?= (int) $report_subject_total ?></div>
                <div class="tar-stat-label">Subjects</div>
            </div>
            <div class="tar-stat">
                <div class="tar-stat-value"><?= (int) $report_assignment_total ?></div>
                <div class="tar-stat-label">Assignments</div>
            </div>
        </div>
    </div>

    <div class="tar-meta">
        Generated on <?= htmlspecialchars($generated_at) ?>
    </div>

    <?php if (!empty($report_groups)): ?>
        <?php foreach ($report_groups as $group): ?>
            <section class="tar-section">
                <div class="tar-section-head">
                    <div>
                        <h2 class="tar-section-title"><?= htmlspecialchars($group['label']) ?></h2>
                        <div class="tar-section-subtitle"><?= (int) $group['subject_total'] ?> <?= $group['subject_total'] == 1 ? 'subject' : 'subjects' ?></div>
                    </div>
                </div>

                <div class="tar-table-wrap">
                    <table class="tar-table">
                        <thead>
                            <tr>
                                <th style="width:70px;">#</th>
                                <th style="width:180px;">Subject Code</th>
                                <th>Subject Description</th>
                                <th style="width:38%;">Assigned Teachers</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['subjects'] as $index => $subject): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <span class="tar-code"><?= htmlspecialchars($subject['code'] !== '' ? $subject['code'] : '-') ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($subject['description'] !== '' ? $subject['description'] : 'No description') ?></td>
                                    <td>
                                        <?php if (!empty($subject['teachers'])): ?>
                                            <div class="tar-teachers">
                                                <?php foreach ($subject['teachers'] as $teacher_name): ?>
                                                    <span class="tar-teacher-pill"><?= htmlspecialchars($teacher_name) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="tar-empty">No teacher assigned</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="tar-empty-card">
            <div class="tar-empty-title">No teacher assignments found</div>
            <div class="tar-empty-text">Assign teachers to subjects first, then generate the report again.</div>
        </div>
    <?php endif; ?>
</div>

<style>
.tar-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0 2rem;
    color: #0f172a;
}

.tar-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.tar-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1rem;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.15s ease, background 0.15s ease, color 0.15s ease;
}

.tar-btn:hover {
    transform: translateY(-1px);
    text-decoration: none;
}

.tar-btn-light {
    background: #eff6ff;
    color: #1d4ed8;
}

.tar-btn-light:hover {
    background: #dbeafe;
    color: #1e40af;
}

.tar-btn-primary {
    background: #1d4ed8;
    color: #fff;
}

.tar-btn-primary:hover {
    background: #1e40af;
    color: #fff;
}

.tar-hero {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1.75rem 2rem;
    border-radius: 24px;
    background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
    color: #fff;
    box-shadow: 0 8px 28px rgba(29, 78, 216, 0.18);
}

.tar-kicker {
    display: inline-block;
    margin-bottom: 0.55rem;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.8);
}

.tar-title {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.tar-subtitle {
    margin: 0.4rem 0 0;
    font-size: 0.92rem;
    color: rgba(255,255,255,0.82);
}

.tar-stats {
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
}

.tar-stat {
    min-width: 110px;
    padding: 0.9rem 1rem;
    border-radius: 16px;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.16);
    text-align: center;
}

.tar-stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;
}

.tar-stat-label {
    margin-top: 0.35rem;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.78);
}

.tar-meta {
    margin: 1rem 0 1.5rem;
    font-size: 0.82rem;
    color: #64748b;
}

.tar-section {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    margin-bottom: 1.25rem;
}

.tar-section-head {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}

.tar-section-title {
    margin: 0;
    font-size: 1.02rem;
    font-weight: 800;
    color: #0f172a;
}

.tar-section-subtitle {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
}

.tar-table-wrap {
    overflow-x: auto;
}

.tar-table {
    width: 100%;
    border-collapse: collapse;
}

.tar-table th,
.tar-table td {
    padding: 0.9rem 1rem;
    border-bottom: 1px solid #eef2f7;
    vertical-align: top;
    text-align: left;
    font-size: 0.88rem;
}

.tar-table th {
    background: #f8fafc;
    font-size: 0.74rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #475569;
}

.tar-table tbody tr:last-child td {
    border-bottom: none;
}

.tar-code {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 800;
    font-size: 0.78rem;
}

.tar-teachers {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}

.tar-teacher-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.65rem;
    border-radius: 999px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    font-size: 0.78rem;
    font-weight: 600;
}

.tar-empty {
    color: #94a3b8;
    font-style: italic;
}

.tar-empty-card {
    padding: 2.5rem 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #fff;
    text-align: center;
}

.tar-empty-title {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
}

.tar-empty-text {
    margin-top: 0.4rem;
    color: #64748b;
    font-size: 0.88rem;
}

@media (max-width: 768px) {
    .tar-hero {
        padding: 1.4rem;
    }

    .tar-title {
        font-size: 1.45rem;
    }
}

@media print {
    .tar-toolbar {
        display: none !important;
    }

    .tar-page {
        padding: 0;
    }

    .tar-hero {
        box-shadow: none;
        break-inside: avoid;
    }

    .tar-section {
        box-shadow: none;
        break-inside: avoid;
    }

    .tar-table th,
    .tar-table td {
        font-size: 11px;
    }
}
</style>
