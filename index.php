<?php

require_once "api/Database.php";
require_once "api/Crud/Brand/Brand.php";
require_once "api/Crud/Category/Category.php";
require_once "api/Crud/Product_model/Product_model.php";

header("Content-Type: application/json");

// Connect to database
$db = new Database();

$brand = new Brand($db->conn);
$category = new Category($db->conn);
//$product_model = new Product_model($db->conn);
/*
$product_model->create([
        "modelName" => "lolipop",
        "officialPrice" => "5154",
        "idBrand",
        "idCategory"
    ]
);
*/



//echo json_encode($category->readAll(array("categoryName")));

/*$brand->update([
    3,
    "brandName" => "Microsoft",
    "logo" => "microsoft.png"
    ]
);*/

//$brand->delete(5);

//echo json_encode($brand->readAll(array()));

// for later
/*if (empty($_GET["path"])) {
    echo json_encode(array("errors" => [
            "No path indicated !"
        ])
    );
}*/

