<?php

class User
{
    protected $tableName = 'User';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function create()

    {

        // Full texts	idUser 	isAdmin 	firstname 	lastname 	email 	phonenumber 	inscriptionDate 	lastlogin 	isValidated 	password 	verificationCode 	publicKey 	token 
        $userDependencies = ['firstname', 'lastname', 'email', 'phonenumber', 'password'];

        foreach ($userDependencies as $dep) {
            if (!isset($_POST[$dep])) return json_encode(['status' => 401, 'error' => "Please Specify a $dep"]);
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 401, 'error' => 'Invalide Email Format']);

            exit();
        }

        $user = Request::Prepare('SELECT idUser FROM ' . $this->tableName . ' WHERE email = ?', [$_POST['email']], $this->conn)->fetch(PDO::FETCH_ASSOC);

        if ($user) return json_encode(['status' => 401, 'error' => 'User already exist']);

        $password = UserRights::encrypt($_POST['password']);
        $token = User::RandomString(50);
        $emailCode = User::RandomString(20);

        Request::Prepare('INSERT INTO ' . $this->tableName . ' (`firstname`, `lastname`, `email`, `phonenumber`, `isValidated`, `password`, `verificationCode`, `token`, `isAdmin`) VALUES (?,?,?,?,?,?,?,?,?)', array(
            $_POST['firstname'],
            $_POST['lastname'],
            $_POST['email'],
            $_POST['phonenumber'],
            0,
            $password,
            $emailCode,
            $token,
            isset($_POST['assoc']) ? 2 : 0
        ), $this->conn);


        return json_encode(['status' => 201]);
    }

    static function RandomString(int $length = 10)
    {
        //https://stackoverflow.com/questions/4356289/php-random-string-generator

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzA_-BCDEFGHIJKLMNOPQRSTUVWXYZ)';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    private function Promote()
    {
        UserRights::UserAdmin($this->conn);



        Request::Prepare('UPDATE `User` SET `isAdmin` = 1 WHERE `User`.`idUser` = ?', [$_POST['idUser']], $this->conn);

        return json_encode(array("status" => 201));
    }

    private function Unpromote()
    {
        UserRights::UserAdmin($this->conn);



        Request::Prepare('UPDATE `User` SET `isAdmin` = 0 WHERE `User`.`idUser` = ?', [$_POST['idUser']], $this->conn);

        return json_encode(array("status" => 201));
    }

    private function update()
    {
        $user = UserRights::UserInfo($this->conn);


        $userDependencies = ['firstname', 'lastname', 'email', 'phonenumber', 'password', 'publicKey'];

        $update = [];
        foreach ($userDependencies as $dep) {
            if (isset($_POST[$dep])) {
                if ($dep === 'password') $_POST['password'] = UserRights::encrypt($_POST['password']);
                array_push($update, "$dep = '$_POST[$dep]'");
            }
        }

        $update = implode(", ", $update);
        $token = User::RandomString(50);
        $update .= ", token = '" . $token . "'";

        Request::Prepare('UPDATE `User` SET ' . $update . " WHERE idUser = " . $user['idUser'], [], $this->conn);

        return json_encode(array("status" => 201, "token" => $token));
    }

    public function route(array $route)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route[1]) && $route[1] === "Promote") {
            return $this->Promote();
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route[1]) && $route[1] === "Unpromote") {
            return $this->Unpromote();
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route[1]) && $route[1] === "Update") {
            return $this->update();
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
