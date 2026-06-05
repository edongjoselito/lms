<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php
$profile_count = !empty($profiles) ? count($profiles) : 0;
$active_count = 0;
$search_label = isset($_GET['search']) ? trim((string) $_GET['search']) : '';
$enrolled_user_ids = isset($enrolled_user_ids) && is_array($enrolled_user_ids) ? array_map('intval', array_column($enrolled_user_ids, 'student_id')) : array();

if (!empty($profiles)) {
    foreach ($profiles as $profile_item) {
        if (!empty($profile_item->user_status)) {
            $active_count++;
        }
    }
}
?>

<div class="ps-page">
    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">SP</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Students</span>
                        <span class="ps-tag ps-tag-code">Profile Registry</span>
                    </div>
                    <h1 class="ps-hero-title">Student Profiles</h1>
                    <p class="ps-hero-desc">Manage student records, generate login-ready accounts, and enroll learners into grade levels and sections.</p>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $profile_count ?></div>
                    <div class="ps-hero-stat-lbl">Results</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= (int) $active_count ?></div>
                    <div class="ps-hero-stat-lbl">Active</div>
                </div>
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= $search_label !== '' ? '1' : '0' ?></div>
                    <div class="ps-hero-stat-lbl">Search Filter</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-person-vcard-fill"></i>
                    <span>Student Profiles</span>
                    <span class="ps-count-pill"><?= (int) $profile_count ?></span>
                </div>
                <div class="ps-card-tools">
                    <form action="<?= site_url('studentprofile') ?>" method="get" class="ps-search-form">
                        <div class="ps-search-wrap">
                            <i class="bi bi-search ps-search-icon"></i>
                            <input type="text" name="search" class="ps-search" placeholder="Search by name or student number..." value="<?= htmlspecialchars($search_label) ?>">
                        </div>
                        <button type="submit" class="ps-tool-btn">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <?php if ($search_label !== ''): ?>
                            <a href="<?= site_url('studentprofile') ?>" class="ps-tool-btn ps-tool-btn-light">
                                <i class="bi bi-x-lg"></i> Clear
                            </a>
                        <?php endif; ?>
                    </form>
                    <a href="<?= site_url('studentprofile/download_template') ?>" class="ps-tool-btn ps-tool-btn-light">
                        <i class="bi bi-download"></i> Download Template
                    </a>
                    <a href="<?= site_url('studentprofile/bulk_upload') ?>" class="ps-submit-btn ps-submit-btn-inline ps-submit-btn-secondary">
                        <i class="bi bi-upload"></i> Bulk Upload
                    </a>
                    <a href="<?= site_url('studentprofile/create') ?>" class="ps-submit-btn ps-submit-btn-inline">
                        <i class="bi bi-plus-lg"></i> Add Student
                    </a>
                </div>
            </div>

            <?php if (!empty($profiles)): ?>
                <div class="ps-table-head ps-student-table-head">
                    <div class="ps-th ps-th-num">#</div>
                    <div class="ps-th ps-th-id">Student Number</div>
                    <div class="ps-th ps-th-user">Student</div>
                    <div class="ps-th ps-th-birth">Birth Date</div>
                    <div class="ps-th ps-th-email">Email</div>
                    <div class="ps-th ps-th-status">Status</div>
                    <div class="ps-th ps-th-actions">Actions</div>
                </div>
                <div class="ps-subject-list">
                    <?php foreach ($profiles as $index => $p): ?>
                        <?php
                        $middle_initial = !empty($p->middle_name) ? ' ' . substr($p->middle_name, 0, 1) . '.' : '';
                        $student_name = trim($p->last_name . ', ' . $p->first_name . $middle_initial);
                        $student_initials = strtoupper(substr((string) $p->first_name, 0, 1) . substr((string) $p->last_name, 0, 1));
                        $profile_email = isset($p->profile_email) && trim((string) $p->profile_email) !== '' ? trim((string) $p->profile_email) : '';
                        $birth_date = !empty($p->birth_date) ? date('M d, Y', strtotime($p->birth_date)) : '-';
                        $is_currently_enrolled = !empty($p->user_id) && in_array((int) $p->user_id, $enrolled_user_ids, true);
                        ?>
                        <div class="ps-subject-item ps-student-item">
                            <div class="ps-col-num">
                                <span class="ps-subject-index"><?= $index + 1 ?></span>
                            </div>

                            <div class="ps-col-id">
                                <span class="ps-subject-code"><?= htmlspecialchars($p->student_number) ?></span>
                            </div>

                            <div class="ps-col-user">
                                <div class="ps-user-profile">
                                    <div class="ps-user-avatar"><?= htmlspecialchars($student_initials !== '' ? $student_initials : 'S') ?></div>
                                    <div class="ps-user-copy">
                                        <div class="ps-user-name"><?= htmlspecialchars($student_name !== '' ? $student_name : 'Student') ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="ps-col-birth">
                                <span class="ps-detail-badge">
                                    <i class="bi bi-calendar3"></i>
                                    <?= htmlspecialchars($birth_date) ?>
                                </span>
                            </div>

                            <div class="ps-col-email">
                                <?php if ($profile_email !== ''): ?>
                                    <span class="ps-user-email"><?= htmlspecialchars($profile_email) ?></span>
                                <?php else: ?>
                                    <span class="ps-muted-empty">—</span>
                                <?php endif; ?>
                            </div>

                            <div class="ps-col-status">
                                <?php if (!empty($p->user_status)): ?>
                                    <span class="ps-status-badge ps-status-active">Active</span>
                                <?php else: ?>
                                    <span class="ps-status-badge ps-status-inactive">Inactive</span>
                                <?php endif; ?>
                            </div>

                            <div class="ps-col-actions">
                                <?php if (!$is_currently_enrolled): ?>
                                    <a href="<?= site_url('studentprofile/enroll/' . $p->id) ?>" class="ps-action-btn ps-action-view" title="Enroll">
                                        <i class="bi bi-person-plus-fill"></i> Enroll
                                    </a>
                                <?php endif; ?>
                                <a href="<?= site_url('studentprofile/edit/' . $p->id) ?>" class="ps-action-btn ps-action-edit" title="Edit">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <a href="<?= site_url('studentprofile/delete/' . $p->id) ?>" class="ps-action-btn ps-action-del" title="Delete" onclick="return confirm('Delete this student profile? This will also delete the associated user account.');">
                                    <i class="bi bi-trash3-fill"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="ps-empty">
                    <div class="ps-empty-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <div class="ps-empty-title">No Student Profiles Yet</div>
                    <div class="ps-empty-sub">
                        <?= $search_label !== '' ? 'No student profiles match your current search.' : 'Create your first student profile to automatically generate a user account.' ?>
                    </div>
                    <div class="ps-empty-actions">
                        <a href="<?= site_url('studentprofile/create') ?>" class="ps-submit-btn ps-empty-btn">
                            <i class="bi bi-plus-lg"></i> Add Student
                        </a>
                        <a href="<?= site_url('studentprofile/bulk_upload') ?>" class="ps-tool-btn ps-tool-btn-light">
                            <i class="bi bi-upload"></i> Bulk Upload
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.ps-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

