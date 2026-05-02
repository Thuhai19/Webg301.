<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
/** @var array{id:int,name:string,type:string,power:int}|null $item */
$item = (isset($item) && is_array($item)) ? $item : null;
$isEdit = !empty($item);
$action = $isEdit ? 'update' : 'store';
?>
<h1 class="h3 mb-4"><?php echo $isEdit ? 'Edit Item' : 'Add Item'; ?></h1>
<form method="post" action="<?php echo e(base_url()); ?>index.php?controller=item&action=<?php echo e($action); ?>" class="col-lg-6">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" required
               value="<?php echo $isEdit ? e($item['name']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="type">Type (weapon, armor, potion...)</label>
        <input type="text" class="form-control" id="type" name="type" required
               value="<?php echo $isEdit ? e($item['type']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="power">Power</label>
        <input type="number" min="0" class="form-control" id="power" name="power" required
               value="<?php echo $isEdit ? e((string) $item['power']) : '0'; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" href="<?php echo e(base_url()); ?>index.php?controller=item&action=index">Cancel</a>
</form>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
