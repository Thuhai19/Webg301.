<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
/** @var array{id:int,name:string,level:int,hp:int,mana:int,kill_by_player_id?:int|null}|null $character */
$character = (isset($character) && is_array($character)) ? $character : null;
$isEdit = !empty($character);
$action = $isEdit ? 'update' : 'store';
?>
<h1 class="h3 mb-4"><?php echo $isEdit ? 'Edit Character' : 'Add Character'; ?></h1>
<form method="post" action="<?php echo e(base_url()); ?>index.php?controller=character&action=<?php echo e($action); ?>" class="row g-3 col-lg-10">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?php echo (int) $character['id']; ?>">
    <?php endif; ?>
    <div class="col-md-6">
        <label class="form-label" for="name">Character Name</label>
        <input type="text" class="form-control" id="name" name="name" required
               value="<?php echo $isEdit ? e($character['name']) : ''; ?>">
    </div>
    <div class="col-md-2">
        <label class="form-label" for="level">Level</label>
        <input type="number" min="1" class="form-control" id="level" name="level" required
               value="<?php echo $isEdit ? e((string) $character['level']) : '1'; ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="hp">HP</label>
        <input type="number" min="0" class="form-control" id="hp" name="hp" required
               value="<?php echo $isEdit ? e((string) $character['hp']) : '100'; ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="mana">Mana</label>
        <input type="number" min="0" class="form-control" id="mana" name="mana" required
               value="<?php echo $isEdit ? e((string) $character['mana']) : '50'; ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="kill_by_player_id">Kill By (Player)</label>
        <select class="form-select" id="kill_by_player_id" name="kill_by_player_id">
            <option value="">-- None --</option>
            <?php foreach (($players ?? []) as $p): ?>
                <option value="<?php echo (int) $p['id']; ?>"
                    <?php echo ($isEdit && !empty($character['kill_by_player_id']) && (int) $character['kill_by_player_id'] === (int) $p['id']) ? 'selected' : ''; ?>>
                    <?php echo e($p['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" href="<?php echo e(base_url()); ?>index.php?controller=character&action=index">Cancel</a>
    </div>
</form>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
