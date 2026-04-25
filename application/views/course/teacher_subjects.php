<?php
$subject_count = count($subjects);
$program_set = array();
foreach ($subjects as $s) {
    if (!empty($s->program_code)) $program_set[$s->program_code] = true;
}
$program_count = count($program_set);

$palette = array('#696cff','#03c3ec','#71dd37','#ffab00','#ff3e1d','#8592a3');
function ts_color($str, $palette) {
    return $palette[abs(crc32($str)) % count($palette)];
}
?>

<div class="ts-wrap">

    <!-- Hero -->
    <div class="ts-hero">
        <div class="ts-hero-inner">
            <div class="ts-hero-left">
                <div class="ts-hero-icon">
                    <span class="material-symbols-outlined">menu_book</span>
                </div>
                <div>
                    <h1 class="ts-hero-title">My Subjects</h1>
                    <p class="ts-hero-sub">Subjects assigned to you for section management</p>
                </div>
            </div>
            <div class="ts-hero-stats">
                <div class="ts-stat">
                    <div class="ts-stat-num"><?= $subject_count ?></div>
                    <div class="ts-stat-lbl">Subject<?= $subject_count !== 1 ? 's' : '' ?></div>
                </div>
                <?php if ($program_count > 0): ?>
                <div class="ts-stat-div"></div>
                <div class="ts-stat">
                    <div class="ts-stat-num"><?= $program_count ?></div>
                    <div class="ts-stat-lbl">Program<?= $program_count !== 1 ? 's' : '' ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="ts-body">

        <?php if (empty($subjects)): ?>
            <div class="ts-empty">
                <div class="ts-empty-icon">
                    <span class="material-symbols-outlined">auto_stories</span>
                </div>
                <h5 class="ts-empty-title">No subjects assigned yet</h5>
                <p class="ts-empty-desc">Your Course Creator will assign subjects to you. Check back later or contact your administrator.</p>
            </div>
        <?php else: ?>
            <div class="ts-grid">
                <?php foreach ($subjects as $s):
                    $color = ts_color($s->code, $palette);
                    $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $s->code), 0, 2) ?: substr($s->code, 0, 2));
                    $type = strtolower($s->system_type ?: 'general');
                ?>
                <div class="ts-card">
                    <div class="ts-card-top" style="background:<?= $color ?>18;border-bottom:2px solid <?= $color ?>30;">
                        <div class="ts-card-avatar" style="background:<?= $color ?>22;color:<?= $color ?>;"><?= htmlspecialchars($initials) ?></div>
                        <div class="ts-card-meta">
                            <?php if (!empty($s->program_code)): ?>
                                <span class="ts-badge ts-badge-program"><?= htmlspecialchars($s->program_code) ?></span>
                            <?php endif; ?>
                            <span class="ts-badge ts-badge-type ts-badge-<?= $type ?>"><?= strtoupper($type) ?></span>
                        </div>
                    </div>
                    <div class="ts-card-body">
                        <div class="ts-card-code"><?= htmlspecialchars($s->code) ?></div>
                        <?php if (!empty($s->description)): ?>
                            <div class="ts-card-desc"><?= htmlspecialchars($s->description) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($s->program_name)): ?>
                            <div class="ts-card-program"><?= htmlspecialchars($s->program_name) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="ts-card-foot">
                        <a href="<?= site_url('course/content/' . $s->id) ?>" class="ts-open-btn" style="--btn-color:<?= $color ?>;">
                            <span class="material-symbols-outlined" style="font-size:1rem;">open_in_new</span>
                            Open Subject
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
.ts-wrap {
    font-family: -apple-system, BlinkMacSystemFont, 'Public Sans', 'Segoe UI', sans-serif;
    color: #566a7f;
}

/* Hero */
.ts-hero {
    background: linear-gradient(135deg, #696cff 0%, #5a5de8 60%, #4a4dd4 100%);
    padding: 2.25rem 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    color: #fff;
}
.ts-hero-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.ts-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.ts-hero-icon {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.18);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ts-hero-icon .material-symbols-outlined {
    font-size: 1.75rem;
    color: #fff;
}
.ts-hero-title {
    font-size: 1.6rem;
    font-weight: 700;
    margin: 0 0 0.2rem;
    color: #fff;
    line-height: 1.2;
}
.ts-hero-sub {
    margin: 0;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.78);
}
.ts-hero-stats {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: rgba(255,255,255,0.12);
    border-radius: 12px;
    padding: 1rem 1.75rem;
}
.ts-stat {
    text-align: center;
}
.ts-stat-num {
    font-size: 1.75rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}
.ts-stat-lbl {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.75);
    margin-top: 3px;
    white-space: nowrap;
}
.ts-stat-div {
    width: 1px;
    height: 36px;
    background: rgba(255,255,255,0.25);
}

/* Body */
.ts-body {
    padding: 0;
}

/* Grid */
.ts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
}

/* Card */
.ts-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e7e7ff;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.18s, transform 0.18s;
}
.ts-card:hover {
    box-shadow: 0 8px 24px rgba(105,108,255,0.12);
    transform: translateY(-2px);
}
.ts-card-top {
    padding: 1.25rem 1.25rem 1rem;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
}
.ts-card-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 700;
    flex-shrink: 0;
    letter-spacing: 0.02em;
}
.ts-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    justify-content: flex-end;
    padding-top: 2px;
}
.ts-badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    white-space: nowrap;
    letter-spacing: 0.02em;
}
.ts-badge-program {
    background: #ede9fe;
    color: #5b21b6;
}
.ts-badge-type {
    background: #e7e7ff;
    color: #696cff;
}
.ts-badge-general  { background: #e7e7ff; color: #696cff; }
.ts-badge-k12      { background: #e0f2fe; color: #0369a1; }
.ts-badge-college  { background: #dcfce7; color: #166534; }
.ts-badge-tesda    { background: #fef9c3; color: #854d0e; }

.ts-card-body {
    padding: 0.9rem 1.25rem 0.75rem;
    flex: 1;
}
.ts-card-code {
    font-size: 1.05rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.3rem;
}
.ts-card-desc {
    font-size: 0.825rem;
    color: #697a8d;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ts-card-program {
    font-size: 0.775rem;
    color: #a1acb8;
    margin-top: 0.4rem;
}

.ts-card-foot {
    padding: 0.85rem 1.25rem;
    border-top: 1px solid #f1f1f4;
}
.ts-open-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    width: 100%;
    padding: 0.5rem 1rem;
    background: var(--btn-color, #696cff);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.84rem;
    font-weight: 600;
    transition: opacity 0.15s, transform 0.15s;
}
.ts-open-btn:hover {
    color: #fff;
    opacity: 0.88;
    transform: translateY(-1px);
}

/* Empty state */
.ts-empty {
    text-align: center;
    padding: 5rem 2rem;
    background: #fff;
    border-radius: 12px;
    border: 1px dashed #d0d5dd;
}
.ts-empty-icon {
    width: 72px;
    height: 72px;
    background: #f5f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}
.ts-empty-icon .material-symbols-outlined {
    font-size: 2rem;
    color: #a1acb8;
}
.ts-empty-title {
    font-size: 1.05rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}
.ts-empty-desc {
    font-size: 0.875rem;
    color: #697a8d;
    max-width: 380px;
    margin: 0 auto;
    line-height: 1.6;
}

@media (max-width: 600px) {
    .ts-hero { padding: 1.5rem 1.25rem; }
    .ts-hero-stats { display: none; }
    .ts-grid { grid-template-columns: 1fr; }
}
</style>
