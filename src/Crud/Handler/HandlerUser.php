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
        $_POST["password"] = HandlerUser::encrypt($_POST["password"]);

        return $this->object->create($_POST);
    }

    static function encrypt(string $password): string
    {
        $salts = ["Hello", "World", "We", "Love", "fairrepack"];

        foreach ($salts as $salt) {
            $password = hash("sha256", $password.$salt);
        }

        return $password;
    }

    static function login(): int
    {
        if (isset($_SESSION["id"])) {
            return $_SESSION["id"];
        } else {
            $db = new Database();
            $object = new User($db->conn);

            if (!isset($_POST['email'])) {
                return -1;
            }
            $email = $_POST['email'];
            $result = $object->where(["email" => $email]);

            if (!isset($result[0]) || !isset($_POST['password'])) {
                return -1;
            } else {
                
                if ($result[0]["password"] == HandlerUser::encrypt($_POST['password'])) {
                    $_SESSION["id"] = $result[0]["idUser"];
                    return $result[0]["idUser"];
                } else {
                    return -1;
                }

            }
            
            
        }
        return -1;
    }
}