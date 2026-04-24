<div class="settings-page">
    <div class="settings-header">
        <div>
            <h1 class="settings-title">Platform Settings</h1>
            <p class="settings-subtitle">Manage the BlueCampus login image shown on the left side of the sign-in page.</p>
        </div>
    </div>

    <?php if (!$settings_ready): ?>
        <div class="settings-alert settings-alert--error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Automatic setup failed.</strong>
                <div>BlueCampus could not create the `platform_settings` table automatically. Check database create-table permissions.</div>
            </div>
        </div>
    <?php endif; ?>

    <div class="settings-card">
        <div class="settings-card-head">
            <div>
                <h2>Login Image</h2>
                <p>Upload a left-side login image for BlueCampus. If no image is set, the default login design stays active.</p>
            </div>
        </div>

        <div class="settings-grid">
            <div class="settings-preview">
                <?php if (!empty($login_image_url)): ?>
                    <img src="<?= $login_image_url ?>" alt="BlueCampus Login Image Preview">
                <?php else: ?>
                    <div class="settings-preview-fallback">
                        <i class="bi bi-image"></i>
                        <strong>Default Login Design</strong>
                        <span>No custom image uploaded.</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="settings-actions">
                <form action="<?= site_url('settings/upload_login_image') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Choose Login Image</label>
                        <input type="file" class="form-control" name="login_image" accept=".jpg,.jpeg,.jfif,.png,.webp,.gif" <?= $settings_ready ? '' : 'disabled' ?> required>
                        <div class="form-note">Recommended: landscape image, JPG/JFIF/PNG/WebP/GIF, up to 4MB. If your phone saves HEIC/HEIF, export it as JPG first.</div>
                    </div>
                    <button type="submit" class="btn btn-primary settings-btn" <?= $settings_ready ? '' : 'disabled' ?>>
                        <i class="bi bi-upload"></i> Save Login Image
                    </button>
                </form>

                <?php if (!empty($login_image_url)): ?>
                    <a href="<?= site_url('settings/remove_login_image') ?>" class="btn btn-outline-danger settings-btn-secondary" onclick="return confirm('Remove the current login image and restore the default design?');">
                        <i class="bi bi-trash"></i> Remove Current Image
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .settings-page {
        max-width: 980px;
    }

    .settings-header {
        margin-bottom: 1.25rem;
    }

    .settings-title {
        margin: 0 0 0.25rem;
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
    }

    .settings-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .settings-alert {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px 16px;
        margin-bottom: 1rem;
        border-radius: 14px;
    }

    .settings-alert i {
        margin-top: 2px;
    }

    .settings-alert--error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .settings-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.5rem;
    }

    .settings-card-head {
        margin-bottom: 1.25rem;
    }

    .settings-card-head h2 {
        margin: 0 0 0.35rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }

    .settings-card-head p {
        margin: 0;
        color: #64748b;
        font-size: 0.9rem;
        max-width: 620px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 1.5rem;
        align-items: start;
    }

    .settings-preview {
        min-height: 320px;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #dbeafe;
        background: linear-gradient(160deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
    }

    .settings-preview img {
        width: 100%;
        height: 100%;
        min-height: 320px;
        object-fit: cover;
        display: block;
    }

    .settings-preview-fallback {
        min-height: 320px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: #fff;
        text-align: center;
        padding: 1.5rem;
    }

    .settings-preview-fallback i {
        font-size: 2rem;
        opacity: 0.9;
    }

    .settings-preview-fallback strong {
        font-size: 1.05rem;
        font-weight: 700;
    }

    .settings-preview-fallback span {
        font-size: 0.88rem;
        opacity: 0.85;
    }

    .settings-actions {
        display: grid;
        gap: 0.9rem;
    }

    .form-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 12px;
        border-color: #dbeafe;
        padding: 0.75rem 0.9rem;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-note {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #64748b;
    }

    .settings-btn,
    .settings-btn-secondary {
        width: 100%;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-weight: 600;
    }

    .settings-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    @media (max-width: 900px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
