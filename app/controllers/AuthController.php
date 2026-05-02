<?php

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        if (!empty($_SESSION[SESSION_USER_KEY])) {
            $this->redirect($this->baseUrl() . 'index.php?controller=dashboard&action=index');
        }
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        $this->render('auth/login.php', ['error' => $error, 'pageTitle' => 'Login']);
    }

    /**
     * Bookmark / link cũ trỏ tới đăng ký — chuyển về đăng nhập.
     */
    public function register(): void
    {
        $this->redirect($this->baseUrl() . 'index.php?controller=auth&action=login');
    }

    public function doRegister(): void
    {
        $this->redirect($this->baseUrl() . 'index.php?controller=auth&action=login');
    }

    public function doLogin(): void
    {
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=auth&action=login');
        }
        $username = trim((string) $this->post('username', ''));
        $password = (string) $this->post('password', '');

        if ($username === '' || $password === '') {
            $_SESSION['flash_error'] = 'Please fill in all required fields.';
            $this->redirect($this->baseUrl() . 'index.php?controller=auth&action=login');
        }

        $user = $this->userModel->findByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['flash_error'] = 'Invalid username or password.';
            $this->redirect($this->baseUrl() . 'index.php?controller=auth&action=login');
        }

        $_SESSION[SESSION_USER_KEY] = [
            'id' => $user['id'],
            'username' => $user['username'],
        ];
        $this->redirect($this->baseUrl() . 'index.php?controller=dashboard&action=index');
    }

    public function logout(): void
    {
        unset($_SESSION[SESSION_USER_KEY]);
        $this->redirect($this->baseUrl() . 'index.php?controller=auth&action=login');
    }
}
