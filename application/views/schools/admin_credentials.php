<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="hero-content">
        <h1 class="hero-title">School Admin Credentials</h1>
        <p class="hero-subtitle">Save these login details for the school administrator</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="<?= site_url('schools') ?>" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Back to Schools
            </a>
        </div>
        
        <div class="credentials-card">
            <div class="credentials-header">
                <div class="credentials-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h5 class="credentials-title">School Admin Account Created</h5>
                    <p class="credentials-subtitle">Please save these credentials securely</p>
                </div>
            </div>

            <div class="credentials-body">
                <div class="credentials-info">
                    <div class="info-item">
                        <label class="info-label">School Name</label>
                        <div class="info-value"><?= htmlspecialchars($credentials['school_name']) ?></div>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Email / Username</label>
                        <div class="info-value"><?= htmlspecialchars($credentials['email']) ?></div>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Password</label>
                        <div class="info-value password-value">
                            <span id="passwordDisplay"><?= htmlspecialchars($credentials['password']) ?></span>
                            <button type="button" class="btn-copy" onclick="copyPassword()">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Important:</strong> These credentials will not be shown again. Please download or copy them now.
                </div>
            </div>

            <div class="credentials-actions">
                <a href="<?= site_url('schools/download_credentials') ?>" class="btn btn-primary-custom">
                    <i class="bi bi-download me-2"></i> Download Credentials
                </a>
                <a href="<?= site_url('schools') ?>" class="btn btn-light">
                    Done
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-hero {
        margin-bottom: 1.25rem;
    }

    .hero-content {
        padding: 0;
    }

    .hero-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .hero-subtitle {
        color: #64748b;
        font-size: 0.875rem;
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
        color: #1e293b;
    }

    .credentials-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 2rem;
        max-width: 600px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .credentials-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .credentials-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .credentials-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .credentials-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    .credentials-body {
        margin-bottom: 2rem;
    }

    .credentials-info {
        background: #f8fafc;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        margin-bottom: 1.25rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        word-break: break-all;
    }

    .password-value {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-copy {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-copy:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    .alert-warning {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 10px;
        padding: 1rem;
        color: #92400e;
        font-size: 0.9rem;
    }

    .credentials-actions {
        display: flex;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-light {
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
    }

    .btn-light:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }
</style>

<script>
function copyPassword() {
    const password = document.getElementById('passwordDisplay').textContent;
    navigator.clipboard.writeText(password).then(function() {
        const btn = document.querySelector('.btn-copy');
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i>';
        btn.style.background = '#10b981';
        btn.style.color = '#ffffff';
        btn.style.borderColor = '#10b981';
        
        setTimeout(function() {
            btn.innerHTML = originalIcon;
            btn.style.background = '';
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    });
}
</script>
