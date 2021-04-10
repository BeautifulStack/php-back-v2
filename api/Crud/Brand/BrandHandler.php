<?php

require_once "Brand.php";
require_once "api/Database.php";

class BrandHandler
{
    private $post;
    private $files;

    public function __construct(array $post, array $files){
        $this->post = $post;
        $this->files = $files;
    }

    public function route(string $command)
    {
        switch ($command) {
            case "ReadAll":
                $args = array();

                if (array_key_exists("attributes", $this->post)) {
                    $args = explode(',', $this->post["attributes"]);
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
        }
    }
}