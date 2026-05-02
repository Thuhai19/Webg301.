<?php
$pageTitle = $pageTitle ?? 'RPG Game Manager';
$showNav = $showNav ?? true;
$minimalHeader = $minimalHeader ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($pageTitle); ?> — RPG Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php if ($minimalHeader && !$showNav): ?>
    <header class="bg-dark text-white py-3 mb-0 shadow-sm">
        <div class="container">
            <span class="fw-semibold">RPG Game Manager</span>
            <span class="text-white-50 small ms-2">Admin area</span>
        </div>
    </header>
<?php elseif ($showNav): ?>
    <?php require __DIR__ . '/navbar.php'; ?>
<?php endif; ?>
<main class="container py-4">
<?php
if (!empty($_SESSION['flash_error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . e($_SESSION['flash_error'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['flash_error']);
}
?>
