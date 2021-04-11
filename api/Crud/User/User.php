<?php

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
        "isValidated",
        "isAdmin",
        "greenCoinsBalance"
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-3);

        $query = $this->conn->prepare("INSERT INTO user(firstName, lastName, password, email, phoneNumber, isValidated, isAdmin) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["firstName"],
            $args["lastName"],
            $args["password"],
            $args["email"],
            $args["phoneNumber"],
            $args["isValidated"],
            $args["isAdmin"]
            ]);
    }
}
