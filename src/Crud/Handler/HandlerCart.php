<?php

class HandlerCart extends Handler
{
    protected function readAll(array $pathArr): array
    {
        if (isset($_SESSION["id"])) {
            $result = $this->object->where(["idUser" => $_SESSION["id"]]);
            return $result;
        } else {
            echo json_encode(array("errors" => [
                "Please Login Before"
             ]));
             exit();
        }
    }
}
