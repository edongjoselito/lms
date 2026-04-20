<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="mb-3">
            <a href="<?= site_url('enrollment/students') ?>" style="color:#6366f1;text-decoration:none;font-size:0.9rem;font-weight:500;">
                <i class="bi bi-arrow-left me-1"></i> Back to Students
            </a>
        </div>
        <div class="form-card">
            <h5 style="font-weight:700;margin-bottom:1.5rem;">
                <i class="bi bi-person-badge-fill me-2" style="color:#6366f1;"></i>Register Student
            </h5>
            <form action="<?= site_url('enrollment/create_student') ?>" method="post">
                <h6 style="font-weight:600;color:#6366f1;font-size:0.85rem;margin-bottom:0.75rem;">Personal Information</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Suffix</label>
                        <input type="text" class="form-control" name="suffix" placeholder="Jr.">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="">-- Select --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Birthdate</label>
                        <input type="date" class="form-control" name="birthdate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Default: student123">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Guardian Name</label>
                        <input type="text" class="form-control" name="guardian_name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Guardian Contact</label>
                        <input type="text" class="form-control" name="guardian_contact">
                    </div>
                </div>

                <h6 style="font-weight:600;color:#6366f1;font-size:0.85rem;margin-bottom:0.75rem;">Academic Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">System Type</label>
                        <select class="form-select" name="system_type" id="studSysType" required>
                            <option value="deped">DepEd (K-12)</option>
                            <option value="ched">CHED (Higher Ed)</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="studLrnField">
                        <label class="form-label">LRN</label>
                        <input type="text" class="form-control" name="lrn" maxlength="20">
                    </div>
                    <div class="col-md-4" id="studIdField">
                        <label class="form-label">Student ID</label>
                        <input type="text" class="form-control" name="student_id" maxlength="30">
                    </div>
                    <div class="col-md-4" id="studGlField">
                        <label class="form-label">Grade Level</label>
                        <select class="form-select" name="grade_level_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($grade_levels as $gl): ?>
                                <option value="<?= $gl->id ?>"><?= $gl->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4" id="studStrandField">
                        <label class="form-label">SHS Strand</label>
                        <select class="form-select" name="strand_id">
                            <option value="">-- N/A --</option>
                            <?php foreach ($strands as $st): ?>
                                <option value="<?= $st->id ?>"><?= $st->code ?> - <?= $st->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4" id="studProgField">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($programs as $pr): ?>
                                <option value="<?= $pr->id ?>"><?= $pr->code ?> - <?= $pr->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="studYlField">
                        <label class="form-label">Year Level</label>
                        <input type="number" class="form-control" name="year_level" min="1" max="6">
                    </div>
                </div>
                <div class="mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg"></i> Register</button>
                    <a href="<?= site_url('enrollment/students') ?>" class="btn btn-light" style="border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.6rem 1.25rem;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('studSysType').addEventListener('change', function() {
    var v = this.value;
    ['studLrnField','studGlField','studStrandField'].forEach(function(id) { document.getElementById(id).style.display = (v === 'deped') ? '' : 'none'; });
    ['studIdField','studProgField','studYlField'].forEach(function(id) { document.getElementById(id).style.display = (v === 'ched') ? '' : 'none'; });
});
document.getElementById('studSysType').dispatchEvent(new Event('change'));
</script>
