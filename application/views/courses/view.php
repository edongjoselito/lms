<div class="mb-3">
    <a href="<?= site_url('courses') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Back to Courses
    </a>
</div>

<!-- Course Header -->
<div class="p-4 mb-4" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <?php if ($course->code): ?>
                <span style="background:#6366f115;color:#6366f1;padding:0.25rem 0.75rem;border-radius:8px;font-size:0.75rem;font-weight:600;"><?= htmlspecialchars($course->code) ?></span>
            <?php endif; ?>
            <h4 style="font-weight:700;margin:0.5rem 0 0.25rem 0;"><?= htmlspecialchars($course->title) ?></h4>
            <?php if ($course->description): ?>
                <p style="color:#64748b;font-size:0.9rem;margin:0;line-height:1.6;"><?= nl2br(htmlspecialchars($course->description)) ?></p>
            <?php endif; ?>
            <div style="font-size:0.8rem;color:#94a3b8;margin-top:0.75rem;">
                <span><i class="bi bi-person me-1"></i><?= $course->creator_name ?></span>
                <span class="ms-3"><i class="bi bi-people me-1"></i><?= $student_count ?> student<?= $student_count != 1 ? 's' : '' ?></span>
                <?php if ($course->category): ?>
                    <span class="ms-3"><i class="bi bi-tag me-1"></i><?= htmlspecialchars($course->category) ?></span>
                <?php endif; ?>
                <?php if (!$course->is_published): ?>
                    <span class="badge-status badge-inactive ms-2">Draft</span>
                <?php endif; ?>
            </div>
            <?php if ($is_student_view): ?>
            <div style="margin-top:1rem;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span style="font-size:0.8rem;font-weight:600;color:#334155;">Course Progress</span>
                    <span style="font-size:0.8rem;font-weight:700;color:<?= $progress_pct >= 100 ? '#10b981' : '#6366f1' ?>;"><?= $progress_pct ?>%</span>
                </div>
                <div style="height:10px;background:#e2e8f0;border-radius:8px;overflow:hidden;">
                    <div style="height:100%;width:<?= $progress_pct ?>%;background:<?= $progress_pct >= 100 ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#6366f1,#8b5cf6)' ?>;border-radius:8px;transition:width 0.5s;"></div>
                </div>
                <?php if ($progress_pct >= 100): ?>
                    <div style="font-size:0.78rem;color:#10b981;font-weight:600;margin-top:0.5rem;"><i class="bi bi-trophy-fill me-1"></i>Course Completed!</div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($is_student_view && !$is_enrolled): ?>
        <div>
            <form action="<?= site_url('courses/self_enroll/' . $course->id) ?>" method="post">
                <div style="margin-top:1rem;">
                    <label style="font-size:0.85rem;font-weight:600;color:#334155;">Section (if applicable)</label>
                    <select class="form-control" name="section_id" style="border-radius:10px;margin-top:0.25rem;">
                        <option value="">Select your section</option>
                        <?php if (isset($sections)): ?>
                            <?php foreach ($sections as $s): ?>
                                <option value="<?= $s->id ?>"><?= htmlspecialchars($s->name) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div style="margin-top:1rem;">
                    <label style="font-size:0.85rem;font-weight:600;color:#334155;">Enrollment Key</label>
                    <input type="text" class="form-control" name="enrollment_key" placeholder="Enter the enrollment key" style="border-radius:10px;margin-top:0.25rem;" required>
                </div>
                <button type="submit" class="btn-primary-custom mt-2" style="border-radius:10px;">
                    <i class="bi bi-person-plus me-1"></i> Enroll in Course
                </button>
            </form>
        </div>
        <?php elseif ($is_student_view && $is_enrolled && !$can_edit): ?>
        <div>
            <a href="<?= site_url('courses/unenroll/' . $course->id) ?>" class="btn btn-light btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;padding:0.6rem 1.25rem;color:#ef4444;" onclick="return confirm('Are you sure you want to unenroll from this course? Your progress will be lost.');">
                <i class="bi bi-person-dash me-1"></i> Unenroll
            </a>
        </div>
        <?php endif; ?>
        <?php if ($can_edit): ?>
        <div class="d-flex gap-2">
            <a href="<?= site_url('courses/student_progress/' . $course->id) ?>" class="btn btn-light btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;">
                <i class="bi bi-graph-up"></i> Progress
            </a>
            <a href="<?= site_url('courses/participants/' . $course->id) ?>" class="btn btn-light btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;">
                <i class="bi bi-people"></i> Participants
            </a>
            <a href="<?= site_url('courses/collaborators/' . $course->id) ?>" class="btn btn-light btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;">
                <i class="bi bi-person-workspace"></i> Collaborators
            </a>
            <a href="<?= site_url('attendance?course_id=' . $course->id) ?>" class="btn btn-light btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;">
                <i class="bi bi-calendar-check-fill"></i> Attendance
            </a>
            <a href="<?= site_url('courses/edit/' . $course->id) ?>" class="btn btn-light btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="<?= site_url('courses/delete/' . $course->id) ?>" class="btn btn-light btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;color:#ef4444;" onclick="return confirm('Delete this course and all its content?');">
                <i class="bi bi-trash"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!$is_student_view || $is_enrolled): ?>
