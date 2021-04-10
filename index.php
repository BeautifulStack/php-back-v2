<?php

require_once "api/Database.php";
require_once "api/Crud/Brand/Brand.php";
require_once "api/Crud/Category/Category.php";
require_once "api/Crud/ProductModel/ProductModel.php";

header("Content-Type: application/json");

// Connect to database
$db = new Database();

//$brand = new Brand($db->conn);
$product_model = new ProductModel($db->conn);

$product_model->create([
    "modelName" => "iPhone 7 32GB",
    "officialPrice" => 150,
    "idBrand" => 2,
    "idCategory" => 1

]);
