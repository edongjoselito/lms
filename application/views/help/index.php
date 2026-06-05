<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="ps-page">
    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">?</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Support</span>
                        <span class="ps-tag ps-tag-code">Guide</span>
                    </div>
                    <h1 class="ps-hero-title">User Guide</h1>
                    <p class="ps-hero-desc">Select your role to view step-by-step instructions for using the LMS platform.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-book"></i>
                    <span>Select Your Role</span>
                </div>
            </div>

            <div class="ps-card-body">
                <div class="help-menu">
                    <?php if (empty($role_slug) || $role_slug === 'super_admin' || $role_slug === 'school_admin'): ?>
                    <!-- School Admin Card -->
                    <a href="<?= site_url('help/school_admin') ?>" class="help-menu-card">
                        <div class="help-menu-icon help-icon-admin">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div class="help-menu-content">
                            <h3>School Administrator</h3>
                            <p>Complete guide for managing your school's LMS platform, users, and academic structure.</p>
                            <div class="help-menu-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                    <?php endif; ?>

                    <?php if (empty($role_slug) || $role_slug === 'super_admin' || $role_slug === 'teacher'): ?>
                    <!-- Teacher Card -->
                    <a href="<?= site_url('help/teacher') ?>" class="help-menu-card">
                        <div class="help-menu-icon help-icon-teacher">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="help-menu-content">
                            <h3>Teacher</h3>
                            <p>Guide for instructors managing course content, assessments, and student progress.</p>
                            <div class="help-menu-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                    <?php endif; ?>

                    <?php if (empty($role_slug) || $role_slug === 'super_admin' || $role_slug === 'student'): ?>
                    <!-- Student Card -->
                    <a href="<?= site_url('help/student') ?>" class="help-menu-card">
                        <div class="help-menu-icon help-icon-student">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div class="help-menu-content">
                            <h3>Student</h3>
                            <p>Guide for students accessing course materials, completing assessments, and tracking progress.</p>
                            <div class="help-menu-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ps-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    padding: 1.25rem 0;
    max-width: 100%;
}

.ps-hero {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 24px rgba(37,99,235,0.16);
}

.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d2453 0%, #13367a 52%, #2563eb 100%);
}

.ps-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.ps-hero-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 2rem 2.25rem;
    flex-wrap: wrap;
}

.ps-hero-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
    min-width: 0;
}

.ps-hero-avatar {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.3);
    color: #fff;
    font-size: 2rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ps-hero-info {
    min-width: 0;
}

.ps-hero-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.ps-tag {
    display: inline-block;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.ps-tag-degree {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.ps-tag-code {
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.9);
    border: 1px solid rgba(255,255,255,0.25);
}

.ps-hero-title {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.3rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.ps-hero-desc {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.72);
    margin: 0;
    line-height: 1.5;
    max-width: 560px;
}

.ps-layout {
    display: grid;
    gap: 1.5rem;
    align-items: start;
}

.ps-layout-full {
    grid-template-columns: 1fr;
}

.ps-card {
    background: #fff;
    border: 1px solid #eaecf0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}

.ps-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    flex-wrap: wrap;
}

.ps-card-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.ps-card-title i {
    color: #2563eb;
    font-size: 1rem;
}

.ps-card-body {
    padding: 1.5rem;
}

.help-menu {
    display: grid;
    gap: 1rem;
}

.help-menu-card {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 1.25rem 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #fff;
    transition: all 0.2s;
    text-decoration: none;
}

.help-menu-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 16px rgba(59,130,246,0.12);
    transform: translateY(-2px);
}

.help-menu-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.help-icon-admin {
    background: linear-gradient(135deg, #dc2626, #ef4444);
}

.help-icon-teacher {
    background: linear-gradient(135deg, #059669, #10b981);
}

.help-icon-student {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
}

.help-menu-content {
    flex: 1;
    min-width: 0;
}

.help-menu-content h3 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.35rem;
}

.help-menu-content p {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.help-menu-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 1rem;
    transition: all 0.2s;
    flex-shrink: 0;
}

.help-menu-card:hover .help-menu-arrow {
    background: #3b82f6;
    color: #fff;
}

@media (max-width: 768px) {
    .ps-hero-content {
        padding: 1.5rem;
    }

    .ps-hero-left {
        align-items: flex-start;
    }

    .ps-hero-avatar {
        width: 58px;
        height: 58px;
        font-size: 1.5rem;
    }

    .ps-card-body {
        padding: 1rem;
    }

    .help-menu-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .help-menu-arrow {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
    }
}
</style>