.ps-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}

.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.ps-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.3);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 1px;
}

.ps-hero-info {
    min-width: 0;
}

.ps-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.ps-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.ps-tag-degree {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.ps-tag-code {
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.9);
    border: 1px solid rgba(255,255,255,0.25);
}

.ps-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.ps-hero-desc {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.72);
    margin: 0;
    line-height: 1.5;
    max-width: 560px;
}

.ps-hero-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
    flex-wrap: wrap;
}

.ps-hero-stat {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 100px;
}

.ps-hero-stat-num {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.ps-hero-stat-lbl {
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255,255,255,0.75);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-top: 0.3rem;
}

.ps-layout {
    display: grid;
    gap: 1.5rem;
    align-items: start;
}

.ps-layout-full {
    grid-template-columns: 1fr;
}

.ps-card {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}

.ps-subject-card {
    overflow: visible;
}

.ps-subject-card > .ps-card-head {
    border-radius: 20px 20px 0 0;
}

.ps-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    flex-wrap: wrap;
}

.ps-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.ps-card-title i {
    color: #2563eb;
    font-size: 1rem;
}

.ps-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.6rem;
    letter-spacing: 0.02em;
}

.ps-card-tools {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.ps-search-form {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-wrap: wrap;
    margin: 0;
}

.ps-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.ps-search-icon {
    position: absolute;
    left: 0.7rem;
    color: #94a3b8;
    font-size: 0.8rem;
    pointer-events: none;
}

.ps-search {
    padding: 0.45rem 0.75rem 0.45rem 2rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.83rem;
    color: #334155;
    background: #fff;
    outline: none;
    width: 260px;
    font-family: inherit;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.ps-search:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}

.ps-tool-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    min-height: 40px;
    padding: 0.6rem 0.95rem;
    border-radius: 10px;
    border: 1px solid #dbe3ef;
    background: #fff;
    color: #475569;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}

.ps-tool-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
    text-decoration: none;
}