<div class="row g-4">
    <!-- Main Content: Modules & Lessons -->
    <div class="col-lg-8">
        <?php if ($can_edit): ?>
        <div class="d-flex gap-2 mb-3">
            <a href="<?= site_url('lessons/create_module/' . $course->id) ?>" class="btn-primary-custom btn-sm">
                <i class="bi bi-folder-plus me-1"></i> Add Module
            </a>
            <a href="<?= site_url('quizzes/create/' . $course->id) ?>" class="btn btn-outline-primary btn-sm" style="border-radius:10px;font-size:0.8rem;font-weight:500;">
                <i class="bi bi-clipboard-plus me-1"></i> Add Assessment
            </a>
        </div>
        <?php endif; ?>

        <?php if (empty($modules) && empty($quizzes)): ?>
        <div class="text-center py-5" style="background:white;border-radius:16px;border:1px solid #e2e8f0;">
            <i class="bi bi-journal-text" style="font-size:3rem;color:#cbd5e1;"></i>
            <p style="color:#94a3b8;margin-top:0.75rem;">No content yet.<?= $can_edit ? ' Add modules and lessons to get started.' : '' ?></p>
        </div>
        <?php endif; ?>

        <?php foreach ($modules as $i => $module): ?>
        <div class="mb-3" style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="d-flex justify-content-between align-items-center p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#module_<?= $module->id ?>">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.85rem;">
                        <?= $i + 1 ?>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:0.95rem;"><?= htmlspecialchars($module->title) ?></div>
                        <div style="color:#94a3b8;font-size:0.78rem;">
                            <?= count($module->lessons) ?> lesson<?= count($module->lessons) != 1 ? 's' : '' ?>
                            <?php if ($module->description): ?>
                                &middot; <?= character_limiter(strip_tags($module->description), 50) ?>
                            <?php endif; ?>
                            <?php if (!$module->is_published): ?>
                                <span class="badge-status badge-inactive ms-1">Draft</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <?php if ($can_edit): ?>
                    <a href="<?= site_url('lessons/create_lesson/' . $module->id) ?>" class="btn-action" title="Add Lesson" onclick="event.stopPropagation();">
                        <i class="bi bi-plus-circle"></i>
                    </a>
                    <a href="<?= site_url('lessons/edit_module/' . $module->id) ?>" class="btn-action" title="Edit" onclick="event.stopPropagation();">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= site_url('lessons/delete_module/' . $module->id) ?>" class="btn-action btn-delete" title="Delete" onclick="event.stopPropagation();return confirm('Delete this module and all its lessons?');">
                        <i class="bi bi-trash"></i>
                    </a>
                    <?php endif; ?>
                    <i class="bi bi-chevron-down" style="color:#94a3b8;"></i>
                </div>
            </div>
            <div class="collapse show" id="module_<?= $module->id ?>">
                <?php if (empty($module->lessons)): ?>
                    <div class="p-3 text-center" style="color:#94a3b8;font-size:0.85rem;">No lessons in this module.</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($module->lessons as $lesson):
                            $is_completed = $is_student_view && in_array($lesson->id, $completed_ids);
                            $is_locked = $is_student_view && isset($lesson_accessible[$lesson->id]) && !$lesson_accessible[$lesson->id];
                            $can_access = !$is_locked || $can_edit;
                        ?>
                        <?php if ($can_access): ?>
                        <a href="<?= site_url('lessons/view/' . $lesson->id) ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-4" style="border:none;border-bottom:1px solid #f1f5f9;">
                        <?php else: ?>
                        <div class="list-group-item d-flex align-items-center gap-3 py-3 px-4" style="border:none;border-bottom:1px solid #f1f5f9;opacity:0.5;cursor:not-allowed;">
                        <?php endif; ?>
                            <?php if ($is_completed): ?>
                            <div style="width:32px;height:32px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-check-circle-fill" style="color:#10b981;"></i>
                            </div>
                            <?php elseif ($is_locked): ?>
                            <div style="width:32px;height:32px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-lock-fill" style="color:#94a3b8;"></i>
                            </div>
                            <?php else: ?>
                            <div style="width:32px;height:32px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <?php
                                $icons = array('text' => 'bi-file-text', 'file' => 'bi-paperclip', 'video' => 'bi-play-circle', 'link' => 'bi-link-45deg');
                                $icon = isset($icons[$lesson->content_type]) ? $icons[$lesson->content_type] : 'bi-file-text';
                                ?>
                                <i class="bi <?= $icon ?>" style="color:#6366f1;"></i>
                            </div>
                            <?php endif; ?>
                            <div style="flex:1;">
                                <div style="font-weight:600;font-size:0.9rem;color:<?= $is_locked ? '#94a3b8' : '#0f172a' ?>;"><?= htmlspecialchars($lesson->title) ?></div>
                                <div style="font-size:0.75rem;color:#94a3b8;">
                                    <?= ucfirst($lesson->content_type) ?>
                                    <?= $lesson->duration_minutes ? ' &middot; ' . $lesson->duration_minutes . ' min' : '' ?>
                                    <?php if ($is_completed): ?>
                                        <span style="color:#10b981;font-weight:600;"> &middot; Completed</span>
                                    <?php elseif ($is_locked): ?>
                                        <span style="color:#94a3b8;font-weight:600;"> &middot; Locked</span>
                                    <?php endif; ?>
                                    <?php if (!$lesson->is_published): ?>
                                        <span class="badge-status badge-inactive ms-1">Draft</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($can_edit): ?>
                            <div class="d-flex gap-1" onclick="event.preventDefault();event.stopPropagation();">
                                <a href="<?= site_url('lessons/edit_lesson/' . $lesson->id) ?>" class="btn-action" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="<?= site_url('lessons/delete_lesson/' . $lesson->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this lesson?');"><i class="bi bi-trash"></i></a>
                            </div>
                            <?php endif; ?>
                        <?php if ($can_access): ?>
                        </a>
                        <?php else: ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Sidebar: Assessments -->
    <div class="col-lg-4">
        <div style="background:white;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">
            <div class="p-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h6 style="margin:0;font-weight:700;font-size:0.9rem;"><i class="bi bi-clipboard-check me-2" style="color:#6366f1;"></i>Assessments</h6>
            </div>
            <?php if (empty($quizzes)): ?>
                <div class="p-3 text-center" style="color:#94a3b8;font-size:0.85rem;">No assessments yet.</div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($quizzes as $q): ?>
                    <div class="list-group-item py-3 px-3" style="border:none;border-bottom:1px solid #f1f5f9;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <?php
                                $type_colors = array('quiz' => '#6366f1', 'exam' => '#ef4444', 'assignment' => '#f59e0b', 'activity' => '#10b981');
                                $color = isset($type_colors[$q->quiz_type]) ? $type_colors[$q->quiz_type] : '#6366f1';
                                ?>
                                <span style="background:<?= $color ?>15;color:<?= $color ?>;padding:0.15rem 0.5rem;border-radius:6px;font-size:0.68rem;font-weight:600;text-transform:uppercase;"><?= $q->quiz_type ?></span>
                                <div style="font-weight:600;font-size:0.85rem;margin-top:0.25rem;color:#0f172a;"><?= htmlspecialchars($q->title) ?></div>
                                <div style="font-size:0.72rem;color:#94a3b8;">
                                    <?= $q->total_points ?: 0 ?> pts
                                    <?php if ($q->time_limit_minutes): ?>&middot; <?= $q->time_limit_minutes ?> min<?php endif; ?>
                                    <?php if (!$q->is_published): ?><span class="badge-status badge-inactive ms-1">Draft</span><?php endif; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if ($can_edit): ?>
                                    <a href="<?= site_url('quizzes/questions/' . $q->id) ?>" class="btn-action" title="Questions"><i class="bi bi-list-ol"></i></a>
                                    <a href="<?= site_url('quizzes/attempts/' . $q->id) ?>" class="btn-action" title="Submissions"><i class="bi bi-people"></i></a>
                                    <a href="<?= site_url('quizzes/edit/' . $q->id) ?>" class="btn-action" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= site_url('quizzes/delete/' . $q->id) ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete?');"><i class="bi bi-trash"></i></a>
                                <?php else: ?>
                                    <?php if ($q->is_published): ?>
                                        <a href="<?= site_url('quizzes/take/' . $q->id) ?>" class="btn-primary-custom btn-sm" style="padding:0.3rem 0.7rem;font-size:0.75rem;"><i class="bi bi-pencil-square"></i> Take</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
