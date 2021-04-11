<?php

require_once "api/Router/Router.php";
require_once "api/Crud/Warehouse/Warehouse.php";

header("Content-Type: application/json");

$router = new Router($_POST, $_FILES);

$router->route($_GET["path"]);

//var_dump($_FILES);
//var_dump($_POST);
