<?php


class HandlerModel extends Handler
{
    protected function readAll(array $pathArr): array
    {
        $result = parent::readAll($pathArr);

        if (!array_key_exists("errors", $result)) {
            foreach ($result as $row) {
                $id = $row["idModel"];
                $db = new Database();
                $caract_value = new CaractValue($db->conn);
                $attr = $caract_value->read_by_id($id);
                $row["attributes"] = $attr;
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

    protected function create()
    {
        //$result = parent::create();

        var_dump($_POST);

        return [];
    }
}