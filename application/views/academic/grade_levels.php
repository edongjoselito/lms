<div class="data-table">
    <div class="table-header">
        <h5><i class="bi bi-list-ol me-2"></i>K-12 Grade Levels</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Order</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grade_levels as $gl): ?>
                    <tr>
                        <td><span class="badge-role badge-user"><?= $gl->code ?></span></td>
                        <td style="font-weight:600;"><?= $gl->name ?></td>
                        <td style="color:#64748b;"><?= ucfirst(str_replace('_', ' ', $gl->category)) ?></td>
                        <td style="color:#64748b;"><?= $gl->level_order ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
