<?php
$enrolled_ids = array_map('intval', array_column($enrolled_subjects ?? array(), 'id'));
$total_subjects = 0;
$enrolled_count = 0;

foreach ($subjects as $program) {
    foreach ($program['subjects'] as $subject) {
        $total_subjects++;
        if (in_array((int) $subject->id, $enrolled_ids, true)) {
            $enrolled_count++;
        }
    }
}

$available_count = max(0, $total_subjects - $enrolled_count);
?>
<div class="subjects-page">
    <div class="page-header subjects-hero">
        <div class="hero-copy">
            <span class="page-eyebrow"><i class="bi bi-compass"></i> Course catalog</span>
            <h1 class="page-title">My Courses</h1>
            <p class="page-subtitle">Access your enrolled subjects and explore available courses</p>
            <?php if ($year_level): ?>
                <div class="grade-level-badge">
                    <i class="bi bi-mortarboard"></i>
                    Grade <?= str_pad($year_level, 2, '0', STR_PAD_LEFT) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="subjects-summary">
            <div class="summary-tile">
                <i class="bi bi-journal-bookmark"></i>
                <div>
                    <span><?= (int) $total_subjects ?></span>
                    <small>Total</small>
                </div>
            </div>
            <div class="summary-tile enrolled">
                <i class="bi bi-check2-circle"></i>
                <div>
                    <span><?= (int) $enrolled_count ?></span>
                    <small>Enrolled</small>
                </div>
            </div>
        </div>
    </div>

    <div class="subjects-toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="subjectSearch" placeholder="Search courses..." class="search-input">
        </div>
    </div>

    <?php if ($total_subjects > 0): ?>
        <div class="subjects-grid">
            <?php foreach ($subjects as $program_code => $program): ?>
                <?php foreach ($program['subjects'] as $s): ?>
                    <?php
                    $is_enrolled = in_array((int) $s->id, $enrolled_ids, true);
                    $subject_name = isset($s->name) ? $s->name : (isset($s->description) ? substr($s->description, 0, 50) : 'Subject');
                    ?>
                    <div class="subject-card" data-search="<?= strtolower($s->code . ' ' . $subject_name) ?>">
                        <div class="subject-cover <?= empty($s->cover_photo) ? 'subject-cover-fallback' : '' ?>">
                            <?php if (!empty($s->cover_photo)): ?>
                                <img src="<?= base_url('uploads/covers/' . $s->cover_photo) ?>" alt="<?= htmlspecialchars($subject_name) ?>">
                            <?php else: ?>
                                <div class="cover-pattern">
                                    <span><?= htmlspecialchars(substr($s->code ?: 'Course', 0, 3)) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($is_enrolled): ?>
                                <span class="enrolled-badge"><i class="bi bi-check2"></i> Enrolled</span>
                            <?php endif; ?>
                        </div>
                        <div class="subject-card-body">
                            <div class="subject-meta">
                                <span class="subject-code"><?= htmlspecialchars($s->code) ?></span>
                                <?php if (isset($s->units)): ?>
                                    <span class="subject-units"><?= htmlspecialchars($s->units) ?> Units</span>
                                <?php endif; ?>
                            </div>
                            <h3 class="subject-name"><?= htmlspecialchars($s->code . ' - ' . $subject_name) ?></h3>
                            <p class="subject-description"><?= htmlspecialchars(isset($s->description) && $s->description ? $s->description : 'Course materials and activities are available for this subject.') ?></p>
                            <a href="<?= site_url('student/content/' . $s->id) ?>" class="subject-action <?= $is_enrolled ? 'primary' : '' ?>">
                                <i class="bi bi-arrow-right-circle"></i> <?= $is_enrolled ? 'Continue Learning' : 'Start Course' ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-book"></i>
            </div>
            <h3>No courses available</h3>
            <p>Check back later for new courses or contact your administrator</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('subjectSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const cards = document.querySelectorAll('.subject-card');
    
    cards.forEach(card => {
        const searchData = card.getAttribute('data-search');
        if (searchData.includes(search)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

<style>
.subjects-page {
    padding: 0.5rem 0 2rem;
}

.page-header {
    margin-bottom: 1.5rem;
}

.subjects-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 1.5rem;
    padding: 1.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
}

.hero-copy {
    min-width: 0;
}

.page-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.5rem;
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.page-subtitle {
    color: rgba(255, 255, 255, 0.85);
    margin: 0 0 0.75rem 0;
    font-size: 0.95rem;
    max-width: 620px;
    line-height: 1.55;
}

.grade-level-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 700;
    margin-top: 0.75rem;
    backdrop-filter: blur(10px);
}

.subjects-summary {
    display: grid;
    grid-template-columns: repeat(2, minmax(100px, 1fr));
    gap: 0.75rem;
    min-width: min(250px, 100%);
}

.summary-tile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
    padding: 1rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
}

.summary-tile i {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
}

.summary-tile.enrolled i {
    background: rgba(16, 185, 129, 0.3);
}

.summary-tile span {
    display: block;
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1;
}

.summary-tile small {
    display: block;
    margin-top: 0.25rem;
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.subjects-toolbar {
    margin-bottom: 1.5rem;
}

.search-box {
    position: relative;
    max-width: 400px;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
}

.search-input {
    width: 100%;
    padding: 0.85rem 1rem 0.85rem 2.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    background: #fff;
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.subject-card {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.subject-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.subject-cover {
    position: relative;
    height: 160px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.subject-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.subject-cover-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.cover-pattern {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
}

.cover-pattern span {
    font-size: 3rem;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.9);
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.enrolled-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.75rem;
    border-radius: 20px;
    background: rgba(16, 185, 129, 0.95);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    backdrop-filter: blur(10px);
}

.subject-card-body {
    display: flex;
    flex: 1;
    flex-direction: column;
    padding: 1.5rem;
}

.subject-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.subject-code {
    color: #667eea;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.subject-units {
    padding: 0.25rem 0.6rem;
    border-radius: 8px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 700;
}

.subject-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.subject-description {
    color: #64748b;
    font-size: 0.9rem;
    line-height: 1.6;
    margin: 0 0 1.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.subject-action {
    width: 100%;
    padding: 0.85rem 1.25rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: auto;
    background: #f1f5f9;
    color: #475569;
    border: 2px solid #e2e8f0;
    transition: all 0.2s ease;
}

.subject-action:hover {
    background: #e2e8f0;
    color: #1e293b;
    border-color: #cbd5e1;
}

.subject-action.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-color: transparent;
}

.subject-action.primary:hover {
    color: #fff;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #fff;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.empty-icon i {
    font-size: 2.25rem;
    color: #fff;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: #1e293b;
    margin: 0 0 0.75rem 0;
    font-weight: 700;
}

.empty-state p {
    color: #64748b;
    margin: 0;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .subjects-hero {
        align-items: flex-start;
        grid-template-columns: 1fr;
        padding: 1.5rem;
    }

    .subjects-summary {
        width: 100%;
        min-width: 0;
    }

    .summary-tile {
        flex: 1;
    }

    .search-box {
        max-width: 100%;
    }

    .subjects-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 520px) {
    .subjects-summary {
        grid-template-columns: 1fr;
    }

    .summary-tile {
        min-height: 70px;
    }
}
</style>
