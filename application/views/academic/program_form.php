<style>
    .ap-shell {
        max-width: 640px;
        margin: 0 auto;
        padding: 1rem 1.25rem 4rem;
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Inter", "Helvetica Neue", Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .ap-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: #6e6e73;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 500;
        letter-spacing: -0.01em;
        margin-bottom: 1.75rem;
        transition: color 0.2s ease;
    }
    .ap-back:hover { color: #1d1d1f; }

    .ap-header {
        text-align: center;
        margin-bottom: 2.25rem;
    }
    .ap-eyebrow {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #6366f1;
        margin-bottom: 0.55rem;
    }
    .ap-title {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.025em;
        color: #1d1d1f;
        margin: 0 0 0.5rem;
        line-height: 1.15;
    }
    .ap-subtitle {
        font-size: 0.95rem;
        color: #86868b;
        font-weight: 400;
        letter-spacing: -0.005em;
        margin: 0;
    }

    .ap-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow:
            0 1px 2px rgba(16, 24, 40, 0.04),
            0 12px 32px rgba(16, 24, 40, 0.05);
        padding: 2.25rem 2rem;
    }

    .ap-field { margin-bottom: 1.4rem; }
    .ap-field:last-of-type { margin-bottom: 0; }

    .ap-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 600;
        color: #1d1d1f;
        letter-spacing: -0.005em;
        margin-bottom: 0.5rem;
    }
    .ap-optional {
        font-size: 0.7rem;
        font-weight: 500;
        color: #a1a1a6;
        letter-spacing: 0;
    }

    .ap-input,
    .ap-select,
    .ap-textarea {
        width: 100%;
        background: #f5f5f7;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.78rem 0.95rem;
        font-size: 0.94rem;
        font-family: inherit;
        color: #1d1d1f;
        letter-spacing: -0.005em;
        transition: background 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
    }
    .ap-input::placeholder,
    .ap-textarea::placeholder { color: #b0b0b6; font-weight: 400; }
    .ap-input:hover,
    .ap-select:hover,
    .ap-textarea:hover { background: #ececef; }
    .ap-input:focus,
    .ap-select:focus,
    .ap-textarea:focus {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.14);
    }
    .ap-textarea { resize: vertical; min-height: 92px; }

    .ap-select {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3e%3cpath fill='none' stroke='%2386868b' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round' d='M3 4.5l3 3 3-3'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.95rem center;
        background-size: 12px 12px;
        padding-right: 2.4rem;
    }

    .ap-row {
        display: grid;
        grid-template-columns: 0.7fr 1.3fr;
        gap: 0.85rem;
        margin-bottom: 1.4rem;
    }
    @media (max-width: 520px) {
        .ap-row { grid-template-columns: 1fr; }
    }
    .ap-row .ap-field { margin-bottom: 0; }

    .ap-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, rgba(0,0,0,0.08), transparent);
        margin: 1.75rem 0 1.4rem;
    }

    .ap-actions {
        display: flex;
        gap: 0.6rem;
        justify-content: flex-end;
        align-items: center;
    }
    .ap-btn {
        border: none;
        border-radius: 980px;
        padding: 0.7rem 1.5rem;
        font-size: 0.88rem;
        font-weight: 600;
        letter-spacing: -0.005em;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        font-family: inherit;
    }
    .ap-btn-ghost {
        background: transparent;
        color: #1d1d1f;
    }
    .ap-btn-ghost:hover { background: #f5f5f7; color: #1d1d1f; }

    .ap-btn-primary {
        background: #1d1d1f;
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(29, 29, 31, 0.18);
    }
    .ap-btn-primary:hover {
        background: #000000;
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(29, 29, 31, 0.24);
        color: #ffffff;
    }
    .ap-btn-primary:active { transform: translateY(0); }
</style>

<div class="ap-shell">
    <a href="<?= site_url('academic/programs') ?>" class="ap-back">
        <i class="bi bi-chevron-left"></i> Programs
    </a>

    <div class="ap-header">
        <div class="ap-eyebrow"><?= ($program) ? 'Edit' : 'New' ?></div>
        <h1 class="ap-title"><?= ($program) ? 'Edit Program' : 'Create a Program' ?></h1>
        <p class="ap-subtitle">A few essentials. Nothing more.</p>
    </div>

    <div class="ap-card">
        <form action="<?= ($program) ? site_url('academic/edit_program/' . $program->id) : site_url('academic/create_program') ?>" method="post" autocomplete="off">

            <div class="ap-row">
                <div class="ap-field">
                    <label class="ap-label" for="ap-code">Code</label>
                    <input id="ap-code" type="text" class="ap-input" name="code"
                           value="<?= ($program) ? htmlspecialchars($program->code) : '' ?>"
                           required placeholder="BSIT">
                </div>
                <div class="ap-field">
                    <label class="ap-label" for="ap-name">Name</label>
                    <input id="ap-name" type="text" class="ap-input" name="name"
                           value="<?= ($program) ? htmlspecialchars($program->name) : '' ?>"
                           required placeholder="Bachelor of Science in Information Technology">
                </div>
            </div>

            <div class="ap-field">
                <label class="ap-label" for="ap-type">Type</label>
                <select id="ap-type" class="ap-select" name="type" required>
                    <option value="program" <?= ($program && (isset($program->type) && $program->type == 'program' || !isset($program->type))) ? 'selected' : '' ?>>Program (CHED)</option>
                    <option value="grade_level" <?= ($program && isset($program->type) && $program->type == 'grade_level') ? 'selected' : '' ?>>Grade Level (DepEd)</option>
                </select>
            </div>

            <div class="ap-field">
                <label class="ap-label" for="ap-description">
                    Description
                    <span class="ap-optional">Optional</span>
                </label>
                <textarea id="ap-description" class="ap-textarea" name="description" rows="3"
                          placeholder="A short summary of this program."><?= ($program) ? htmlspecialchars($program->description) : '' ?></textarea>
            </div>

            <div class="ap-divider"></div>

            <div class="ap-actions">
                <a href="<?= site_url('academic/programs') ?>" class="ap-btn ap-btn-ghost">Cancel</a>
                <button type="submit" class="ap-btn ap-btn-primary">
                    <?= ($program) ? 'Save Changes' : 'Create Program' ?>
                </button>
            </div>
        </form>
    </div>
</div>
