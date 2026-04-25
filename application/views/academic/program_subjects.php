<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="ps-page">

    <!-- Back -->
    <a href="<?= site_url('academic/programs') ?>" class="ps-back">
        <i class="bi bi-arrow-left-short" style="font-size:1.1rem;"></i> Back to Programs
    </a>

    <!-- Hero header -->
    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar"><?= strtoupper(substr($program->code, 0, 2)) ?></div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <?php if (!empty($program->degree_type)): ?>
                            <span class="ps-tag ps-tag-degree"><?= ucfirst($program->degree_type) ?></span>
                        <?php endif; ?>
                        <span class="ps-tag ps-tag-code"><?= htmlspecialchars($program->code) ?></span>
                    </div>
                    <h1 class="ps-hero-title"><?= htmlspecialchars($program->name) ?></h1>
                    <?php if (!empty($program->description)): ?>
                        <p class="ps-hero-desc"><?= htmlspecialchars($program->description) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="ps-hero-stats">
                <div class="ps-hero-stat">
                    <div class="ps-hero-stat-num"><?= count($program_subjects) ?></div>
                    <div class="ps-hero-stat-lbl">Total Subjects</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="ps-layout <?= $is_admin ? 'ps-layout-split' : 'ps-layout-full' ?>">

        <!-- Subject list -->
        <div class="ps-card ps-subject-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Subjects</span>
                    <span class="ps-count-pill"><?= count($program_subjects) ?></span>
                </div>
                <?php if (!empty($program_subjects)): ?>
                <div class="ps-search-wrap">
                    <i class="bi bi-search ps-search-icon"></i>
                    <input type="text" class="ps-search" id="subjectSearch" placeholder="Search subjects...">
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($program_subjects)): ?>
                <!-- Table header -->
                <div class="ps-table-head">
                    <div class="ps-th ps-th-num">#</div>
                    <div class="ps-th ps-th-subject">Subject</div>
                    <div class="ps-th ps-th-teachers">Assigned Teachers</div>
                    <div class="ps-th ps-th-actions">Actions</div>
                </div>
                <div class="ps-subject-list" id="subjectList">
                    <?php foreach ($program_subjects as $i => $s): ?>
                        <?php $assigned_ids = isset($assigned_map[$s->id]) ? $assigned_map[$s->id] : array(); ?>
                        <div class="ps-subject-item" data-search="<?= strtolower(htmlspecialchars($s->code . ' ' . $s->description)) ?>">

                            <!-- # -->
                            <div class="ps-col-num">
                                <span class="ps-subject-index"><?= $i + 1 ?></span>
                            </div>

                            <!-- Subject info -->
                            <div class="ps-col-subject">
                                <div class="ps-subject-top">
                                    <span class="ps-subject-code"><?= htmlspecialchars($s->code) ?></span>
                                    <span class="ps-subject-name"><?= htmlspecialchars($s->description) ?></span>
                                </div>
                            </div>

                            <!-- Teachers column -->
                            <div class="ps-col-teachers">
                                <?php if (!empty($assigned_ids)): ?>
                                    <div class="ps-teacher-badges">
                                        <?php foreach ($teachers as $t): ?>
                                            <?php if (in_array((int)$t->id, $assigned_ids)): ?>
                                                <span class="ps-teacher-badge">
                                                    <span class="ps-teacher-avatar"><?= strtoupper(substr($t->first_name, 0, 1)) ?></span>
                                                    <?= htmlspecialchars(trim($t->first_name . ' ' . $t->last_name)) ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="ps-teacher-empty">
                                        <?= $can_manage_teachers ? '— No teacher assigned' : '—' ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="ps-col-actions">
                                <a href="<?= site_url('course/content/' . $s->id . '?back=' . urlencode('academic/program_subjects/' . $program->id)) ?>" class="ps-action-btn ps-action-view">
                                    <i class="bi bi-eye-fill"></i> View
                                </a>
                                <?php if ($can_manage_teachers && !empty($teachers)): ?>
                                    <div class="dropdown">
                                        <button type="button" class="ps-action-btn ps-action-teacher" data-bs-toggle="dropdown" data-bs-boundary="viewport" title="Assign Teachers">
                                            <i class="bi bi-person-plus-fill"></i>
                                            <?php if (!empty($assigned_ids)): ?>
                                                <span class="ps-teacher-count"><?= count($assigned_ids) ?></span>
                                            <?php endif; ?>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end ps-teacher-menu">
                                            <li><span class="dropdown-header" style="font-size:0.75rem;font-weight:700;color:#6366f1;">Assign Teachers</span></li>
                                            <li class="px-2 pb-1">
                                                <input type="text" class="ps-teacher-search form-control form-control-sm" placeholder="Search teacher..." autocomplete="off">
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li class="ps-teacher-list" style="max-height:200px;overflow-y:auto;">
                                                <?php foreach ($teachers as $t): ?>
                                                    <?php $is_assigned = in_array((int)$t->id, $assigned_ids); ?>
                                                    <div class="ps-teacher-opt" data-name="<?= strtolower(htmlspecialchars(trim($t->first_name . ' ' . $t->last_name))) ?>">
                                                        <form action="<?= site_url('academic/assign_subject_teacher/' . $program->id . '/' . $s->id) ?>" method="post">
                                                            <input type="hidden" name="user_id" value="<?= $t->id ?>">
                                                            <button type="submit" class="dropdown-item <?= $is_assigned ? 'ps-teacher-assigned' : '' ?>">
                                                                <span class="ps-opt-avatar"><?= strtoupper(substr($t->first_name, 0, 1)) ?></span>
                                                                <span class="ps-opt-name"><?= htmlspecialchars(trim($t->first_name . ' ' . $t->last_name)) ?></span>
                                                                <?php if ($is_assigned): ?>
                                                                    <i class="bi bi-check2 ps-opt-check"></i>
                                                                <?php endif; ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endforeach; ?>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <?php if ($is_admin): ?>
                                    <a href="<?= site_url('academic/edit_program_subject/' . $program->id . '/' . $s->id) ?>" class="ps-action-btn ps-action-edit" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="<?= site_url('academic/remove_subject_from_program/' . $program->id . '/' . $s->id) ?>" class="ps-action-btn ps-action-del" title="Remove" onclick="return confirm('Remove this subject from the program?');">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ps-no-results" id="noResults" style="display:none;">
                    <i class="bi bi-search" style="font-size:1.8rem;opacity:0.3;"></i>
                    <p>No subjects match your search.</p>
                </div>
            <?php else: ?>
                <div class="ps-empty">
                    <div class="ps-empty-icon">
                        <i class="bi bi-journal-plus"></i>
                    </div>
                    <div class="ps-empty-title">No subjects yet</div>
                    <div class="ps-empty-sub">
                        <?= $is_admin ? 'Use the form to add the first subject to this program.' : 'No subjects have been added to this program yet.' ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Add subject form (admins only) -->
        <?php if ($is_admin): ?>
        <div class="ps-sidebar">
            <div class="ps-card ps-form-card">
                <div class="ps-card-head">
                    <div class="ps-card-title">
                        <i class="bi bi-plus-circle-fill" style="color:#6366f1;"></i>
                        <span>Add Subject</span>
                    </div>
                </div>
                <form action="<?= site_url('academic/create_program_subject/' . $program->id) ?>" method="post" class="ps-form">
                    <div class="ps-field">
                        <label class="ps-label">Course Code <span class="ps-req">*</span></label>
                        <input type="text" class="ps-input" name="code" required placeholder="e.g. CS101" autocomplete="off">
                    </div>
                    <div class="ps-field">
                        <label class="ps-label">Description</label>
                        <textarea class="ps-input ps-textarea" name="description" rows="3" placeholder="Enter subject description..."></textarea>
                    </div>
                    <button type="submit" class="ps-submit-btn">
                        <i class="bi bi-plus-lg"></i> Create & Add Subject
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
document.getElementById('subjectSearch') && document.getElementById('subjectSearch').addEventListener('input', function () {
    var q = this.value.toLowerCase().trim();
    var items = document.querySelectorAll('.ps-subject-item');
    var visible = 0;
    items.forEach(function (item) {
        var match = !q || item.dataset.search.includes(q);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('noResults').style.display = visible === 0 ? 'flex' : 'none';
});

// Teacher search inside dropdown
document.addEventListener('input', function (e) {
    var searchInput = e.target.closest('.ps-teacher-search');
    if (!searchInput) return;
    var q = searchInput.value.toLowerCase().trim();
    var menu = searchInput.closest('.ps-teacher-menu');
    var opts = menu.querySelectorAll('.ps-teacher-opt');
    opts.forEach(function (opt) {
        opt.style.display = (!q || opt.dataset.name.includes(q)) ? '' : 'none';
    });
});

// Keep dropdown open when typing in teacher search
document.addEventListener('click', function (e) {
    if (e.target.closest('.ps-teacher-search')) {
        e.stopPropagation();
    }
});

document.addEventListener('shown.bs.dropdown', function (event) {
    var item = event.target.closest('.ps-subject-item');
    if (item) item.classList.add('ps-dropdown-open');
});

document.addEventListener('hidden.bs.dropdown', function (event) {
    var item = event.target.closest('.ps-subject-item');
    if (item) item.classList.remove('ps-dropdown-open');
});
</script>

<style>
.ps-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

/* Back */
.ps-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #6366f1;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 1.5rem;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}
.ps-back:hover { background: #ede9fe; color: #4f46e5; text-decoration: none; }

/* Hero */
.ps-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(99,102,241,0.13);
}
.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #6366f1 100%);
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
.ps-hero-info { min-width: 0; }
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
.ps-tag-degree { background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); }
.ps-tag-code { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.25); }
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
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 480px;
}
.ps-hero-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
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

