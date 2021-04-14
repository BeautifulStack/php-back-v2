<?php
//session_start();

// Connect to db, need config.php
require_once "config/config.php";
require_once "api/Database.php";

// CRUD
require_once "api/Crud/CrudClass/CrudInterface.php";
require_once "api/Crud/CrudClass/CrudClass.php";

require_once "api/Crud/Association.php";
require_once "api/Crud/Brand.php";
require_once "api/Crud/CaractValue.php";
require_once "api/Crud/Cart.php";
require_once "api/Crud/Category.php";
require_once "api/Crud/Image.php";
require_once "api/Crud/Offer.php";
require_once "api/Crud/Order.php";
require_once "api/Crud/Product.php";
require_once "api/Crud/ProductModel.php";
require_once "api/Crud/Project.php";
require_once "api/Crud/Promotion.php";
require_once "api/Crud/Transfer.php";
require_once "api/Crud/User.php";
require_once "api/Crud/Warehouse.php";

// Handle request
require_once "api/Crud/Handler/Handler.php";
require_once "api/Crud/Handler/HandlerLogo.php";
require_once "api/Crud/Handler/HandlerModel.php";
require_once "api/Crud/Handler/HandlerImage.php";

// Route request to right object
require_once "api/Router/Router.php";

// Accept form-data OR json
if (count($_POST) == 0 && count($_FILES) == 0) {
    $json = file_get_contents("php://input");
    $data = json_decode($json, TRUE);
    $_POST = $data;
}

header("Content-Type: application/json");

$router = new Router();

$router->route($_GET["path"]);
