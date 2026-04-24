<!-- Hero Banner -->
<div class="hero-banner">
    <div class="hero-content">
        <h1 class="hero-title">Select School</h1>
        <p class="hero-subtitle">Choose an institution to manage and configure</p>
    </div>
    <div class="hero-icon-wrap">
        <span class="material-symbols-outlined">domain</span>
    </div>
</div>

<!-- Schools Grid -->
<div class="schools-grid">
    <?php if (!empty($schools)): ?>
        <div class="row g-4">
            <?php foreach ($schools as $s): ?>
                <?php
                $type_labels = array('deped' => 'DepEd', 'basic' => 'DepEd', 'ched' => 'CHED', 'college' => 'CHED', 'tesda' => 'TESDA', 'tech_voc' => 'TESDA', 'both' => 'Both');
                $type = isset($s->type) ? $s->type : 'deped';
                $type_label = isset($type_labels[$type]) ? $type_labels[$type] : ucfirst($type);
                ?>
                <div class="col-md-6 col-lg-4">
                    <a href="<?= site_url('schools/switch_school/' . $s->id) ?>" class="school-card">
                        <div class="card-inner">
                            <div class="card-header-row">
                                <div class="icon-box">
                                    <span class="material-symbols-outlined">apartment</span>
                                </div>
                                <span class="type-tag"><?= $type_label ?></span>
                            </div>
                            <div class="card-body">
                                <h4 class="school-name"><?= htmlspecialchars($s->name) ?></h4>
                                <p class="school-id"><?= $s->school_id_number ?: 'No ID' ?></p>
                                <?php if ($s->division): ?>
                                    <p class="school-location">
                                        <span class="material-symbols-outlined">location_on</span>
                                        <?= htmlspecialchars($s->division) ?><?= $s->region ? ', ' . htmlspecialchars($s->region) : '' ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer-row">
                                <span class="status-pill <?= $s->status ? 'active' : 'inactive' ?>">
                                    <span class="dot"></span>
                                    <?= $s->status ? 'Active' : 'Inactive' ?>
                                </span>
                                <span class="action-link">
                                    Enter
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

            <!-- Add New School Card -->
            <div class="col-md-6 col-lg-4">
                <a href="<?= site_url('schools/create') ?>" class="school-card add-card">
                    <div class="add-card-inner">
                        <div class="add-icon-box">
                            <span class="material-symbols-outlined">add</span>
                        </div>
                        <span class="add-label">Add New School</span>
                        <span class="add-hint">Register a new institution</span>
                    </div>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon-box">
                <span class="material-symbols-outlined">domain_disabled</span>
            </div>
            <h4>No Schools Yet</h4>
            <p>Get started by creating your first school institution</p>
            <a href="<?= site_url('schools/create') ?>" class="btn-primary">
                <span class="material-symbols-outlined">add</span>
                Create First School
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Light Clean Aesthetic - Dashboard Style */
    .hero-banner {
        background: linear-gradient(135deg, #696cff 0%, #5f61f4 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
    }

    .hero-content {
        flex: 1;
    }

    .hero-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.25rem;
        letter-spacing: -0.02em;
    }

    .hero-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
        margin: 0;
    }

    .hero-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .hero-icon-wrap .material-symbols-outlined {
        font-size: 1.75rem;
        color: #ffffff;
    }

    /* School Cards - Light */
    .school-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #d9dee3;
        overflow: hidden;
        transition: all 0.2s ease;
        height: 100%;
        display: block;
        text-decoration: none;
        box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.08);
    }

    .school-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px 0 rgba(67, 89, 113, 0.15);
        border-color: #696cff;
    }

    .card-inner {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .card-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        background: #e7e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-box .material-symbols-outlined {
        font-size: 1.375rem;
        color: #696cff;
    }

    .type-tag {
        font-size: 0.6875rem;
        font-weight: 600;
        color: #697a8d;
        padding: 0.25rem 0.625rem;
        background: #f5f5f9;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .card-body {
        flex: 1;
        margin-bottom: 1rem;
    }

    .school-name {
        font-size: 1rem;
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 0.25rem;
        line-height: 1.4;
    }

    .school-id {
        font-size: 0.75rem;
        color: #a1acb8;
        margin-bottom: 0.5rem;
        font-variant-numeric: tabular-nums;
    }

    .school-location {
        font-size: 0.8125rem;
        color: #697a8d;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin: 0;
    }

    .school-location .material-symbols-outlined {
        font-size: 0.875rem;
        color: #a1acb8;
    }

    .card-footer-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.75rem;
        border-top: 1px solid #f5f5f9;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.6875rem;
        font-weight: 600;
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .status-pill.active {
        color: #28c76f;
        background: #e2f6ed;
    }

    .status-pill.inactive {
        color: #a1acb8;
        background: #f5f5f9;
    }

    .status-pill .dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }

    .action-link {
        font-size: 0.8125rem;
        font-weight: 500;
        color: #696cff;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.15s ease;
    }

    .action-link .material-symbols-outlined {
        font-size: 0.875rem;
    }

    .school-card:hover .action-link {
        gap: 0.375rem;
    }

    /* Add Card */
    .add-card {
        border: 2px dashed #d9dee3;
        background: transparent;
        box-shadow: none;
    }

    .add-card:hover {
        border-color: #696cff;
        background: #f8f8ff;
    }

    .add-card-inner {
        padding: 2rem 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 180px;
        text-align: center;
    }

    .add-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #e7e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .add-icon-box .material-symbols-outlined {
        font-size: 1.5rem;
        color: #696cff;
    }

    .add-card:hover .add-icon-box {
        background: #696cff;
    }

    .add-card:hover .add-icon-box .material-symbols-outlined {
        color: #ffffff;
    }

    .add-label {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 0.25rem;
    }

    .add-hint {
        font-size: 0.8125rem;
        color: #a1acb8;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #d9dee3;
        box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.08);
    }

    .empty-icon-box {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        background: #f5f5f9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .empty-icon-box .material-symbols-outlined {
        font-size: 2rem;
        color: #a1acb8;
    }

    .empty-state h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #566a7f;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #a1acb8;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #696cff;
        color: white;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(105, 108, 255, 0.4);
    }

    .btn-primary:hover {
        background: #5f61f4;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.5);
    }

    .btn-primary .material-symbols-outlined {
        font-size: 1.125rem;
    }
</style>