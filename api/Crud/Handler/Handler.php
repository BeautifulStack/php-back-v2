<?php

class Handler
{
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    protected function upload_file(string $path, string $attr): string
    {
        $acceptable = [
            "image/jpeg",
            "image/jpg",
            "image/gif",
            "image/png"
        ];

        if (!(in_array($_FILES[$attr]["type"], $acceptable))) {
            echo json_encode(array("errors" => [
                    "Not a valid image file !"
                ])
            );
            exit;
        }
        if ($_FILES[$attr]['size'] > (1024*1024)) {
            echo json_encode(array("errors" => [
                    "File too big !"
                ])
            );
            exit;
        }

        $filename = $_FILES[$attr]['name'];
        $imageArr = explode(".", $filename);
        $type = end($imageArr);
        $path = $path.".".$type;

        move_uploaded_file($_FILES[$attr]["tmp_name"], $path);

        return $path;
    }

    protected function delete_file($id, $attr)
    {
        $res = $this->object->read([$id, $attr]);

        try {
            unlink($res[0][$attr]);
        } catch (Exception $e) {}

        return $res[0][$attr];
    }

    protected function readAll(array $pathArr): array
    {
        $args = array();

        // if: Object/ReadAll/(attributes)
        if (count($pathArr) > 2) {
            $args = explode(',', $pathArr[2]);
        }

        $result = $this->object->readAll($args);

        if (count($result) == 0) {
            echo json_encode(array("errors" => [
                    "No results !"
                ])
            );
            exit;
        }

        return $result;
    }

    protected function read(array $pathArr)
    {
        $args = array();

        if (count($pathArr) > 2) {
            $args[0] = $pathArr[2]; // id as index 0
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

        $result = $this->object->read($args);

        if (count($result) == 0) {
            echo json_encode(array("errors" => [
                    "No results !"
                ])
            );
            exit;
        }

        return $result;
    }

    protected function create(): array
    {
        return $this->object->create($_POST);
    }

    protected function update()
    {
        $this->object->update($_POST);
        return [];
    }

    protected function delete()
    {
        $this->object->delete($_POST["id"]);
        return [];
    }

    public function route(array $pathArr): array
    {
        switch ($pathArr[1]) {
            case "ReadAll":
                return $this->readAll($pathArr);

            case "Read":
                return $this->read($pathArr);

            case "Create":
                return $this->create();

            case "Update":
                return $this->update();

            case "Delete":
                return $this->delete();

            default:
                echo json_encode(array("errors" => [
                        "None or invalid path indicated !"
                    ])
                );
                exit();
        }
    }
}