<?php

require_once "api/Database.php";
require_once "api/Crud/Brand/Brand.php";

header("Content-Type: application/json");

// Connect to database
$db = new Database();

$brand = new Brand($db->conn);

//echo json_encode($brand->readAll(array("brandName")));

/*$brand->update([
    3,
    "brandName" => "Microsoft",
    "logo" => "microsoft.png"
    ]
);*/

$brand->delete(5);

//echo json_encode($brand->readAll(array()));

// for later
/*if (empty($_GET["path"])) {
    echo json_encode(array("errors" => [
            "No path indicated !"
        ])
    );
}*/

