<?php
$sy_label = isset($school_year) && $school_year ? $school_year->year_start . '-' . $school_year->year_end : 'N/A';
$advisory_count = !empty($sections) ? count($sections) : 0;
?>

<div class="ps-page">
    <a href="<?= site_url('dashboard') ?>" class="ps-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Dashboard
    </a>

    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">AC</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-primary">Teacher</span>
                        <span class="ps-tag ps-tag-secondary">Advisory</span>
                    </div>
                    <h1 class="ps-hero-title">My Advisory Class</h1>
                    <p class="ps-hero-desc">View the students currently enrolled under your advisory section.</p>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $advisory_count ?></div>
                    <div class="ps-hero-stat-lbl">Section<?= $advisory_count === 1 ? '' : 's' ?></div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= htmlspecialchars($sy_label) ?></div>
                    <div class="ps-hero-stat-lbl">School Year</div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($sections)): ?>
        <div class="ps-empty">
            <div class="ps-empty-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="ps-empty-title">No advisory section assigned yet</div>
            <div class="ps-empty-sub">Once a section is assigned to you as adviser, the enrolled students will appear here.</div>
        </div>
    <?php else: ?>
        <?php foreach ($sections as $section): ?>
            <div class="ps-card mb-4">
                <div class="ps-card-head">
                    <div>
                        <div class="ps-card-title">
                            <i class="bi bi-diagram-3-fill"></i>
                            <span><?= htmlspecialchars($section->name) ?></span>
                        </div>
                        <div class="ps-card-sub">
                            <span class="ps-pill"><?= htmlspecialchars(isset($section->grade_level_label) ? $section->grade_level_label : '—') ?></span>
                            <span class="ps-pill"><?= (int) $section->student_count ?> Student<?= (int) $section->student_count === 1 ? '' : 's' ?></span>
                        </div>
                    </div>
                    <a href="<?= site_url('academic/section_students/' . (int) $section->id . '?back=' . urlencode('academic/my_advisory_class')) ?>" class="ps-open-btn">
                        <i class="bi bi-box-arrow-up-right"></i> Open Section
                    </a>
                </div>

                <div class="table-responsive">
                    <?php if (empty($section->students)): ?>
                        <div class="ps-empty-inline">
                            <i class="bi bi-info-circle"></i> No students enrolled in this section yet.
                        </div>
                    <?php else: ?>
                        <table class="table ps-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th>Student Name</th>
                                    <th>Student No.</th>
                                    <th>Enrolled Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($section->students as $index => $student): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($student->name) ?></td>
                                        <td><?= isset($student->student_number) && $student->student_number !== '' ? htmlspecialchars($student->student_number) : '-' ?></td>
                                        <td><?= !empty($student->enrolled_date) ? htmlspecialchars($student->enrolled_date) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.ps-page {
    padding: 1.25rem 0;
}

.ps-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 1.5rem;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}

.ps-back:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
}

.ps-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(5, 150, 105, 0.16);
}

.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0f766e 0%, #0f766e 48%, #14b8a6 100%);
}

.ps-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.045'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.ps-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}

.ps-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.ps-hero-avatar {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.18);
    border: 2px solid rgba(255, 255, 255, 0.28);
    color: #fff;
    font-size: 1.2rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 1px;
}

.ps-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.55rem;
}

.ps-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.ps-tag-primary {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.ps-tag-secondary {
    background: rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.ps-hero-title {
    color: #fff;
    font-size: 1.65rem;
    font-weight: 800;
    margin: 0;
}

.ps-hero-desc {
    margin: 0.45rem 0 0;
    color: rgba(255, 255, 255, 0.88);
    font-size: 0.93rem;
    max-width: 560px;
}

.ps-hero-stats {
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
}

.ps-hero-stat {
    min-width: 132px;
    padding: 0.95rem 1rem;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: #fff;
}

.ps-hero-stat-num {
    font-size: 1.35rem;
    font-weight: 800;
    line-height: 1.1;
}

.ps-hero-stat-lbl {
    margin-top: 0.25rem;
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.82);
}

.ps-card {
    background: #fff;
    border: 1px solid #dbeafe;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}

.ps-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem 1.35rem;
    border-bottom: 1px solid #eef2ff;
    flex-wrap: wrap;
}

.ps-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
}

.ps-card-title i {
    color: #0f766e;
}

.ps-card-sub {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 0.55rem;
}

.ps-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.28rem 0.7rem;
    border-radius: 999px;
    background: #f0fdfa;
    color: #0f766e;
    font-size: 0.76rem;
    font-weight: 700;
}

.ps-open-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 0.9rem;
    border-radius: 10px;
    background: #0f766e;
    color: #fff;
    text-decoration: none;
    font-size: 0.82rem;
    font-weight: 700;
}

.ps-open-btn:hover {
    background: #115e59;
    color: #fff;
    text-decoration: none;
}

.ps-empty {
    background: #fff;
    border: 1px solid #dbeafe;
    border-radius: 20px;
    padding: 3rem 1.5rem;
    text-align: center;
    color: #475569;
}

.ps-empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 1rem;
    border-radius: 20px;
    background: #ecfeff;
    color: #0f766e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.9rem;
}

.ps-empty-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
}

.ps-empty-sub {
    margin-top: 0.45rem;
    font-size: 0.9rem;
}

.ps-empty-inline {
    padding: 1.15rem 1.35rem;
    color: #64748b;
    font-size: 0.9rem;
}

.ps-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
}

.ps-table tbody td {
    vertical-align: middle;
    color: #0f172a;
}

@media (max-width: 768px) {
    .ps-hero-content {
        padding: 1.5rem;
    }

    .ps-hero-stats {
        width: 100%;
    }

    .ps-hero-stat {
        flex: 1 1 0;
    }

    .ps-card-head {
        padding: 1rem;
    }
}
</style>
