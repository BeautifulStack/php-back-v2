<?php

class UserRights
{
    public static function UserAdmin(PDO $conn)
    {
        if (!isset($_SERVER['HTTP_FAIRREPACK_TOKEN'])) {
            echo json_encode(['status' => 401, 'error' => 'Please Sign up to do this action']);
            exit();
        }
        $request = UserRights::GetUser($conn);

        if (!isset($request['isAdmin']) || $request['isAdmin'] == 0) {
            echo json_encode(['status' => 401, 'error' => 'Unauthorized Action']);
            exit();
        }
    }

    public static function UserInfo(PDO $conn)
    {
        if (!isset($_SERVER['HTTP_FAIRREPACK_TOKEN'])) {
            echo json_encode(['status' => 401, 'error' => 'Please Sign up to do this action']);
            exit();
        }
        $request = UserRights::GetUser($conn);
        if (!isset($request['idUser'])) {
            echo json_encode(['status' => 401, 'error' => 'No corresponding User']);
            exit();
        }
        return $request['idUser'];
    }

    public static function GetUser(PDO $conn)
    {
        return Request::Prepare('SELECT idUser, isAdmin FROM User WHERE token = ?', [$_SERVER['HTTP_FAIRREPACK_TOKEN']], $conn)->fetch(PDO::FETCH_ASSOC);
    }

    public static function Login(PDO $conn)
    {
        if (!isset($_POST['email'])) {
            echo json_encode(['status' => 401, 'error' => 'Please Specify Email']);
            exit();
        }
        if (!isset($_POST['password'])) {
            echo json_encode(['status' => 401, 'error' => 'Please Specify Password']);
            exit();
        }

        $result = Request::Prepare('SELECT token FROM User WHERE email = ? AND password = ?', [$_POST['email'], UserRights::encrypt($_POST['password'])], $conn)->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    static function encrypt(string $password): string
    {
        $salts = ["Hello", "World", "We", "Love", "fairrepack"];

        foreach ($salts as $salt) {
            $password = hash("sha256", $password . $salt);
        }

        return $password;
    }

    static function getPrice(PDO $conn): array
    {

        $result = Request::Prepare('SELECT resellPrice FROM `InCart` INNER JOIN product ON InCart.idProduct = product.idProduct INNER JOIN model ON product.idModel = model.idModel WHERE idUser = ?', [UserRights::UserInfo($conn)], $conn);

        $total = 0;
        foreach ($result as $row) {
            $total += $row["resellPrice"];
        }

        $total = round($total);
        return array("price" => $total);
    }
}
