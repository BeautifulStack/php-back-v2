<?php
session_start();

// Config file for db and data folder
require_once "config/config.php";

// Connect to db, need config.php
require_once "src/Database.php";

// CRUD Parent Class
require_once "src/email.php";

// Route request to right object
require_once "src/Router.php";

require_once "src/Objects/brand.php";
require_once "src/Objects/category.php";
require_once "src/Objects/offer.php";
require_once "src/Objects/products.php";
require_once "src/Objects/users.php";
require_once "src/Objects/model.php";
require_once "src/Objects/order.php";
require_once "src/Objects/cart.php";
require_once "src/Objects/warehouse.php";
require_once "src/Objects/project.php";
require_once "src/Objects/blockchainClient.php";

require_once "src/Utils/UserRights.php";
require_once "src/Utils/Estimate.php";
require_once "src/Utils/Stripe.php";
require_once "src/Utils/MysqlQuery.php";

// Inventory for the C application
require_once "src/Inventory/Inventory.php";

// Allow requests from outside domain
header("Access-Control-Allow-Origin: https://fairrepack.patedesable.eu/");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With, X-CSRF-Token, fairrepack_token, token");

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
