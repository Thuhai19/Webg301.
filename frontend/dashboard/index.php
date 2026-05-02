<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
?>
<h1 class="h3 mb-4">Dashboard</h1>
<p class="text-muted mb-4">Choose a management section from the menu or cards below.</p>
<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-primary">
            <div class="card-body">
                <h2 class="h5 card-title">Players</h2>
                <p class="card-text small text-muted">Manage players</p>
                <a href="<?php echo e(base_url()); ?>index.php?controller=player&action=index" class="btn btn-primary btn-sm">Open</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-success">
            <div class="card-body">
                <h2 class="h5 card-title">Characters</h2>
                <p class="card-text small text-muted">RPG characters</p>
                <a href="<?php echo e(base_url()); ?>index.php?controller=character&action=index" class="btn btn-success btn-sm">Open</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-warning">
            <div class="card-body">
                <h2 class="h5 card-title">Skills</h2>
                <p class="card-text small text-muted">Skills</p>
                <a href="<?php echo e(base_url()); ?>index.php?controller=skill&action=index" class="btn btn-warning btn-sm text-dark">Open</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-info">
            <div class="card-body">
                <h2 class="h5 card-title">Items</h2>
                <p class="card-text small text-muted">Items</p>
                <a href="<?php echo e(base_url()); ?>index.php?controller=item&action=index" class="btn btn-info btn-sm text-dark">Open</a>
            </div>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
--