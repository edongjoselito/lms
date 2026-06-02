<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-lock-fill" style="font-size: 3rem; color: #2563eb;"></i>
                        </div>
                        <h4 class="card-title mb-2">Quiz Password Required</h4>
                        <p class="text-muted mb-0"><?= htmlspecialchars($quiz->title) ?></p>
                    </div>
                    
                    <form method="post" action="<?= site_url('course/start_assessment/' . $quiz->id) ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <div class="mb-4">
                            <label for="quiz_password" class="form-label">Enter Password</label>
                            <input type="password" class="form-control form-control-lg" id="quiz_password" name="quiz_password" required autofocus>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-unlock me-2"></i>Start Quiz
                            </button>
                            <a href="<?= site_url('course/assessment/' . $activity->id) ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
