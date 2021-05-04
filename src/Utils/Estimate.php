<?php
function estimate (): int
{
    $modelId = $_POST["modelId"];
    $accessories = $_POST["accessories"];
    $state = $_POST["state"];

    if ($accessories === null || $state === null) {
        echo json_encode(array("errors" => [
            "Please fill accessories and state fields!"
        ])
        );
        exit;
    }

// $accessories = [0 = 5€, 1 = 10€, 2 = 15€, 3 = 0€]
    switch ($accessories){
        case 0:
            $additionalValue = 5;
            break;
        case 1:
            $additionalValue = 10;
            break;
        case 2:
            $additionalValue = 15;
            break;
        default:
            $additionalValue = 0;
    }

    // $state = [0 = 0.7, 1 = 0.5, 2 = 0.4, 3 = 0.3]
    switch ($state){
        case 0:
            $mult = 0.7;
            break;
        case 1:
            $mult = 0.5;
            break;
        case 2:
            $mult = 0.4;
            break;
        default:
            $mult = 0.3;
    }
    

    $db = new Database();
    $object = new ProductModel($db->conn);
    $model = $object->where(["idModel" => $modelId])[0];

    $originalPrice = $model["officialPrice"];

    $estimation = ($originalPrice * $mult) + $additionalValue;

    return round($estimation);
}
?>