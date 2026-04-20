<div class="row g-4">
    <?php foreach ($tracks as $track): ?>
    <div class="col-lg-6">
        <div class="data-table h-100">
            <div class="table-header">
                <h5><i class="bi bi-signpost-split-fill me-2"></i><?= $track->name ?> (<?= $track->code ?>)</h5>
            </div>
            <div class="p-3">
                <?php $track_strands = array_filter($strands, function($s) use ($track) { return $s->track_id == $track->id; }); ?>
                <?php if (!empty($track_strands)): ?>
                    <?php foreach ($track_strands as $strand): ?>
                        <div class="d-flex align-items-center gap-2 py-2 px-2" style="font-size:0.88rem;border-bottom:1px solid #f1f5f9;">
                            <span class="badge-role badge-admin" style="min-width:55px;text-align:center;"><?= $strand->code ?></span>
                            <span><?= $strand->name ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center py-3" style="color:#94a3b8;">No strands.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
