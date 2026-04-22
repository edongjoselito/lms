<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title">Select School</h1>
        <p class="hero-subtitle">Choose an institution to manage and configure</p>
    </div>
</div>

<!-- Schools Grid -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <?php if (!empty($schools)): ?>
            <div class="row g-4">
                <?php foreach ($schools as $s): ?>
                    <?php
                    $type_colors = array('deped' => '#0f172a', 'basic' => '#0f172a', 'ched' => '#0d9488', 'college' => '#0d9488', 'tesda' => '#6366f1', 'tech_voc' => '#6366f1', 'both' => '#7c3aed');
                    $type_labels = array('deped' => 'DepEd', 'basic' => 'DepEd', 'ched' => 'CHED', 'college' => 'CHED', 'tesda' => 'TESDA', 'tech_voc' => 'TESDA', 'both' => 'Both');
                    $type = isset($s->type) ? $s->type : 'deped';
                    $type_color = isset($type_colors[$type]) ? $type_colors[$type] : '#64748b';
                    $type_label = isset($type_labels[$type]) ? $type_labels[$type] : ucfirst($type);
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="<?= site_url('schools/switch_school/' . $s->id) ?>" class="school-select-card" style="text-decoration:none;display:block;">
                            <div class="school-card-header" style="background: linear-gradient(135deg, <?= $type_color ?> 0%, <?= $type_color ?>dd 100%);">
                                <div class="school-card-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <span class="school-card-type"><?= $type_label ?></span>
                            </div>
                            <div class="school-card-body">
                                <h4 class="school-card-name"><?= htmlspecialchars($s->name) ?></h4>
                                <p class="school-card-id"><?= $s->school_id_number ?: 'No ID' ?></p>
                                <?php if ($s->division): ?>
                                    <p class="school-card-location"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($s->division) ?><?= $s->region ? ', ' . htmlspecialchars($s->region) : '' ?></p>
                                <?php endif; ?>
                                <div class="school-card-status <?= $s->status ? 'active' : 'inactive' ?>">
                                    <span class="status-dot"></span>
                                    <?= $s->status ? 'Active' : 'Inactive' ?>
                                </div>
                            </div>
                            <div class="school-card-footer">
                                <span class="enter-text">Enter School <i class="bi bi-arrow-right ms-1"></i></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

                <!-- Add New School Card -->
                <div class="col-md-6 col-lg-4">
                    <a href="<?= site_url('schools/create') ?>" class="school-select-card add-school-card" style="text-decoration:none;display:block;">
                        <div class="school-card-body d-flex flex-column align-items-center justify-content-center" style="min-height:200px;">
                            <div class="add-icon">
                                <i class="bi bi-plus-lg"></i>
                            </div>
                            <h4 class="add-school-text">Add New School</h4>
                            <p class="add-school-sub">Register a new institution</p>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-building-x"></i>
                </div>
                <h4>No Schools Yet</h4>
                <p>Get started by creating your first school institution</p>
                <a href="<?= site_url('schools/create') ?>" class="btn-banner-primary">
                    <i class="bi bi-plus-lg me-2"></i> Create First School
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .dashboard-hero {
        margin-bottom: 2rem;
    }

    .hero-content {
        padding: 0.5rem 0;
    }

    .hero-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .hero-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .school-select-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.2s ease;
        height: 100%;
    }

    .school-select-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }

    .school-card-header {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
    }

    .school-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .school-card-type {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 6px;
    }

    .school-card-body {
        padding: 1.25rem;
    }

    .school-card-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .school-card-id {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }

    .school-card-location {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.75rem;
    }

    .school-card-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
    }

    .school-card-status.active {
        color: #10b981;
        background: #dcfce7;
    }

    .school-card-status.inactive {
        color: #94a3b8;
        background: #f1f5f9;
    }

    .school-card-status .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .school-card-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .enter-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0d9488;
        display: flex;
        align-items: center;
    }

    .school-select-card:hover .enter-text {
        color: #0f766e;
    }

    /* Add School Card */
    .add-school-card {
        border: 2px dashed #cbd5e1;
        background: #f8fafc;
    }

    .add-school-card:hover {
        border-color: #0d9488;
        background: #f0fdfa;
    }

    .add-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #64748b;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }

    .add-school-card:hover .add-icon {
        background: #0d9488;
        color: white;
    }

    .add-school-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .add-school-sub {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #f8fafc;
        border-radius: 16px;
        border: 2px dashed #e2e8f0;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 1.5rem;
    }

    .empty-state h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    .btn-banner-primary {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: #0d9488;
        color: white;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-banner-primary:hover {
        background: #0f766e;
        color: white;
    }
</style>