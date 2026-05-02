<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
?>
<h1 class="h3 mb-4">Player Details</h1>
<dl class="row col-lg-8">
    <dt class="col-sm-3">ID</dt>
    <dd class="col-sm-9"><?php echo e((string) $player['id']); ?></dd>
    <dt class="col-sm-3">Name</dt>
    <dd class="col-sm-9"><?php echo e($player['name']); ?></dd>
    <dt class="col-sm-3">Email</dt>
    <dd class="col-sm-9"><?php echo e($player['email']); ?></dd>
    <dt class="col-sm-3">Kill</dt>
    <dd class="col-sm-9"><?php echo e($player['kill_character_name'] ?? ''); ?></dd>
    <dt class="col-sm-3">Item</dt>
    <dd class="col-sm-9"><?php echo e($player['item_name'] ?? ''); ?></dd>
</dl>
<a class="btn btn-outline-secondary" href="<?php echo e(base_url()); ?>index.php?controller=player&action=index">Back to list</a>
<a class="btn btn-primary" href="<?php echo e(base_url()); ?>index.php?controller=player&action=edit&id=<?php echo (int) $player['id']; ?>">Edit</a>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
