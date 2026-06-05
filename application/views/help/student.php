<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="ps-page">
    <a href="<?= site_url('help') ?>" class="ps-back">
        <i class="bi bi-arrow-left-short"></i>
        Back to Help
    </a>

    <div class="ps-hero">
        <div class="ps-hero-bg"></div>
        <div class="ps-hero-content">
            <div class="ps-hero-left">
                <div class="ps-hero-avatar">S</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Support</span>
                        <span class="ps-tag ps-tag-code">Student</span>
                    </div>
                    <h1 class="ps-hero-title">Student Guide</h1>
                    <p class="ps-hero-desc">Guide for students accessing course materials, completing assessments, and tracking progress.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Student Guide</span>
                </div>
            </div>

            <div class="ps-card-body">
                <div class="help-content">
                    <div class="help-subsection">
                        <h3><i class="bi bi-1-circle-fill"></i> Getting Started</h3>
                        <ul>
                            <li><strong>Dashboard:</strong> Your dashboard shows enrolled subjects, progress, and upcoming activities.</li>
                            <li><strong>My Subjects:</strong> Access all your enrolled subjects from the sidebar or dashboard.</li>
                            <li><strong>Navigation:</strong> Use the sidebar to navigate between subjects, grades, and profile settings.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-2-circle-fill"></i> Accessing Course Content</h3>
                        <ul>
                            <li><strong>Subject Content:</strong> Click on a subject to view its modules and lessons.</li>
                            <li><strong>Sequential Access:</strong> Lessons may require completion of previous lessons before access (depending on teacher settings).</li>
                            <li><strong>Lesson Types:</strong> View different lesson content types:
                                <ul>
                                    <li><em>Text:</em> Read lesson materials with rich formatting</li>
                                    <li><em>Video:</em> Watch embedded video lessons</li>
                                    <li><em>File:</em> Download and view attached documents</li>
                                    <li><em>Link:</em> Visit external resources</li>
                                </ul>
                            </li>
                            <li><strong>Marking Complete:</strong> Mark lessons as complete after studying to track your progress.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-3-circle-fill"></i> Taking Quizzes</h3>
                        <ul>
                            <li><strong>Quiz Access:</strong> Quizzes appear in module content. Click to start when ready.</li>
                            <li><strong>Quiz Rules:</strong> Read quiz instructions carefully including time limits and attempt restrictions.</li>
                            <li><strong>Answering Questions:</strong>
                                <ul>
                                    <li>Multiple Choice: Select the correct answer</li>
                                    <li>True/False: Choose True or False</li>
                                    <li>Identification: Type your answer (case-insensitive)</li>
                                    <li>Fill-in-the-Blanks: Complete the missing words</li>
                                    <li>Essay: Write detailed responses</li>
                                </ul>
                            </li>
                            <li><strong>Submitting:</strong> Submit your quiz when finished. Ensure all questions are answered.</li>
                            <li><strong>Results:</strong> View your score and results after submission (if enabled by teacher).</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-4-circle-fill"></i> Tracking Progress</h3>
                        <ul>
                            <li><strong>Progress Bar:</strong> See your overall progress for each subject on the subject page.</li>
                            <li><strong>Completed Lessons:</strong> View which lessons you've completed in each module.</li>
                            <li><strong>Quiz Scores:</strong> Review your quiz scores and attempts in the subject content area.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-5-circle-fill"></i> Viewing Grades</h3>
                        <ul>
                            <li><strong>Grade Report:</strong> Access your grades from the sidebar under Grades.</li>
                            <li><strong>Grade Components:</strong> See how different activities (quizzes, exams) contribute to your final grade.</li>
                            <li><strong>Performance:</strong> Track your academic performance across all subjects.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-6-circle-fill"></i> Profile Settings</h3>
                        <ul>
                            <li><strong>Personal Information:</strong> Update your profile details including contact information.</li>
                            <li><strong>Change Password:</strong> Change your password regularly for security.</li>
                            <li><strong>Avatar:</strong> Upload a profile picture (if enabled by your school).</li>
                        </ul>
                    </div>
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

.ps-back {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    color: #2563eb;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 1.5rem;
    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}

.ps-back:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
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
    font-size: 1.8rem;
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

.help-content {
    padding-left: 0;
}

.help-subsection {
    margin-bottom: 1.5rem;
}

.help-subsection:last-child {
    margin-bottom: 0;
}

.help-subsection h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #334155;
    margin: 0 0 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.help-subsection h3 i {
    color: #2563eb;
    font-size: 1.1rem;
}

.help-subsection ul {
    margin: 0;
    padding-left: 1.5rem;
    list-style-type: disc;
}

.help-subsection li {
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.help-subsection li strong {
    color: #1e293b;
    font-weight: 600;
}

.help-subsection li em {
    color: #64748b;
    font-style: italic;
}

.help-subsection ul ul {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    padding-left: 1.25rem;
}

.help-subsection ul ul li {
    font-size: 0.85rem;
    list-style-type: circle;
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
}
</style>
