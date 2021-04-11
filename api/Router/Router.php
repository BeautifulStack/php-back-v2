<?php

class Router
{

    public function route(string $path)
    {
        $pathArr = explode('/', $path);

        if (empty($pathArr) || count($pathArr) < 2 || empty($pathArr[1])) {
            echo json_encode(array("errors" => [
                    "None or invalid path indicated !"
                ])
            );
            exit;
        }

        // Deals with it handler
        switch ($pathArr[0]) {

            case "Association":
                $db = new Database();
                $object = new Association($db->conn);
                $handler = new HandlerLogo($object, "name");
                $handler->route($pathArr);
                break;

            case "Brand":
                $db = new Database();
                $brand = new Brand($db->conn);
                $handler = new HandlerLogo($brand, "brandName");
                $handler->route($pathArr);
                break;

            case "Cart":
                $db = new Database();
                $object = new Cart($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "Category":
                $db = new Database();
                $object = new Category($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "Offer":
                $db = new Database();
                $object = new Offer($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "Order":
                $db = new Database();
                $object = new Order($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "Product":
                $db = new Database();
                $object = new Product($db->conn);
                //$handler = new Handler($this->posts, $object);
                //$handler->route($pathArr);
                break;

            case "ProductModel":
                $db = new Database();
                $object = new ProductModel($db->conn);
                //$handler = new Handler($this->posts, $object);
                //$handler->route($pathArr);
                break;

            case "Project":
                $db = new Database();
                $object = new Project($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "Promotion":
                $db = new Database();
                $object = new Promotion($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "Transfer":
                $db = new Database();
                $object = new Transfer($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "User":
                $db = new Database();
                $object = new User($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            case "Warehouse":
                $db = new Database();
                $object = new Warehouse($db->conn);
                $handler = new Handler($object);
                $handler->route($pathArr);
                break;

            default:
                echo json_encode(array("errors" => [
                        "None or invalid path indicated !"
                    ])
                );
                exit;
        }

    }

}