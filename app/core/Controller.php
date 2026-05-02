<?php
/**
 * Controller cơ sở: render view, redirect, kiểm tra đã đăng nhập
 */
class Controller
{
    protected function render(string $viewFile, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = ROOT_PATH . '/frontend/' . $viewFile;
        if (!is_file($viewPath)) {
            http_response_code(404);
            echo 'View not found: ' . htmlspecialchars($viewFile);
            return;
        }
        require $viewPath;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function url(string $controller, string $action = 'index', array $params = []): string
    {
        $q = ['controller' => $controller, 'action' => $action];
        foreach ($params as $k => $v) {
            $q[$k] = $v;
        }
        return 'index.php?' . http_build_query($q);
    }

    protected function requireAdmin(): void
    {
        if (empty($_SESSION[SESSION_USER_KEY])) {
            $this->redirect($this->baseUrl() . 'index.php?controller=auth&action=login');
        }
    }

    /** Đường dẫn web tới thư mục chứa index.php (có / cuối nếu không rỗng) */
    protected function baseUrl(): string
    {
        $b = rtrim((string) BASE_URL, '/');
        return $b === '' ? '' : $b . '/';
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }

    protected function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    protected function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    protected function e(?string $s): string
    {
        return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
    }
}
