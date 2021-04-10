<?php

require_once "api/Crud/Brand/BrandHandler.php";

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

        switch ($pathArr[0]) {

            // Deals with it handler
            case "Brand":
                $brand_handler = new BrandHandler($this->posts, $this->files);
                $brand_handler->route($pathArr[1]);
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