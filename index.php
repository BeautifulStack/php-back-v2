<?php

require_once "api/Database.php";
require_once "api/Crud/Brand/Brand.php";
require_once "api/Crud/Category/Category.php";
require_once "api/Crud/User/User.php";
require_once "api/Crud/Cart/Cart.php";
require_once "api/Crud/Promotion/Promotion.php";
require_once "api/Crud/Transfer/Transfer.php";

header("Content-Type: application/json");

// Connect to database
$db = new Database();

//$brand = new Brand($db->conn);
//$transfer = new Transfer($db->conn);







