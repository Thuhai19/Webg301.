<?php
/**
 * Hàm tiện ích dùng trong view
 */
function base_url(): string
{
    $b = rtrim((string) BASE_URL, '/');
    return $b === '' ? '' : $b . '/';
}

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
