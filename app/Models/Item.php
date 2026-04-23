<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Item
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }s
