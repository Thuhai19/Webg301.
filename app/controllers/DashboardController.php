<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('dashboard/index.php', ['pageTitle' => 'Dashboard']);
    }
}
