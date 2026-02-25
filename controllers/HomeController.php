<?php
require_once __DIR__ . '/../models/DashboardModel.php';

class HomeController
{
    public function dashboard(): void
    {
        $title = 'Obras y Proyectos';
        $data = (new DashboardModel())->getDashboardData();
        include __DIR__ . '/../views/home.php';
    }
}