.ps-tool-btn-light {
    background: #fff;
}

.ps-submit-btn {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(59,130,246,0.35);
    font-family: inherit;
    margin-top: 0.25rem;
    letter-spacing: 0.01em;
    text-decoration: none;
}

.ps-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(59,130,246,0.45);
    color: #fff;
    text-decoration: none;
}

.ps-submit-btn-inline {
    width: auto;
    margin-top: 0;
    padding: 0.65rem 1rem;
    border-radius: 10px;
}

.ps-submit-btn-secondary {
    background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
    box-shadow: 0 4px 14px rgba(20,184,166,0.28);
}

.ps-submit-btn-secondary:hover {
    box-shadow: 0 8px 22px rgba(20,184,166,0.38);
}

.ps-student-table-head {
    display: grid;
    grid-template-columns: 44px minmax(130px, 0.95fr) minmax(220px, 1.2fr) minmax(140px, 0.9fr) minmax(200px, 1.2fr) minmax(110px, 0.6fr) auto;
    padding: 0.55rem 1.5rem;
    background: #f8faff;
    border-bottom: 1px solid #eaecf0;
}

.ps-th {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.ps-th-actions {
    text-align: right;
    min-width: 220px;
}

.ps-subject-list {
    position: relative;
    overflow: visible;
    padding: 0;
}

.ps-subject-item {
    position: relative;
    display: grid;
    grid-template-columns: 44px minmax(130px, 0.95fr) minmax(220px, 1.2fr) minmax(140px, 0.9fr) minmax(200px, 1.2fr) minmax(110px, 0.6fr) auto;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.14s;
    animation: ps-fadein 0.22s ease forwards;
}

@keyframes ps-fadein {
    from { opacity: 0; transform: translateY(3px); }
    to { opacity: 1; transform: translateY(0); }
}

.ps-subject-item:last-child {
    border-bottom: none;
}

.ps-subject-item:hover {
    background: #f8f9ff;
}

.ps-col-num {
    display: flex;
    align-items: center;
}

.ps-subject-index {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ps-col-id,
.ps-col-user,
.ps-col-birth,
.ps-col-email,
.ps-col-status {
    min-width: 0;
    padding-right: 1rem;
}

.ps-subject-code {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1d4ed8;
    border-radius: 7px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    white-space: nowrap;
    flex-shrink: 0;
}

.ps-user-profile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
}

.ps-user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: linear-gradient(135deg,#3b82f6,#60a5fa);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 800;
    flex-shrink: 0;
}

.ps-user-copy {
    min-width: 0;
}

.ps-user-name {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1e293b;
    line-height: 1.25;
}

.ps-user-email {
    font-size: 0.85rem;
    color: #64748b;
    line-height: 1.45;
    word-break: break-word;
}

.ps-detail-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 600;
}

