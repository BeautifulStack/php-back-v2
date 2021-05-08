<?php

class HandlerUser extends Handler
{
    protected function readAll(array $pathArr): array
    {
        $result = parent::readAll($pathArr);

        $result = $this->filterPassword($result);

        return $result;
    }

    protected function read(array $pathArr)
    {
        $result = parent::read($pathArr);

        $result = $this->filterPassword($result);

        return $result;
    }

    protected function create(): array
    {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(
                array("errors" => [
                    "Invalid email format !"
                ])
            );
            exit();
        }

        $result = $this->object->where(["email" => $_POST['email']]);
        if (count($result) != 0) {
            echo json_encode(
                array("errors" => [
                    "Email already exists !"
                ])
            );
            exit();
        }



        $_POST["password"] = HandlerUser::encrypt($_POST["password"]);
        return $this->object->create($_POST);
    }

    static function encrypt(string $password): string
    {
        $salts = ["Hello", "World", "We", "Love", "fairrepack"];

        foreach ($salts as $salt) {
            $password = hash("sha256", $password . $salt);
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

    static function filterPassword(array $args = []): array
    {
        $retrunValue = [];
        foreach ($args as $row) {
            if (isset($row['password'])) unset($row['password']);
            $retrunValue[] = $row;
        }
        return $retrunValue;
    }
}
