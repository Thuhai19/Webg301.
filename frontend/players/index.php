<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0">Player List</h1>
    <a class="btn btn-primary" href="<?php echo e(base_url()); ?>index.php?controller=player&action=create">Add Player</a>
</div>
<form class="row g-2 mb-3" method="get" action="<?php echo e(base_url()); ?>index.php">
    <input type="hidden" name="controller" value="player">
    <input type="hidden" name="action" value="index">
    <div class="col-auto">
        <input type="number" min="1" class="form-control" name="search_id" placeholder="Search by ID"
               value="<?php echo isset($searchId) && $searchId !== '' ? e((string) $searchId) : ''; ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">Search</button>
        <a class="btn btn-outline-secondary" href="<?php echo e(base_url()); ?>index.php?controller=player&action=index">View all</a>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Kill</th>
                <th>Item</th>
                <th style="width:220px">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($players)): ?>
                <tr><td colspan="6" class="text-center text-muted">No data available</td></tr>
            <?php else: ?>
                <?php foreach ($players as $idx => $p): ?>
                    <tr>
                        <td><?php echo (int) ($p['display_id'] ?? ($idx + 1)); ?></td>
                        <td><?php echo e($p['name']); ?></td>
                        <td><?php echo e($p['email']); ?></td>
                        <td><?php echo e($p['kill_character_name'] ?? ''); ?></td>
                        <td><?php echo e($p['item_name'] ?? ''); ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo e(base_url()); ?>index.php?controller=player&action=show&id=<?php echo (int) $p['id']; ?>">Details</a>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(base_url()); ?>index.php?controller=player&action=edit&id=<?php echo (int) $p['id']; ?>">Edit</a>
                            <a class="btn btn-sm btn-outline-danger" href="<?php echo e(base_url()); ?>index.php?controller=player&action=delete&id=<?php echo (int) $p['id']; ?>"
                               onclick="return confirm('Delete this player?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
