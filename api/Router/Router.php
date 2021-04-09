<?php

class Router
{
    private array $posts;
    private array $files;

    public function __construct(array $posts, array $files)
    {
        $this->posts = $posts;
        $this->files = $files;
    }

    public function route(string $path)
    {
        $pathArr = explode('/', $path);

        if (empty($pathArr)) {
            echo json_encode(array("errors" => [
                    "None or invalid path indicated !"
                ])
            );
        }

        switch ($pathArr[0]){

            // Deals with it handler
            case "Brand":
                new BrandHandler($pathArr[1], $this->posts, $this->files);
        }

    }

}