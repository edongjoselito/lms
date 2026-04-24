<!-- Profile Page -->
<div class="pf-wrap">

    <!-- Page Header -->
    <div class="pf-hero">
        <div class="pf-hero-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8" />
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
        </div>
        <div>
            <h1 class="pf-hero-title">My Profile</h1>
            <p class="pf-hero-sub">Manage your account information and security settings</p>
        </div>
    </div>

    <!-- Avatar + Summary Strip -->
    <div class="pf-identity-card">
        <div class="pf-avatar-wrap">
            <?php if (!empty($user->avatar) && file_exists(FCPATH . $user->avatar)): ?>
                <img src="<?= base_url($user->avatar) ?>" alt="Avatar" class="pf-avatar-img">
            <?php else: ?>
                <div class="pf-avatar">
                    <?= strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) ?>
                </div>
            <?php endif; ?>
            <form action="<?= site_url('profile/upload_avatar') ?>" method="post" enctype="multipart/form-data" class="pf-avatar-form" id="avatarForm">
                <label class="pf-avatar-upload" title="Change avatar">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <input type="file" name="avatar" accept="image/*" class="d-none" onchange="document.getElementById('avatarForm').submit()">
                </label>
            </form>
            <?php if (!empty($user->avatar)): ?>
                <a href="<?= site_url('profile/remove_avatar') ?>" class="pf-avatar-remove" title="Remove avatar" onclick="return confirm('Remove your avatar?')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </a>
            <?php endif; ?>
        </div>
        <div class="pf-identity-info">
            <div class="pf-identity-name"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></div>
            <div class="pf-identity-meta">
                <span class="pf-role-pill">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                    </svg>
                    <?= htmlspecialchars($user->role_name) ?>
                </span>
                <?php if ($user->school_name): ?>
                    <span class="pf-dot">·</span>
                    <span class="pf-school">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                        <?= htmlspecialchars($user->school_name) ?>
                    </span>
                <?php endif; ?>
                <span class="pf-dot">·</span>
                <span class="pf-last-login">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                        <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    Last login: <?= $user->last_login ? date('M j, Y g:ia', strtotime($user->last_login)) : 'Never' ?>
                </span>
            </div>
        </div>
        <a href="<?= site_url('profile/change_password') ?>" class="pf-passwd-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8" />
                <path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
            Change Password
        </a>
    </div>

    <!-- Form Card -->
    <div class="pf-card">
        <?php echo form_open('profile/update'); ?>

        <!-- Section: Personal Information -->
        <div class="pf-section">
            <div class="pf-section-header">
                <span class="pf-section-icon pf-section-icon--blue">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" />
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </span>
                <div>
                    <div class="pf-section-title">Personal Information</div>
                    <div class="pf-section-desc">Update your name and contact details</div>
                </div>
            </div>

            <div class="pf-fields">
                <div class="pf-field-group">
                    <div class="pf-field">
                        <label class="pf-label">First Name <span class="pf-req">*</span></label>
                        <input type="text" class="pf-input" name="first_name"
                            value="<?= htmlspecialchars($user->first_name) ?>" required
                            placeholder="Enter first name">
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Last Name <span class="pf-req">*</span></label>
                        <input type="text" class="pf-input" name="last_name"
                            value="<?= htmlspecialchars($user->last_name) ?>" required
                            placeholder="Enter last name">
                    </div>
                </div>
                <div class="pf-field-group">
                    <div class="pf-field">
                        <label class="pf-label">Email Address <span class="pf-req">*</span></label>
                        <div class="pf-input-icon-wrap">
                            <svg class="pf-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.8" />
                                <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                            <input type="email" class="pf-input pf-input--icon" name="email"
                                value="<?= htmlspecialchars($user->email) ?>" required
                                placeholder="your@email.com">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Account Details (read-only) -->
        <div class="pf-section pf-section--readonly">
            <div class="pf-section-header">
                <span class="pf-section-icon pf-section-icon--slate">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                        <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </span>
                <div>
                    <div class="pf-section-title">Account Details</div>
                    <div class="pf-section-desc">Read-only information managed by your administrator</div>
                </div>
                <span class="pf-readonly-badge">Read only</span>
            </div>

            <div class="pf-readonly-grid">
                <div class="pf-readonly-item">
                    <div class="pf-readonly-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                        </svg>
                        Role
                    </div>
                    <div class="pf-readonly-value"><?= htmlspecialchars($user->role_name) ?></div>
                    <div class="pf-readonly-hint">Assigned by administrator</div>
                </div>

                <?php if ($user->school_name): ?>
                    <div class="pf-readonly-item">
                        <div class="pf-readonly-label">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                            Institution
                        </div>
                        <div class="pf-readonly-value"><?= htmlspecialchars($user->school_name) ?></div>
                        <div class="pf-readonly-hint">Your assigned school</div>
                    </div>
                <?php endif; ?>

                <div class="pf-readonly-item">
                    <div class="pf-readonly-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        Last Login
                    </div>
                    <div class="pf-readonly-value">
                        <?= $user->last_login ? date('F j, Y', strtotime($user->last_login)) : 'Never' ?>
                    </div>
                    <?php if ($user->last_login): ?>
                        <div class="pf-readonly-hint"><?= date('g:i a', strtotime($user->last_login)) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="pf-actions">
            <button type="submit" class="pf-save-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                    <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Save Changes
            </button>
            <button type="reset" class="pf-cancel-btn">
                Discard
            </button>
        </div>

        <?php echo form_close(); ?>
    </div>

</div>

