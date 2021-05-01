<?php

class HandlerUser extends Handler
{
    protected function readAll(array $pathArr): array
    {
        $result = parent::readAll($pathArr);

        $retrunValue = [];
        foreach ($result as $row) {
            unset($row['password']);
            $retrunValue[] = $row;
        }

        return $retrunValue;
    }

    protected function read(array $pathArr)
    {
        $result = parent::read($pathArr);

        $retrunValue = [];
        foreach ($result as $row) {
            if (isset($row['password'])) unset($row['password']);
            $retrunValue[] = $row;
        }

        return $result;
    }

    protected function create(): array
    {
        $result = $this->object->read_by_email($_POST['email']);
        if (count($result) != 0) {
            echo json_encode(array("errors" => [
                    "User already exists !"
                ])
            );
            exit();
        }

        $_POST["password"] = $this->encrypt($_POST["password"]);
        return $this->object->create($_POST);

    }

    private function encrypt(string $password): string
    {
        $salts = ["Hello", "World", "We", "Love", "fairrepack"];

        foreach ($salts as $salt) {
            $password = hash("sha256", $password.$salt);
        }

        return $password;
    }
}

