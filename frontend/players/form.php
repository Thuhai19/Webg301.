<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
/** @var array{id:int,name:string,email:string,kill_character_id?:int|null,item_id?:int|null}|null $player */
$player = (isset($player) && is_array($player)) ? $player : null;
$isEdit = !empty($player);
$action = $isEdit ? 'update' : 'store';
?>
<h1 class="h3 mb-4"><?php echo $isEdit ? 'Edit Player' : 'Add Player'; ?></h1>
<form method="post" action="<?php echo e(base_url()); ?>index.php?controller=player&action=<?php echo e($action); ?>" class="col-lg-6">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?php echo (int) $player['id']; ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" required
               value="<?php echo $isEdit ? e($player['name']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required
               value="<?php echo $isEdit ? e($player['email']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="kill_character_id">Kill (Character)</label>
        <select class="form-select" id="kill_character_id" name="kill_character_id">
            <option value="">-- None --</option>
            <?php foreach (($characters ?? []) as $c): ?>
                <option value="<?php echo (int) $c['id']; ?>"
                    <?php echo ($isEdit && !empty($player['kill_character_id']) && (int) $player['kill_character_id'] === (int) $c['id']) ? 'selected' : ''; ?>>
                    <?php echo e($c['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label" for="item_id">Item</label>
        <select class="form-select" id="item_id" name="item_id">
            <option value="">-- None --</option>
            <?php foreach (($items ?? []) as $it): ?>
                <option value="<?php echo (int) $it['id']; ?>"
                    <?php echo ($isEdit && !empty($player['item_id']) && (int) $player['item_id'] === (int) $it['id']) ? 'selected' : ''; ?>>
                    <?php echo e($it['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" href="<?php echo e(base_url()); ?>index.php?controller=player&action=index">Cancel</a>
</form>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
