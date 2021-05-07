<?php
session_start();

// Config file for db and data folder
require_once "config/config.php";

// Connect to db, need config.php
require_once "src/Database.php";

// CRUD Parent Class
require_once "src/Crud/CrudClass/CrudInterface.php";
require_once "src/Crud/CrudClass/CrudClass.php";

// CRUD Classes
require_once "src/Crud/Association.php";
require_once "src/Crud/Brand.php";
require_once "src/Crud/CaractValue.php";
require_once "src/Crud/Cart.php";
require_once "src/Crud/Category.php";
require_once "src/Crud/Image.php";
require_once "src/Crud/Offer.php";
require_once "src/Crud/Order.php";
require_once "src/Crud/Product.php";
require_once "src/Crud/ProductModel.php";
require_once "src/Crud/Project.php";
require_once "src/Crud/Promotion.php";
require_once "src/Crud/Transfer.php";
require_once "src/Crud/User.php";
require_once "src/Crud/Warehouse.php";

// Handle request
require_once "src/Crud/Handler/Handler.php";
require_once "src/Crud/Handler/HandlerUser.php";
require_once "src/Crud/Handler/HandlerLogo.php";
require_once "src/Crud/Handler/HandlerModel.php";
require_once "src/Crud/Handler/HandlerImage.php";
require_once "src/Crud/Handler/HandlerOffer.php";

// Route request to right object
require_once "src/Router.php";

// Inventory for the C application
require_once "src/Inventory/Inventory.php";

// Allow requests from outside domain
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With, X-CSRF-Token");

// return a JSON response
header("Content-Type: application/json");

// Accept form-data OR json
if (count($_POST) == 0 && count($_FILES) == 0) {
    $json = file_get_contents("php://input");
    $_POST = json_decode($json, TRUE);
} elseif (array_key_exists("request", $_POST)) {
    $json_decoded = json_decode($_POST["request"], TRUE);

    if ($json_decoded === NULL) {
        echo json_encode(
            array("errors" => [
                "Json is not properly encoded"
            ])
        );
        exit();
    }

    $_POST = $json_decoded;
}

$router = new Router();

$router->route($_GET["path"]);
