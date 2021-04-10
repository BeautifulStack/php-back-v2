<?php

require_once "Brand.php";
require_once "../../Database.php";

class BrandHandler
{

    public function __construct(string $command, array $post, array $files)
    {
        switch ($command) {
            case "ReadAll":
                $args = array();

                if (array_key_exists("attributes", $post)) {
                    $args = explode(',', $post["attributes"]);
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