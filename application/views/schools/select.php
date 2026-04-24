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
                    $type_colors = array('deped' => '#3b82f6', 'basic' => '#3b82f6', 'ched' => '#10b981', 'college' => '#10b981', 'tesda' => '#8b5cf6', 'tech_voc' => '#8b5cf6', 'both' => '#f59e0b');
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
        padding: 0;
    }

    .hero-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.35rem;
        letter-spacing: -0.035em;
    }

    .hero-subtitle {
        color: #6b7280;
        font-size: 0.925rem;
        margin: 0;
        letter-spacing: -0.005em;
    }

    .school-select-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #ececee;
        overflow: hidden;
        transition: all 0.22s cubic-bezier(0.22, 1, 0.36, 1);
        height: 100%;
        box-shadow: 0 1px 2px rgba(10, 10, 10, 0.03);
    }

    .school-select-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(10, 10, 10, 0.08), 0 2px 6px rgba(10, 10, 10, 0.04);
        border-color: #e0e2e6;
    }

    .school-card-header {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
    }

    .school-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        backdrop-filter: blur(6px);
    }

    .school-card-type {
        font-size: 0.7rem;
        font-weight: 500;
        padding: 0.22rem 0.6rem;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 6px;
        letter-spacing: 0.01em;
        backdrop-filter: blur(6px);
    }

    .school-card-body {
        padding: 1.25rem;
    }

    .school-card-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.3rem;
        line-height: 1.35;
        letter-spacing: -0.015em;
    }

    .school-card-id {
        font-size: 0.78rem;
        color: #9ca3af;
        margin-bottom: 0.5rem;
        font-variant-numeric: tabular-nums;
    }

    .school-card-location {
        font-size: 0.78rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
        letter-spacing: -0.005em;
    }

    .school-card-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.72rem;
        font-weight: 500;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
    }

    .school-card-status.active {
        color: #047857;
        background: #ecfdf5;
    }

    .school-card-status.inactive {
        color: #9ca3af;
        background: #f5f5f7;
    }

    .school-card-status .status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }

    .school-card-footer {
        padding: 0.875rem 1.25rem;
        border-top: 1px solid #f2f2f4;
        background: #fafafa;
    }

    .enter-text {
        font-size: 0.82rem;
        font-weight: 500;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        letter-spacing: -0.005em;
        transition: color 0.15s ease;
    }

    .school-select-card:hover .enter-text {
        color: #1e293b;
    }

    /* Add School Card */
    .add-school-card {
        border: 1px dashed #d1d5db;
        background: #fafafa;
        box-shadow: none;
    }

    .add-school-card:hover {
        border-color: #3b82f6;
        background: #f1f5f9;
    }

    .add-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #ececee;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #6b7280;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .add-school-card:hover .add-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        border-color: #3b82f6;
        color: white;
    }

    .add-school-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
        letter-spacing: -0.02em;
    }

    .add-school-sub {
        font-size: 0.82rem;
        color: #6b7280;
        margin: 0;
        letter-spacing: -0.005em;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #ececee;
        box-shadow: 0 1px 2px rgba(10, 10, 10, 0.03);
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: #f5f5f7;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }

    .empty-state h4 {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .btn-banner-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 1.1rem;
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: white;
        border-radius: 9px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s ease;
        letter-spacing: -0.005em;
        box-shadow: 0 1px 3px rgba(59, 130, 246, 0.2);
    }

    .btn-banner-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
</style>