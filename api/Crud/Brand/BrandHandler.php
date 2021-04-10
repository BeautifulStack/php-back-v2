<?php

require_once "Brand.php";
require_once "api/Database.php";

class BrandHandler
{
    private $post;
    private $files;

    public function __construct(array $post, array $files)
    {
        $this->post = $post;
        $this->files = $files;
    }

    public function route(array $pathArr)
    {
        switch ($pathArr[1]) {
            case "ReadAll":
                $args = array();

                if (count($pathArr) > 2) {
                    $args = explode(',', $pathArr[2]);
                }

                $db = new Database();
                $brand = new Brand($db->conn);

                $result = $brand->readAll($args);

                if (count($result) == 0) {
                    echo json_encode(array("errors" => [
                            "No results !"
                        ])
                    );
                    exit;
                }

                echo json_encode($result);

                break;

            case "Read":
                $args = array();

                if (count($pathArr) > 2) {
                    $args[0] = $pathArr[2];
                    if (count($pathArr) > 3) {
                        $args = array_merge($args, explode(',', $pathArr[3]));
                    }
                } else {
                    echo json_encode(array("errors" => [
                            "Missing argument(s) !"
                        ])
                    );
                    exit();
                }

                $db = new Database();
                $brand = new Brand($db->conn);

                $result = $brand->read($args);

                if (count($result) == 0) {
                    echo json_encode(array("errors" => [
                            "No results !"
                        ])
                    );
                    exit;
                }

                echo json_encode($result);
                break;

            default:
                echo json_encode(array("errors" => [
                        "None or invalid path indicated !"
                    ])
                );
        }
    }
}