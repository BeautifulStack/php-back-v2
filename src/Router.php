<?php

class Router
{

    public function route(string $path)
    {
        $pathArr = explode('/', $path);

        if (empty($pathArr) || count($pathArr) == 1 || empty($pathArr[0])) {
            echo json_encode(
                array("errors" => [
                    "None or invalid path indicated !"
                ])
            );
            exit;
        }
        // Deals with it handler
        switch ($pathArr[0]) {

                // case "Association":
                //     $db = new Database();
                //     // $object = new Association($db->conn);
                //     // $handler = new HandlerLogo($object, "name");
                //     // echo json_encode($handler->route($pathArr));
                //     break;

            case "Brand":
                $db = new Database();
                $brand = new Brand($db->conn);
                echo $brand->route();
                break;


            case "Category":
                $db = new Database();
                $brand = new Category($db->conn);
                echo $brand->route($pathArr);
                break;

            case "Offer":
                $db = new Database();
                $brand = new Offer($db->conn);
                echo $brand->route($pathArr);
                break;


            case "Product":
                $db = new Database();
                $brand = new Products($db->conn);
                echo $brand->route($pathArr);
                break;

            case "Model":
                $db = new Database();
                $brand = new Model($db->conn);
                echo $brand->route($pathArr);
                break;



            case "Promotion":
                $db = new Database();
                // $object = new Promotion($db->conn);
                // $handler = new Handler($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "User":
                $db = new Database();
                $object = new User($db->conn);
                echo $object->route($pathArr);
                // echo json_encode($handler->route($pathArr));
                break;


            case "Inventory":
                if ($pathArr[1] == "sendInventory") echo Inventory::upload_inventory();
                else echo Inventory::handle_daily($pathArr);
                break;

            case "data":
                $temp = explode('.', $path);
                $extension = end($temp);
                if ($extension == "jpg") $extension = "jpeg";
                header("Content-Type: image/$extension");
                echo file_get_contents($path);
                break;

            case "Login":
                $db = new Database();
                $user = UserRights::Login($db->conn);
                if ($user) {
                    echo json_encode(["status" => 201, "token" => $user['token'], "admin" => $user['isAdmin']]);
                } else {
                    echo json_encode(["status" => 401, "error" => "Bad credentials"]);
                }

                break;

            case "estimate":
                echo json_encode(
                    array(
                        "estimation" => estimate()
                    )
                );
                break;

            case "Payment":
                echo json_encode(Stripe::do_payment($pathArr));
                break;

            default:
                echo json_encode(
                    array("errors" => [
                        "None or invalid path indicated !"
                    ])
                );
                exit;
        }
    }
}
