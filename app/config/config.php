<?php
/**
 * Cấu hình chung ứng dụng
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

// URL prefix for this app (project in htdocs subfolder with public entrypoint)
define('BASE_URL', '/WEB.rpggame/public');

// Session
define('SESSION_USER_KEY', 'rpg_admin_user');
