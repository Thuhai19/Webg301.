<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0">Item List</h1>
    <a class="btn btn-primary" href="<?php echo e(base_url()); ?>index.php?controller=item&action=create">Add Item</a>
</div>
<form class="row g-2 mb-3" method="get" action="<?php echo e(base_url()); ?>index.php">
    <input type="hidden" name="controller" value="item">
    <input type="hidden" name="action" value="index">
    <div class="col-auto">
        <input type="number" min="1" class="form-control" name="search_id" placeholder="Search by ID"
               value="<?php echo isset($searchId) && $searchId !== '' ? e((string) $searchId) : ''; ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">Search</button>
        <a class="btn btn-outline-secondary" href="<?php echo e(base_url()); ?>index.php?controller=item&action=index">View all</a>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Power</th>
                <th style="width:160px">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr><td colspan="5" class="text-center text-muted">No data available</td></tr>
            <?php else: ?>
                <?php foreach ($items as $idx => $it): ?>
                    <tr>
                        <td><?php echo (int) ($it['display_id'] ?? ($idx + 1)); ?></td>
                        <td><?php echo e($it['name']); ?></td>
                        <td><?php echo e($it['type']); ?></td>
                        <td><?php echo e((string) $it['power']); ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(base_url()); ?>index.php?controller=item&action=edit&id=<?php echo (int) $it['id']; ?>">Edit</a>
                            <a class="btn btn-sm btn-outline-danger" href="<?php echo e(base_url()); ?>index.php?controller=item&action=delete&id=<?php echo (int) $it['id']; ?>"
                               onclick="return confirm('Delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
