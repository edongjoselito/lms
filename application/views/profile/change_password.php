<div class="cp-wrap">

    <!-- Breadcrumb -->
    <div class="cp-breadcrumb">
        <a href="<?= site_url('profile') ?>" class="cp-back-link">
            <span class="cp-back-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            Back to Profile
        </a>
    </div>

    <!-- Card -->
    <div class="cp-card">

        <!-- Card Header -->
        <div class="cp-card-header">
            <div class="cp-icon-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <circle cx="8" cy="15" r="4" stroke="currentColor" stroke-width="1.8" />
                    <path d="M12 15h8M17 13v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M8 11V8a4 4 0 018 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </div>
            <div>
                <h5 class="cp-card-title">Change Password</h5>
                <p class="cp-card-subtitle">Update your account password below</p>
            </div>
        </div>

        <!-- Form -->
        <?php echo form_open('profile/update_password'); ?>

        <div class="cp-fields">

            <div class="cp-field-group">
                <label class="cp-label" for="current_password">Current Password</label>
                <div class="cp-input-wrap">
                    <span class="cp-input-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8" />
                            <path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="current_password"
                        class="cp-input"
                        name="current_password"
                        placeholder="Enter current password"
                        required>
                    <button type="button" class="cp-toggle-btn" onclick="togglePassword('current_password', this)" aria-label="Toggle visibility">
                        <svg class="eye-show" width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                        <svg class="eye-hide" width="15" height="15" viewBox="0 0 24 24" fill="none" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="cp-divider"></div>

            <div class="cp-field-group">
                <label class="cp-label" for="new_password">New Password</label>
                <div class="cp-input-wrap">
                    <span class="cp-input-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="new_password"
                        class="cp-input"
                        name="new_password"
                        placeholder="Minimum 6 characters"
                        required
                        minlength="6"
                        oninput="checkStrength(this.value)">
                    <button type="button" class="cp-toggle-btn" onclick="togglePassword('new_password', this)" aria-label="Toggle visibility">
                        <svg class="eye-show" width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                        <svg class="eye-hide" width="15" height="15" viewBox="0 0 24 24" fill="none" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <!-- Strength meter -->
                <div class="cp-strength" id="strengthMeter" style="display:none">
                    <div class="cp-strength-bars">
                        <span class="cp-bar" id="bar1"></span>
                        <span class="cp-bar" id="bar2"></span>
                        <span class="cp-bar" id="bar3"></span>
                        <span class="cp-bar" id="bar4"></span>
                    </div>
                    <span class="cp-strength-label" id="strengthLabel">Weak</span>
                </div>
            </div>

            <div class="cp-field-group">
                <label class="cp-label" for="confirm_password">Confirm New Password</label>
                <div class="cp-input-wrap">
                    <span class="cp-input-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="confirm_password"
                        class="cp-input"
                        name="confirm_password"
                        placeholder="Re-enter new password"
                        required
                        oninput="checkMatch()">
                    <button type="button" class="cp-toggle-btn" onclick="togglePassword('confirm_password', this)" aria-label="Toggle visibility">
                        <svg class="eye-show" width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                        <svg class="eye-hide" width="15" height="15" viewBox="0 0 24 24" fill="none" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <span class="cp-match-msg" id="matchMsg"></span>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="cp-footer">
            <a href="<?= site_url('profile') ?>" class="cp-btn cp-btn--ghost">Cancel</a>
            <button type="submit" class="cp-btn cp-btn--primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                    <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Update Password
            </button>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    .cp-wrap {
        max-width: 520px;
        margin: 0 auto;
        padding: 1rem 1rem 3rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* ── Breadcrumb ── */
    .cp-breadcrumb {
        margin-bottom: 1.25rem;
    }

    .cp-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .875rem;
        font-weight: 600;
        color: #3b82f6;
        text-decoration: none;
        padding: 6px 12px 6px 8px;
        border-radius: 8px;
        transition: background .15s, color .15s;
    }

    .cp-back-link:hover {
        background: #eff6ff;
        color: #2563eb;
    }

    .cp-back-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #dbeafe;
        color: #3b82f6;
        flex-shrink: 0;
    }

    /* ── Card ── */
    .cp-card {
        background: #fff;
        border: 1px solid #e5e9f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(17, 24, 39, .05), 0 6px 20px rgba(17, 24, 39, .05);
    }

    /* ── Card Header ── */
    .cp-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, #f5f8ff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e5e9f0;
    }

    .cp-icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        border-radius: 12px;
        background: #dbeafe;
        color: #3b82f6;
        border: 1px solid #bfdbfe;
    }

    .cp-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 2px;
    }

    .cp-card-subtitle {
        font-size: .8rem;
        color: #64748b;
        margin: 0;
    }

    /* ── Fields ── */
    .cp-fields {
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .cp-divider {
        border: none;
        border-top: 1px dashed #e5e9f0;
        margin: 0;
    }

    .cp-field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .cp-label {
        font-size: .8rem;
        font-weight: 600;
        color: #374151;
        letter-spacing: .01em;
    }

    /* ── Input ── */
    .cp-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .cp-input-icon {
        position: absolute;
        left: 12px;
        display: flex;
        align-items: center;
        color: #94a3b8;
        pointer-events: none;
    }

    .cp-input {
        width: 100%;
        padding: 10px 42px 10px 38px;
        font-size: .9rem;
        color: #1e293b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        outline: none;
        transition: border-color .15s, background .15s, box-shadow .15s;
        font-family: inherit;
    }

    .cp-input:focus {
        background: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
    }

    .cp-input::placeholder {
        color: #94a3b8;
    }

    .cp-toggle-btn {
        position: absolute;
        right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        border-radius: 6px;
        transition: background .15s, color .15s;
        padding: 0;
    }

    .cp-toggle-btn:hover {
        background: #f1f5f9;
        color: #475569;
    }

    /* ── Strength Meter ── */
    .cp-strength {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }

    .cp-strength-bars {
        display: flex;
        gap: 4px;
        flex: 1;
    }

    .cp-bar {
        height: 4px;
        flex: 1;
        border-radius: 999px;
        background: #e2e8f0;
        transition: background .25s;
    }

    .cp-strength-label {
        font-size: .72rem;
        font-weight: 600;
        color: #94a3b8;
        white-space: nowrap;
        min-width: 44px;
        text-align: right;
    }

    /* ── Match Message ── */
    .cp-match-msg {
        font-size: .75rem;
        font-weight: 500;
        min-height: 1rem;
    }

    /* ── Footer ── */
    .cp-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .75rem;
        padding: 1.25rem 1.75rem;
        background: #f8fafc;
        border-top: 1px solid #e5e9f0;
    }

    .cp-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 20px;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: background .15s, transform .1s, box-shadow .15s;
        font-family: inherit;
    }

    .cp-btn--ghost {
        background: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .cp-btn--ghost:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    .cp-btn--primary {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: #fff;
    }

    .cp-btn--primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, .3);
        color: #fff;
    }

    .cp-btn--primary:active {
        transform: translateY(0);
    }

    @media (max-width: 480px) {
        .cp-fields {
            padding: 1.25rem;
        }

        .cp-footer {
            padding: 1rem 1.25rem;
            flex-direction: column-reverse;
        }

        .cp-btn {
            width: 100%;
            justify-content: center;
        }

        .cp-card-header {
            padding: 1.25rem;
        }
    }
