<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-3">
            <a href="<?= site_url('users') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Users
            </a>
        </div>

        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-<?= ($user) ? 'pencil-square' : 'person-plus-fill' ?> me-2" style="color:#6366f1;"></i>
                <?= ($user) ? 'Edit User' : 'Add New User' ?>
            </h5>

            <form action="<?= ($user) ? site_url('users/edit/' . $user->id) : site_url('users/create') ?>" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name"
                               value="<?= ($user) ? htmlspecialchars($user->first_name) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name"
                               value="<?= ($user) ? htmlspecialchars($user->last_name) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email"
                               value="<?= ($user) ? htmlspecialchars($user->email) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <?= ($user) ? '<small style="color:#94a3b8;font-weight:400;">(leave blank to keep current)</small>' : '' ?></label>
                        <input type="password" class="form-control" name="password"
                               <?= ($user) ? '' : 'required' ?> minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="user" <?= ($user && $user->role == 'user') ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= ($user && $user->role == 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch"
                                   <?= (!$user || $user->status) ? 'checked' : '' ?> style="cursor:pointer;">
                            <label class="form-check-label" for="statusSwitch" style="cursor:pointer;font-weight:400;font-size:0.9rem;">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid #e2e8f0;padding-top:1.5rem;">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check-lg"></i>
                        <?= ($user) ? 'Update User' : 'Create User' ?>
                    </button>
                    <a href="<?= site_url('users') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
