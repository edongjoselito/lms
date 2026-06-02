<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Pending School Approvals</h2>
            <p class="text-muted mb-0">Review and approve school registration requests</p>
        </div>
        <a href="<?= site_url('schools') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Schools
        </a>
    </div>

    <?php if (empty($pending_schools)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No Pending Approvals</h5>
                <p class="text-muted">All school registrations have been processed.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>School ID</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Location</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_schools as $school): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($school->name) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($school->school_id_number) ?></td>
                                    <td>
                                        <?php
                                        $type_labels = array(
                                            'deped' => 'DepEd (K-12)',
                                            'ched' => 'CHED (Higher Ed)',
                                            'tesda' => 'TESDA (Tech-Voc)',
                                            'both' => 'All',
                                            'basic' => 'Basic',
                                            'college' => 'College',
                                            'tech_voc' => 'Tech-Voc'
                                        );
                                        echo isset($type_labels[$school->type]) ? $type_labels[$school->type] : $school->type;
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($school->email) ?></td>
                                    <td><?= htmlspecialchars($school->contact_number ?: '-') ?></td>
                                    <td>
                                        <?= htmlspecialchars($school->division ?: '-') ?>,
                                        <?= htmlspecialchars($school->region ?: '-') ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($school->created_at)) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('schools/approve_school/' . $school->id) ?>" 
                                               class="btn btn-success"
                                               onclick="return confirm('Are you sure you want to approve this school? This will create a school admin account.')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </a>
                                            <a href="<?= site_url('schools/reject_school/' . $school->id) ?>" 
                                               class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to reject this school? This will permanently delete the school record.')">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
