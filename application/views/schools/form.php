<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title"><?= ($school) ? 'Edit School' : 'Add New School' ?></h1>
        <p class="hero-subtitle"><?= ($school) ? 'Update school information and settings' : 'Register a new educational institution' ?></p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('schools') ?>" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Back to Schools
            </a>
        </div>
        <div class="form-card">
            <h5 class="form-card-title">
                <i class="bi bi-building me-2"></i>
                <?= ($school) ? 'Edit School' : 'School Information' ?>
            </h5>
            <form action="<?= ($school) ? site_url('schools/edit/' . $school->id) : site_url('schools/create') ?>" method="post">
                <!-- Basic Information -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-info-circle me-2"></i>Basic Information</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">School Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" name="name" placeholder="Enter full school name" value="<?= ($school) ? htmlspecialchars($school->name) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">School ID Number</label>
                            <input type="text" class="form-control" name="school_id_number" placeholder="e.g., SCH-001" value="<?= ($school) ? htmlspecialchars($school->school_id_number) : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">School Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" required>
                                <option value="deped" <?= ($school && $school->type == 'deped') ? 'selected' : '' ?>>DepEd (K-12)</option>
                                <option value="ched" <?= ($school && $school->type == 'ched') ? 'selected' : '' ?>>CHED (Higher Ed)</option>
                                <option value="tesda" <?= ($school && $school->type == 'tesda') ? 'selected' : '' ?>>TESDA (Tech-Voc)</option>
                                <option value="both" <?= ($school && $school->type == 'both') ? 'selected' : '' ?>>All (K-12, CHED, TESDA)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-telephone me-2"></i>Contact Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" placeholder="school@example.com" value="<?= ($school) ? htmlspecialchars($school->email) : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contact_number" placeholder="e.g., +63 912 345 6789" value="<?= ($school) ? htmlspecialchars($school->contact_number) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="form-section">
                    <h6 class="section-title"><i class="bi bi-geo-alt me-2"></i>Location</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Division</label>
                            <input type="text" class="form-control" name="division" placeholder="e.g., NCR" value="<?= ($school) ? htmlspecialchars($school->division) : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Region</label>
                            <input type="text" class="form-control" name="region" placeholder="e.g., Metro Manila" value="<?= ($school) ? htmlspecialchars($school->region) : '' ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Complete Address</label>
                            <textarea class="form-control" name="address" rows="3" placeholder="Enter street address, city, zip code"><?= ($school) ? htmlspecialchars($school->address) : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <?php if ($school): ?>
                    <!-- Status -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="bi bi-toggle-on me-2"></i>Status</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" <?= ($school->status) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusSwitch">School is active and can be accessed</label>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Save School</button>
                    <a href="<?= site_url('schools') ?>" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
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

    .back-link {
        color: #64748b;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #0f172a;
    }

    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .form-card-title {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #0f172a;
        font-size: 1.1rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0d9488;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        font-size: 1rem;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }

    .form-control-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }

    .form-actions {
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 0.75rem;
    }

    .btn-light {
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.6rem 1.25rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
    }

    .btn-light:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .form-check-input:checked {
        background-color: #0d9488;
        border-color: #0d9488;
    }
</style>