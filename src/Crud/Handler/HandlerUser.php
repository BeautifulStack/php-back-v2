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



        $id = $this->object->create($_POST);
        $code=substr(md5(mt_rand()),0,15);
        $message = "Your Activation Code is ".$code."";
        $to=$email;
        $subject="Activation Code for FairRepack";
        $from = 'your email';
        $body='your activation code is '.$code.' Please Click on this link Verify.php?id='.$id['id'].'$code='.$code.'</a>to activate your account';
        $headers = "From:".$from;
        mail($to,$subject,$body,$headers);

        echo "An activation code is sent to you emails ";


        $_POST["password"] = HandlerUser::encrypt($_POST["password"]);
        return $id['id'];
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
