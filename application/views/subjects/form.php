<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="sf-page">
    <a href="<?= site_url('subjects') ?>" class="sf-back">
        <i class="bi bi-arrow-left"></i> Back
    </a>

    <h1 class="sf-title"><?= ($subject) ? 'Edit Subject' : 'New Subject' ?></h1>

    <div class="sf-card">
        <form action="<?= ($subject) ? site_url('subjects/edit/' . $subject->id) : site_url('subjects/create') ?>" method="post">
            <div class="sf-grid">
                <div class="sf-col sf-col--1">
                    <label class="sf-label">Subject Code</label>
                    <input type="text" class="sf-input" name="code"
                        value="<?= ($subject) ? htmlspecialchars($subject->code) : '' ?>" required
                        placeholder="e.g. CS101">
                </div>

                <div class="sf-col sf-col--3" id="semesterWrapper">
                    <label class="sf-label">Semester</label>
                    <select class="sf-select" name="semester_type" id="semesterSelect">
                        <option value="">Select Semester</option>
                        <option value="1st_sem" <?= ($subject && $subject->semester_type == '1st_sem') ? 'selected' : '' ?>>First Semester</option>
                        <option value="2nd_sem" <?= ($subject && $subject->semester_type == '2nd_sem') ? 'selected' : '' ?>>Second Semester</option>
                    </select>
                </div>

                <div class="sf-col sf-col--2">
                    <label class="sf-label">Description</label>
                    <input type="text" class="sf-input" name="description"
                        value="<?= ($subject) ? htmlspecialchars($subject->description) : '' ?>"
                        placeholder="What is this subject about?">
                </div>

                <div class="sf-col sf-col--2">
                    <label class="sf-label">Program <span class="sf-optional">College</span></label>
                    <select class="sf-select" name="program_id" id="programSelect">
                        <option value="">Select Program</option>
                        <?php foreach ($programs as $p): ?>
                            <option value="<?= $p->id ?>" <?= ($subject && $subject->program_id == $p->id) ? 'selected' : '' ?>>
                                <?= $p->code ?> &mdash; <?= $p->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="sf-col sf-col--2">
                    <label class="sf-label">Grade Level <span class="sf-optional">K-12</span></label>
                    <select class="sf-select" name="grade_level_id" id="gradeLevelSelect">
                        <option value="">Select Grade Level</option>
                        <?php foreach ($grade_levels as $gl): ?>
                            <option value="<?= $gl->id ?>" <?= ($subject && $subject->grade_level_id == $gl->id) ? 'selected' : '' ?>>
                                <?= $gl->code ?> &mdash; <?= $gl->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="sf-actions">
                <a href="<?= site_url('subjects') ?>" class="sf-btn sf-btn--secondary">Cancel</a>
                <button type="submit" class="sf-btn sf-btn--primary">
                    <?= ($subject) ? 'Save Changes' : 'Create Subject' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* ── Apple-Level Design System ──────────────────────────────────── */
    .sf-page {
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
        padding: 2rem;
        color: #1d1d1f;
    }

    .sf-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #3b82f6;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        padding: 8px 14px 8px 10px;
        border-radius: 10px;
        background: rgba(59, 130, 246, 0.08);
        transition: all 0.2s ease;
    }

    .sf-back:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #2563eb;
    }

    .sf-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1d1d1f;
        margin: 0 0 1.5rem 0;
        letter-spacing: -0.02em;
    }

    .sf-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
    }

    .sf-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .sf-col {
        display: flex;
        flex-direction: column;
    }

    .sf-col--1 {
        grid-column: span 1;
    }

    .sf-col--2 {
        grid-column: span 2;
    }

    .sf-col--3 {
        grid-column: span 3;
    }

    .sf-col--4 {
        grid-column: span 4;
    }

    .sf-col--5 {
        grid-column: span 5;
    }

    .sf-col--6 {
        grid-column: span 6;
    }

    .sf-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 0.5rem;
    }

    .sf-optional {
        font-weight: 400;
        color: #86868b;
    }

    .sf-input,
    .sf-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        font-size: 0.875rem;
        color: #1d1d1f;
        background: #fff;
        transition: all 0.2s ease;
    }

    .sf-input:focus,
    .sf-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .sf-input::placeholder {
        color: #9ca3af;
    }

    .sf-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2386868b' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2.25rem;
    }

    .sf-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        margin-top: 1rem;
    }

    .sf-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
    }

    .sf-btn--primary {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: #fff;
    }

    .sf-btn--primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .sf-btn--secondary {
        background: #f5f5f7;
        color: #1d1d1f;
        border: 1px solid #d2d2d7;
    }

    .sf-btn--secondary:hover {
        background: #fff;
        border-color: #86868b;
    }

    @media (max-width: 1024px) {
        .sf-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .sf-col--1,
        .sf-col--2,
        .sf-col--3 {
            grid-column: span 2;
        }

        .sf-col--4,
        .sf-col--5,
        .sf-col--6 {
            grid-column: span 4;
        }
    }

    @media (max-width: 640px) {
        .sf-page {
            padding: 1rem;
        }

        .sf-grid {
            grid-template-columns: 1fr;
        }

        .sf-col--1,
        .sf-col--2,
        .sf-col--3,
        .sf-col--4,
        .sf-col--5,
        .sf-col--6 {
            grid-column: span 1;
        }

        .sf-actions {
            flex-direction: column-reverse;
        }

        .sf-btn {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const programSelect = document.getElementById('programSelect');
        const gradeLevelSelect = document.getElementById('gradeLevelSelect');
        const semesterSelect = document.getElementById('semesterSelect');
        const semesterWrapper = document.getElementById('semesterWrapper');
        const semesterLabelText = document.getElementById('semesterLabelText');

        function toggleSemester() {
            if (programSelect.value) {
                semesterWrapper.style.display = '';
                semesterLabelText.textContent = 'Semester';
                semesterSelect.required = true;
            } else if (gradeLevelSelect.value) {
                semesterWrapper.style.display = 'none';
                semesterSelect.required = false;
                semesterSelect.value = '';
            } else {
                semesterWrapper.style.display = '';
                semesterLabelText.textContent = 'Semester (Optional)';
                semesterSelect.required = false;
            }
        }

        programSelect.addEventListener('change', function() {
            if (this.value) gradeLevelSelect.value = '';
            toggleSemester();
        });

        gradeLevelSelect.addEventListener('change', function() {
            if (this.value) programSelect.value = '';
            toggleSemester();
        });

        toggleSemester();
    });
</script>