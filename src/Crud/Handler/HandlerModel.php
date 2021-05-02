<?php

require_once "src/Crud/Handler/HandlerImage.php";

class HandlerModel extends HandlerImage
{
    protected function readAll(array $pathArr): array
    {
        $result = parent::readAll($pathArr);

        if (!array_key_exists("errors", $result)) {
            foreach ($result as $key => $row) {
                $id = $row["idModel"];
                $db = new Database();
                $caract_value = new CaractValue($db->conn);
                $attr = $caract_value->read_by_id($id);
                $result[$key]["attributes"] = $attr;
            }
        }

        return $result;
    }

    protected function read(array $pathArr)
    {
        $result = parent::read($pathArr);

        if (!array_key_exists("errors", $result)) {
            $id = $result[0]["idModel"];
            $db = new Database();
            $caract_value = new CaractValue($db->conn);
            $attr = $caract_value->read_by_id($id);
            $result[0]["attributes"] = $attr;
        }

        return $result;
    }

    protected function create(): array
    {
        $result = parent::create();

        $db = new Database();
        $caract_value = new CaractValue($db->conn);

        foreach ($_POST["caractValue"] as $key => $value) {
            $caract_value->create([
                "caractName" => $key,
                "caractValue" => $value,
                "idModel" => $result["id"]
            ]);
        }

        return $result;
    }

    protected function update(): array
    {

        if (array_key_exists("caractValue", $_POST)) {
            $caractArr = $_POST["caractValue"];
            unset($_POST["caractValue"]);

            $db = new Database();
            $caract_value = new CaractValue($db->conn);

            foreach ($caractArr as $key => $value) {
                $res = $caract_value->read_id_by_name($_POST["id"], $key);
                if (count($res) == 0) {
                    echo json_encode(array("errors" => [
                            $key . " not found !"
                        ])
                    );
                    exit();
                }

                $caract_value->update([
                    "id" => $res[0]["idCaract"],
                    "caractValue" => $value
                ]);

            }
        }

        parent::update();

        return [];
    }

    protected function delete(): array
    {
        return parent::delete();
    }
}