/* Layout */
.ps-layout { display: grid; gap: 1.5rem; align-items: start; }
.ps-layout-split { grid-template-columns: 1fr 300px; }
.ps-layout-full  { grid-template-columns: 1fr; }
@media (max-width: 860px) {
    .ps-layout-split { grid-template-columns: 1fr; }
}

/* Card */
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
.ps-card-title i { color: #6366f1; font-size: 1rem; }
.ps-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ede9fe;
    color: #6d28d9;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.6rem;
    letter-spacing: 0.02em;
}

/* Search */
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
    width: 200px;
    font-family: inherit;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.ps-search:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
@media (max-width: 600px) { .ps-search { width: 140px; } }

/* Table header */
.ps-table-head {
    display: grid;
    grid-template-columns: 44px 1fr 1fr auto;
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
.ps-th-actions { text-align: right; min-width: 160px; }

/* Subject items */
.ps-subject-list { position: relative; overflow: visible; padding: 0; }
.ps-subject-item {
    position: relative;
    display: grid;
    grid-template-columns: 44px 1fr 1fr auto;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.14s;
    animation: ps-fadein 0.22s ease forwards;
}
.ps-subject-item.ps-dropdown-open { z-index: 30; }
@keyframes ps-fadein { from { opacity:0; transform:translateY(3px); } to { opacity:1; transform:translateY(0); } }
.ps-subject-item:last-child { border-bottom: none; }
.ps-subject-item:hover { background: #f8f9ff; }

.ps-col-num { display: flex; align-items: center; }
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
.ps-col-subject { min-width: 0; padding-right: 1.5rem; }
.ps-subject-top {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-wrap: wrap;
}
.ps-subject-code {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    color: #5b21b6;
    border-radius: 7px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    white-space: nowrap;
    flex-shrink: 0;
}
.ps-subject-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.ps-col-teachers { min-width: 0; padding-right: 1rem; }
.ps-col-actions {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    justify-content: flex-end;
    min-width: 160px;
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
.ps-action-view { background: #e0e7ff; color: #4338ca; }
.ps-action-view:hover { background: #c7d2fe; color: #3730a3; text-decoration: none; transform: translateY(-1px); }
.ps-action-edit { background: #fef9c3; color: #a16207; }
.ps-action-edit:hover { background: #fef08a; color: #854d0e; text-decoration: none; transform: translateY(-1px); }
.ps-action-del { background: #fee2e2; color: #dc2626; }
.ps-action-del:hover { background: #fecaca; color: #b91c1c; text-decoration: none; transform: translateY(-1px); }
.ps-action-teacher { background: #dcfce7; color: #16a34a; border: none; }
.ps-action-teacher:hover { background: #bbf7d0; color: #15803d; transform: translateY(-1px); }
.ps-teacher-badges { display: flex; flex-wrap: wrap; gap: 5px; }
.ps-teacher-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #065f46;
    background: #d1fae5;
    border: 1px solid #a7f3d0;
    border-radius: 20px;
    padding: 3px 10px 3px 4px;
}
.ps-teacher-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ps-teacher-empty {
    font-size: 0.8rem;
    color: #cbd5e1;
    font-style: italic;
}
.ps-teacher-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #059669;
    color: #fff;
    border-radius: 10px;
    font-size: 0.6rem;
    font-weight: 800;
    padding: 0 5px;
    min-width: 16px;
    height: 16px;
    margin-left: 2px;
}
.ps-teacher-menu { min-width: 240px; z-index: 1080; padding: 0.5rem 0; }
.ps-teacher-menu .dropdown-header { font-size: 0.72rem; padding: 0.4rem 1rem 0.3rem; }
.ps-teacher-menu .ps-teacher-assigned { background: #f0fdf4; }
.ps-teacher-menu form { margin: 0; }
.ps-teacher-menu form .dropdown-item {
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    padding: 0.45rem 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.85rem;
}
.ps-teacher-menu form .dropdown-item:hover { background: #f8f9ff; }
.ps-opt-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #7c3aed);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ps-teacher-assigned .ps-opt-avatar { background: linear-gradient(135deg, #10b981, #059669); }
.ps-opt-name { flex: 1; font-weight: 500; color: #1e293b; }
.ps-opt-check { color: #10b981; font-size: 0.9rem; margin-left: auto; font-weight: 700; }
.ps-teacher-search { font-size: 0.82rem !important; border-radius: 7px !important; }
.ps-teacher-search:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 2px rgba(99,102,241,0.15) !important; }
.ps-teacher-list { display: block; }
@media (max-width: 768px) {
    .ps-table-head { display: none; }
    .ps-subject-item { grid-template-columns: 36px 1fr auto; grid-template-rows: auto auto; }
    .ps-col-teachers { grid-column: 2; grid-row: 2; padding-top: 0.4rem; }
    .ps-col-actions { grid-row: 1 / 3; }
}

/* No results */
.ps-no-results {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 3rem 2rem;
    color: #94a3b8;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
}
.ps-no-results p { margin: 0; }

/* Empty */
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
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    color: #7c3aed;
    font-size: 1.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}
.ps-empty-title { font-size: 1rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; }
.ps-empty-sub { font-size: 0.85rem; color: #94a3b8; max-width: 280px; line-height: 1.5; }

/* Form */
.ps-sidebar { position: sticky; top: 1rem; }
.ps-form-card {}
.ps-form { padding: 1.35rem 1.5rem 1.5rem; }
.ps-field { margin-bottom: 1.1rem; }
.ps-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.4rem;
    letter-spacing: 0.01em;
}
.ps-req { color: #ef4444; }
.ps-input {
    width: 100%;
    padding: 0.6rem 0.875rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.875rem;
    color: #1e293b;
    background: #fff;
    outline: none;
    font-family: inherit;
    transition: border-color 0.15s, box-shadow 0.15s;
    box-sizing: border-box;
}
.ps-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.ps-textarea { resize: vertical; }
.ps-submit-btn {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    font-family: inherit;
    margin-top: 0.25rem;
    letter-spacing: 0.01em;
}
.ps-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(99,102,241,0.45); }
.ps-submit-btn:active { transform: translateY(0); }
</style>
