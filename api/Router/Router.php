<?php

require_once "api/Crud/Brand/BrandHandler.php";
require_once "api/Crud/Brand/Brand.php";
require_once "api/Crud/Category/Category.php";
require_once "api/Database.php";
require_once "api/Crud/Handler.php";

class Router
{
    private $posts;
    private $files;

    public function __construct(array $posts, array $files)
    {
        $this->posts = $posts;
        $this->files = $files;
    }

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

            case "Brand":
                $db = new Database();
                $brand = new Brand($db->conn);
                $handler = new BrandHandler($this->posts, $this->files, $brand);
                $handler->route($pathArr);
                break;

            case "Category":
                $db = new Database();
                $category = new Category($db->conn);
                $handler = new Handler($this->posts, $category);
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