</style>

<script>
    function togglePassword(id, btn) {
        var input = document.getElementById(id);
        var show = btn.querySelector('.eye-show');
        var hide = btn.querySelector('.eye-hide');
        if (input.type === 'password') {
            input.type = 'text';
            show.style.display = 'none';
            hide.style.display = '';
        } else {
            input.type = 'password';
            show.style.display = '';
            hide.style.display = 'none';
        }
    }

    function checkStrength(val) {
        var meter = document.getElementById('strengthMeter');
        var label = document.getElementById('strengthLabel');
        var bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
        var colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
        var labels = ['Weak', 'Fair', 'Good', 'Strong'];

        if (!val) {
            meter.style.display = 'none';
            return;
        }
        meter.style.display = 'flex';

        var score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        score = Math.min(score, 4);

        bars.forEach(function(b, i) {
            b.style.background = i < score ? colors[score - 1] : '#e2e8f0';
        });
        label.textContent = labels[score - 1] || 'Weak';
        label.style.color = colors[score - 1] || '#94a3b8';
    }

    function checkMatch() {
        var np = document.getElementById('new_password').value;
        var cp = document.getElementById('confirm_password').value;
        var msg = document.getElementById('matchMsg');
        if (!cp) {
            msg.textContent = '';
            return;
        }
        if (np === cp) {
            msg.textContent = '✓ Passwords match';
            msg.style.color = '#22c55e';
        } else {
            msg.textContent = '✗ Passwords do not match';
            msg.style.color = '#ef4444';
        }
    }
</script>