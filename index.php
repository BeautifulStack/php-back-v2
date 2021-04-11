<?php

// Connect to db, need config.php
require_once "config/config.php";
require_once "api/Database.php";

// CRUD
require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

require_once "api/Crud/Association/Association.php";
require_once "api/Crud/Brand/Brand.php";
require_once "api/Crud/CaractValue/CaractValue.php";
require_once "api/Crud/Cart/Cart.php";
require_once "api/Crud/Category/Category.php";
require_once "api/Crud/Image/Image.php";
require_once "api/Crud/Offer/Offer.php";
require_once "api/Crud/Order/Order.php";
require_once "api/Crud/Product/Product.php";
require_once "api/Crud/ProductModel/ProductModel.php";
require_once "api/Crud/Project/Project.php";
require_once "api/Crud/Promotion/Promotion.php";
require_once "api/Crud/Transfer/Transfer.php";
require_once "api/Crud/User/User.php";
require_once "api/Crud/Warehouse/Warehouse.php";

// Handle request
require_once "api/Crud/Handler/Handler.php";

// Route request to right object
require_once "api/Router/Router.php";


header("Content-Type: application/json");

$router = new Router();

$router->route($_GET["path"]);

//var_dump($_FILES);
//var_dump($_POST);
