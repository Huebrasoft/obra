<?php
require_once __DIR__ . '/../models/Proyecto.php';

class HomeController {
    public function dashboard(): void {
        requireLogin();
        $proyectos = (new Proyecto())->activos();
        include __DIR__ . '/../views/dashboard/index.php';
    }
}