<style>
    /* ── Wrap ──────────────────────────────────────── */
    .pf-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 1rem 0 4rem;
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
        color: #1d1d1f;
    }

    /* ── Hero ──────────────────────────────────────── */
    .pf-hero {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-shrink: 0;
    }

    .pf-hero-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #dbeafe;
        color: #3b82f6;
        flex-shrink: 0;
    }

    .pf-hero-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 2px;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .pf-hero-sub {
        font-size: 0.875rem;
        color: #86868b;
        margin: 0;
        line-height: 1.4;
        white-space: nowrap;
    }

    /* ── Identity Strip ────────────────────────────── */
    .pf-identity-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        flex-wrap: nowrap;
    }

    .pf-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .pf-avatar {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: #fff;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        letter-spacing: .02em;
    }

    .pf-avatar-img {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        object-fit: cover;
        display: block;
        border: 2px solid rgba(0, 0, 0, 0.08);
    }

    .pf-avatar-form {
        position: absolute;
        bottom: -4px;
        right: -4px;
        margin: 0;
    }

    .pf-avatar-upload {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #3b82f6;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease;
    }

    .pf-avatar-upload:hover {
        background: #2563eb;
        transform: scale(1.05);
    }

    .pf-avatar-remove {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ff3b30;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease;
    }

    .pf-avatar-remove:hover {
        background: #ff453a;
        transform: scale(1.05);
    }

    .pf-identity-info {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .pf-identity-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 4px;
        letter-spacing: -0.01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pf-identity-meta {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: #86868b;
        white-space: nowrap;
        overflow: hidden;
    }

    .pf-role-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: #dbeafe;
        color: #3b82f6;
        border-radius: 100px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .pf-school,
    .pf-last-login {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .pf-dot {
        color: #d2d2d7;
    }

    .pf-passwd-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.5rem 0.875rem;
        background: #f5f5f7;
        border: 1px solid #d2d2d7;
        border-radius: 8px;
        color: #1d1d1f;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-left: auto;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .pf-passwd-btn:hover {
        background: #fff;
        border-color: #3b82f6;
        color: #3b82f6;
    }

    /* ── Form Card ─────────────────────────────────── */
    .pf-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    /* ── Section ───────────────────────────────────── */
    .pf-section {
        padding: 1.75rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .pf-section--readonly {
        background: #fafafa;
    }

    .pf-section-header {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
        margin-bottom: 1.25rem;
        position: relative;
    }

    .pf-section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f5f5f7;
        color: #86868b;
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .pf-section-icon--blue {
        background: #dbeafe;
        color: #3b82f6;
    }

    .pf-section-icon--slate {
        background: #f5f5f7;
        color: #86868b;
    }

    .pf-section-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 2px;
    }

    .pf-section-desc {
        font-size: 0.8125rem;
        color: #86868b;
    }

    .pf-readonly-badge {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: #f5f5f7;
        color: #86868b;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* ── Fields ────────────────────────────────────── */
    .pf-fields {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .pf-field-group {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .pf-field {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 0;
    }

    .pf-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #1d1d1f;
        line-height: 1.3;
    }

    .pf-req {
        color: #ff3b30;
    }

    .pf-input {
        height: 44px;
        padding: 0 14px;
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        font-size: 0.9375rem;
        color: #1d1d1f;
        background: #fff;
        transition: all 0.2s ease;
        outline: none;
        width: 100%;
        box-sizing: border-box;
    }

    .pf-input:hover {
        border-color: rgba(0, 0, 0, 0.2);
    }

    .pf-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .pf-input-icon-wrap {
        position: relative;
    }

    .pf-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #86868b;
        pointer-events: none;
    }

    .pf-input--icon {
        padding-left: 40px;
    }

    /* ── Read-only Grid ────────────────────────────── */
    .pf-readonly-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .pf-readonly-item {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 1rem 1.15rem;
    }

    .pf-readonly-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #86868b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 6px;
    }

    .pf-readonly-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 3px;
    }

    .pf-readonly-hint {
        font-size: 0.75rem;
        color: #c4c4c4;
    }

    /* ── Actions ───────────────────────────────────── */
    .pf-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.25rem 2rem;
        background: #fafafa;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }

    .pf-save-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 0.65rem 1.25rem;
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-save-btn:hover {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .pf-save-btn:active {
        transform: translateY(0);
    }

    .pf-cancel-btn {
        padding: 9px 18px;
        background: transparent;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }

    .pf-cancel-btn:hover {
        background: #fff;
        color: #374151;
    }

    /* ── Responsive ────────────────────────────────── */
    @media (max-width: 768px) {
        .pf-wrap {
            padding: 1rem;
        }

        .pf-hero {
            margin-bottom: 1rem;
        }

        .pf-hero-sub {
            white-space: normal;
            font-size: 0.8125rem;
        }

        .pf-identity-card {
            flex-wrap: wrap;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
        }

        .pf-passwd-btn {
            margin-left: 0;
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }

        .pf-identity-meta {
            flex-wrap: wrap;
            white-space: normal;
        }

        .pf-section {
            padding: 1.25rem 1.1rem;
        }

        .pf-actions {
            padding: 1.1rem 1.1rem;
            flex-direction: column-reverse;
        }

        .pf-save-btn,
        .pf-cancel-btn {
            width: 100%;
            justify-content: center;
        }

        .pf-field-group {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .pf-section {
            padding: 1.25rem 1.1rem;
        }

        .pf-actions {
            padding: 1.1rem 1.1rem;
        }

        .pf-field-group {
            grid-template-columns: 1fr;
        }

        .pf-identity-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .pf-passwd-btn {
            width: 100%;
            justify-content: center;
        }

        .pf-readonly-badge {
            display: none;
        }
    }
</style>