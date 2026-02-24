<?php
class HomeController
{
    public function dashboard(): void
    {
        $title = 'Dashboard';
        include __DIR__ . '/../views/home.php';
    }
}
