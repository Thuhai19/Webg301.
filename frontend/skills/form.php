<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
/** @var array{id:int,name:string,damage:int,mana_cost:int}|null $skill */
$skill = (isset($skill) && is_array($skill)) ? $skill : null;
$isEdit = !empty($skill);
$action = $isEdit ? 'update' : 'store';
?>
<h1 class="h3 mb-4"><?php echo $isEdit ? 'Edit Weapon' : 'Add Weapon'; ?></h1>
<form method="post" action="<?php echo e(base_url()); ?>index.php?controller=skill&action=<?php echo e($action); ?>" class="col-lg-6">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?php echo (int) $skill['id']; ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label" for="name">Weapon Name</label>
        <input type="text" class="form-control" id="name" name="name" required
               value="<?php echo $isEdit ? e($skill['name']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="damage">Damage</label>
        <input type="number" min="0" class="form-control" id="damage" name="damage" required
               value="<?php echo $isEdit ? e((string) $skill['damage']) : '0'; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="mana_cost">Mana cost</label>
        <input type="number" min="0" class="form-control" id="mana_cost" name="mana_cost" required
               value="<?php echo $isEdit ? e((string) $skill['mana_cost']) : '0'; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" href="<?php echo e(base_url()); ?>index.php?controller=skill&action=index">Cancel</a>
</form>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
