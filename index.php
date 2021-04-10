<?php

require_once "api/Crud/ProductModel/ProductModel.php";
require_once "api/Database.php";

header("Content-Type: application/json");

/*require_once "api/Router/Router.php";

header("Content-Type: application/json");

$router = new Router($_POST, $_FILES);

$router->route($_GET["path"]);*/

//var_dump($_FILES);
//var_dump($_POST);

$db = new Database();
$pro = new ProductModel($db->conn);

$pro->readAll([]);

echo json_encode($pro->read([2, "idBrand"]));