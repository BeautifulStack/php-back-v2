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

            case "Association":
                $db = new Database();
                // $object = new Association($db->conn);
                // $handler = new HandlerLogo($object, "name");
                // echo json_encode($handler->route($pathArr));
                break;

            case "Brand":
                $db = new Database();
                $brand = new Brand($db->conn);
                echo $brand->route();
                break;

            case "Cart":
                $db = new Database();
                // $object = new Cart($db->conn);
                // $handler = new HandlerCart($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "Category":
                $db = new Database();
                $brand = new Category($db->conn);
                echo $brand->route();
                // $object = new Category($db->conn);
                // $handler = new Handler($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "Offer":
                $db = new Database();
                $brand = new Offer($db->conn);
                echo $brand->route($pathArr);

                // $object = new Offer($db->conn);
                // $handler = new HandlerOffer($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "Order":
                $db = new Database();

                // $object = new Order($db->conn);
                // $handler = new Handler($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "Product":
                $db = new Database();
                $brand = new Products($db->conn);
                echo $brand->route();
                // $object = new Product($db->conn);
                // $handler = new HandlerProduct($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "ProductModel":
                $db = new Database();
                // $object = new ProductModel($db->conn);
                // $handler = new HandlerModel($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "Project":
                $db = new Database();
                // $object = new Project($db->conn);
                // $handler = new Handler($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "Promotion":
                $db = new Database();
                // $object = new Promotion($db->conn);
                // $handler = new Handler($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "Transfer":
                $db = new Database();
                // $object = new Transfer($db->conn);
                // $handler = new Handler($object);
                // echo json_encode($handler->route($pathArr));
                break;

            case "User":
                $db = new Database();
                $object = new User($db->conn);
                echo $object->route();
                // echo json_encode($handler->route($pathArr));
                break;

            case "Warehouse":
                $db = new Database();
                // $object = new Warehouse($db->conn);
                // $handler = new Handler($object);
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
                $token = UserRights::Login($db->conn);
                if ($token) {
                    echo json_encode(["status" => 201, "token" => $token]);
                } else {
                    echo json_encode(["status" => 401, "error" => "Bad credentials"]);
                }

                break;


            case "userInfo":
                // if (isset($_SESSION["id"])) {
                //     $db = new Database();
                //     $object = new User($db->conn);
                //     echo json_encode(array(
                //         "infos" => HandlerUser::filterPassword($object->where(["idUser" => $_SESSION["id"]]))[0]
                //     ));
                //     exit();
                // } else {
                //     echo json_encode(array("errors" => [
                //         "Please Login Before"
                //     ]));
                //     exit();
                // }

                // case "login":
                //     if (($id = HandlerUser::Login()) > -1) {
                //         echo json_encode(array(
                //             "id" => $id
                //         ));
                //         exit();
                //     } else {
                //         echo json_encode(array("errors" => [
                //             "Error in credentials"
                //         ]));
                //         exit();
                //     }

                // case "logout":
                //     echo json_encode(array("status" => [
                //         "ok"
                //     ]));
                //     session_destroy();
                //     break;

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
