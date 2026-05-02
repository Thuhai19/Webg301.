<?php
$showNav = false;
$minimalHeader = true;
require ROOT_PATH . '/frontend/views/layout/header.php';
?>
<div class="row justify-content-center align-items-center py-5" style="min-height: calc(100vh - 8rem);">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h4 mb-2 text-center">Login</h1>
                <p class="text-muted text-center small mb-4">Use your account to access the admin area.</p>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2"><?php echo e($error); ?></div>
                <?php endif; ?>
                <form method="post" action="<?php echo e(base_url()); ?>index.php?controller=auth&action=doLogin" autocomplete="on">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required autocomplete="username">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
