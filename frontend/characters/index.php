<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0">Character List</h1>
    <a class="btn btn-primary" href="<?php echo e(base_url()); ?>index.php?controller=character&action=create">Add Character</a>
</div>
<form class="row g-2 mb-3" method="get" action="<?php echo e(base_url()); ?>index.php">
    <input type="hidden" name="controller" value="character">
    <input type="hidden" name="action" value="index">
    <div class="col-auto">
        <input type="number" min="1" class="form-control" name="search_id" placeholder="Search by ID"
               value="<?php echo isset($searchId) && $searchId !== '' ? e((string) $searchId) : ''; ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">Search</button>
        <a class="btn btn-outline-secondary" href="<?php echo e(base_url()); ?>index.php?controller=character&action=index">View all</a>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered bg-white small">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Level</th>
                <th>HP</th>
                <th>Mana</th>
                <th>Kill By</th>
                <th style="width:200px">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($characters)): ?>
                <tr><td colspan="7" class="text-center text-muted">No data available</td></tr>
            <?php else: ?>
                <?php foreach ($characters as $idx => $c): ?>
                    <tr>
                        <td><?php echo (int) ($c['display_id'] ?? ($idx + 1)); ?></td>
                        <td><?php echo e($c['name']); ?></td>
                        <td><?php echo e((string) $c['level']); ?></td>
                        <td><?php echo e((string) $c['hp']); ?></td>
                        <td><?php echo e((string) $c['mana']); ?></td>
                        <td><?php echo e($c['kill_by_player_name'] ?? ''); ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo e(base_url()); ?>index.php?controller=character&action=show&id=<?php echo (int) $c['id']; ?>">Details</a>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(base_url()); ?>index.php?controller=character&action=edit&id=<?php echo (int) $c['id']; ?>">Edit</a>
                            <a class="btn btn-sm btn-outline-danger" href="<?php echo e(base_url()); ?>index.php?controller=character&action=delete&id=<?php echo (int) $c['id']; ?>"
                               onclick="return confirm('Delete this character?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
