<?php
//session_start();

// Config file for db and data folder
require_once "config/config.php";

// Connect to db, need config.php
require_once "api/Database.php";

// CRUD Parent Class
require_once "api/Crud/CrudClass/CrudInterface.php";
require_once "api/Crud/CrudClass/CrudClass.php";

// CRUD Classes
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

// Inventory for the C application
require_once "api/Inventory/Inventory.php";

// Allow requests from outside domain
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With, X-CSRF-Token");

// return a JSON response
header("Content-Type: application/json");

// Accept form-data OR json
if (count($_POST) == 0 && count($_FILES) == 0) {
    $json = file_get_contents("php://input");
    $_POST = json_decode($json, TRUE);
} elseif (array_key_exists("request", $_POST)) $_POST = json_decode($_POST["request"], TRUE);

$router = new Router();

$router->route($_GET["path"]);