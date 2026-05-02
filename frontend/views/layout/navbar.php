<?php
$u = $_SESSION[SESSION_USER_KEY] ?? null;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-0">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(base_url()); ?>index.php?controller=dashboard&action=index">RPG Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <?php if ($u): ?>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(base_url()); ?>index.php?controller=player&action=index">Players</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(base_url()); ?>index.php?controller=character&action=index">Characters</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(base_url()); ?>index.php?controller=skill&action=index">Skills</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(base_url()); ?>index.php?controller=item&action=index">Items</a>
                </li>
            </ul>
            <span class="navbar-text text-white-50 me-3 small"><?php echo e($u['username'] ?? ''); ?></span>
            <a class="btn btn-outline-light btn-sm" href="<?php echo e(base_url()); ?>index.php?controller=auth&action=logout">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
