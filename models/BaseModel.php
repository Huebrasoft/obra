<?php
require_once __DIR__ . '/../config/database.php';

abstract class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }
}
