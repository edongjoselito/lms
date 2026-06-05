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
                <div class="ps-hero-avatar">T</div>
                <div class="ps-hero-info">
                    <div class="ps-hero-meta">
                        <span class="ps-tag ps-tag-degree">Support</span>
                        <span class="ps-tag ps-tag-code">Teacher</span>
                    </div>
                    <h1 class="ps-hero-title">Teacher Guide</h1>
                    <p class="ps-hero-desc">Guide for instructors managing course content, assessments, and student progress.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout ps-layout-full">
        <div class="ps-card">
            <div class="ps-card-head">
                <div class="ps-card-title">
                    <i class="bi bi-person-workspace"></i>
                    <span>Teacher Guide</span>
                </div>
            </div>

            <div class="ps-card-body">
                <div class="help-content">
                    <div class="help-subsection">
                        <h3><i class="bi bi-1-circle-fill"></i> Dashboard Overview</h3>
                        <ul>
                            <li><strong>My Subjects:</strong> View all subjects assigned to you as an instructor.</li>
                            <li><strong>My Sections:</strong> See sections where you are assigned as an adviser.</li>
                            <li><strong>Quick Access:</strong> Click on any subject to access course content immediately.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-2-circle-fill"></i> Managing Course Content</h3>
                        <ul>
                            <li><strong>Accessing Content:</strong> Navigate to Course → Teacher Subjects or click on a subject from your dashboard.</li>
                            <li><strong>Creating Modules:</strong> Add modules to organize your lessons by topic, unit, or week.</li>
                            <li><strong>Adding Lessons:</strong> Create lessons within modules with text, video, file, or link content.</li>
                            <li><strong>Publishing Content:</strong> Toggle the publish status to make lessons visible to students. Hidden lessons are only visible to you.</li>
                            <li><strong>Reordering:</strong> Drag and drop to reorder modules and lessons within your course structure.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-3-circle-fill"></i> Lesson Planning</h3>
                        <ul>
                            <li><strong>ILAW Lesson Plans:</strong> Click "Lesson Plan (ILAW)" on any lesson to create a detailed lesson plan.</li>
                            <li><strong>Template Sections:</strong> Fill in Objectives, Subject Matter, Materials, Procedures, Evaluation, Assignment, and Remarks.</li>
                            <li><strong>Editing Plans:</strong> Update lesson plans anytime as your teaching approach evolves.</li>
                            <li><strong>Marking as Taught:</strong> Use the "Mark as Taught" feature to track when lessons have been delivered.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-4-circle-fill"></i> Creating Assessments</h3>
                        <ul>
                            <li><strong>Adding Quizzes:</strong> Create quiz activities within modules to assess student understanding.</li>
                            <li><strong>Import Questions:</strong> Use the "Add Assessment" feature to import questions from Moodle XML or GIFT format.</li>
                            <li><strong>Question Types:</strong> Support for multiple choice, true/false, identification, fill-in-the-blanks, and essay questions.</li>
                            <li><strong>Quiz Settings:</strong> Set time limits, passing scores, and configure whether students can see results.</li>
                            <li><strong>Publishing Quizzes:</strong> Publish quizzes when ready for students to take. Hide while editing.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-5-circle-fill"></i> Monitoring Student Progress</h3>
                        <ul>
                            <li><strong>Section Progress:</strong> View detailed progress reports for your assigned sections.</li>
                            <li><strong>Lesson Completion:</strong> See which students have completed each lesson.</li>
                            <li><strong>Quiz Results:</strong> Review student quiz scores and attempts.</li>
                            <li><strong>Activity Tracking:</strong> Monitor student engagement with course materials.</li>
                        </ul>
                    </div>

                    <div class="help-subsection">
                        <h3><i class="bi bi-6-circle-fill"></i> Grade Management</h3>
                        <ul>
                            <li><strong>Grade Components:</strong> Set up grade components (quizzes, exams, activities) with weight percentages.</li>
                            <li><strong>Encoding Grades:</strong> Enter student grades for each component.</li>
                            <li><strong>Grade Reports:</strong> Generate and view student grade reports.</li>
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
    box-shadow: 0 4px 24px rgba(5,150,105,0.16);
}

.ps-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #064e3b 0%, #065f46 52%, #059669 100%);
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
    color: #059669;
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
    color: #059669;
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
