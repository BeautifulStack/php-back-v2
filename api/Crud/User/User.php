<?php

require_once "api/Crud/CrudInterface.php";
require_once "api/Crud/CrudClass.php";

class User extends CrudClass implements CrudInterface
{
    protected $name = "user";
    protected $key = "idUser";
    protected $attributes = [
        "idUser",
        "firstName",
        "lastName",
        "password",
        "email",
        "phoneNumber",
        "inscriptionDate",
        "lastLoginDate",
        "isValidated",
        "isAdmin",
        "greenCoinsBalance",

    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO user(firstName, lastName, password, email, phoneNumber, lastLoginDate, isValidated, isAdmin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["firstName"],
            $args["lastName"],
            $args["password"],
            $args["email"],
            $args["phoneNumber"],
            $args["lastLoginDate"],
            $args["isValidated"],
            $args["isAdmin"]
            ]);
    }
}