.ps-detail-badge i {
    color: #2563eb;
}

.ps-muted-empty {
    color: #cbd5e1;
    font-style: italic;
    font-size: 0.82rem;
}

.ps-status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.32rem 0.7rem;
    border-radius: 999px;
    font-size: 0.74rem;
    font-weight: 700;
    letter-spacing: 0.02em;
}

.ps-status-active {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.ps-status-inactive {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.ps-col-actions {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    justify-content: flex-end;
    min-width: 220px;
}

.ps-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.38rem 0.7rem;
    border-radius: 9px;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.14s ease;
    white-space: nowrap;
    cursor: pointer;
}

.ps-action-view {
    background: #dbeafe;
    color: #1d4ed8;
}

.ps-action-view:hover {
    background: #bfdbfe;
    color: #1e40af;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-action-edit {
    background: #fef9c3;
    color: #a16207;
}

.ps-action-edit:hover {
    background: #fef08a;
    color: #854d0e;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-action-del {
    background: #fee2e2;
    color: #dc2626;
}

.ps-action-del:hover {
    background: #fecaca;
    color: #b91c1c;
    text-decoration: none;
    transform: translateY(-1px);
}

.ps-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4rem 2rem;
    text-align: center;
}

.ps-empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
    font-size: 1.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.ps-empty-title {
    font-size: 1rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.4rem;
}

.ps-empty-sub {
    font-size: 0.85rem;
    color: #94a3b8;
    max-width: 360px;
    line-height: 1.5;
}

.ps-empty-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 1.25rem;
}

.ps-empty-btn {
    width: auto;
    margin-top: 0;
    padding: 0.75rem 1rem;
}

@media (max-width: 1180px) {
    .ps-student-table-head {
        display: none;
    }

    .ps-subject-item {
        grid-template-columns: 36px 1fr auto;
        grid-template-rows: auto auto auto auto auto;
        gap: 0.65rem 0;
    }

    .ps-col-id,
    .ps-col-birth,
    .ps-col-email,
    .ps-col-status {
        grid-column: 2;
    }

    .ps-col-actions {
        grid-row: 1 / 6;
        align-self: start;
    }
}

@media (max-width: 768px) {
    .ps-hero-content {
        padding: 1.5rem;
    }

    .ps-hero-left {
        align-items: flex-start;
    }

    .ps-hero-title {
        font-size: 1.3rem;
    }

    .ps-card-head {
        padding: 1rem;
    }

    .ps-card-tools,
    .ps-search-form {
        width: 100%;
        justify-content: stretch;
    }

    .ps-search-wrap,
    .ps-search,
    .ps-tool-btn,
    .ps-submit-btn-inline {
        width: 100%;
    }

    .ps-subject-item {
        padding: 1rem;
    }
}

@media (max-width: 520px) {
    .ps-subject-item {
        grid-template-columns: 1fr;
        grid-template-rows: none;
        gap: 0.75rem;
    }

    .ps-col-num {
        display: none;
    }

    .ps-col-id,
    .ps-col-user,
    .ps-col-birth,
    .ps-col-email,
    .ps-col-status,
    .ps-col-actions {
        grid-column: auto;
        grid-row: auto;
        padding-right: 0;
    }

    .ps-col-actions {
        justify-content: flex-start;
        min-width: 0;
        flex-wrap: wrap;
    }

    .ps-empty-actions {
        flex-direction: column;
        width: 100%;
    }

    .ps-empty-actions .ps-tool-btn,
    .ps-empty-actions .ps-empty-btn {
        width: 100%;
    }
}
</style>
