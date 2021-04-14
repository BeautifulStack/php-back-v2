<?php


class HandlerImage extends Handler
{

    protected function readAll(array $pathArr): array
    {
        $result = parent::readAll($pathArr);

        if (!array_key_exists("errors", $result)) {
            foreach ($result as $key => $row) {
                $id = $row[$this->object->key];
                $db = new Database();
                $image = new Image($db->conn);
                $attr = $image->read_by_id($id, $this->object->key);
                $result[$key]["images"] = $attr;
            }
        }

        return $result;
    }

    protected function read(array $pathArr)
    {
        $result = parent::read($pathArr);

        if (!array_key_exists("errors", $result)) {
            $id = $result[0][$this->object->key];
            $db = new Database();
            $image = new Image($db->conn);
            $attr = $image->read_by_id($id, $this->object->key);
            $result[0]["images"] = $attr;
        }

        return $result;
    }

    protected function create(): array
    {
        if (count($_FILES) == 0) {
            echo json_encode(array("errors" => [
                    "File missing !"
                ])
            );
            exit();
        } elseif (count($_FILES) > 3) {
            echo json_encode(array("errors" => [
                    "Maximum files allowed is 3 !"
                ])
            );
            exit();
        }

        $result = parent::create();

        $db = new Database();
        $image = new Image($db->conn);

        foreach ($_FILES as $key => $value) {

            $path = "data/".$this->object->name."/".md5(rand());
            $path = $this->upload_file($path, $key);

            $image->create([
                "key" => $this->object->key,
                "path" => $path,
                "idRefer" => $result["id"]
            ]);
        }

        return [];
    }

    protected function update()
    {

        return parent::update();
    }
}