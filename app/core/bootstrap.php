<?php
/**
 * Nạp class Controller và Model theo tên
 */
require_once ROOT_PATH . '/app/config/helpers.php';

spl_autoload_register(function ($className) {
    $paths = [
        ROOT_PATH . '/app/controllers/' . $className . '.php',
        ROOT_PATH . '/app/models/' . $className . '.php',
        ROOT_PATH . '/app/core/' . $className . '.php',
    ];
    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

require_once ROOT_PATH . '/app/config/database.php';
