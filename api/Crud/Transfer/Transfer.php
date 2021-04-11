<?php

class Transfer extends CrudClass implements CrudInterface
{
    protected  $name = "transfer";
    protected  $key = "idTransfer";
    protected  $attributes = [
        "idTransfer",
        "amount",
        "transferDate",
        "idUser",
        "idProject"
    ];
    protected $foreignKey = [
        "idUser" => ["user", "lastName"],
        "idProject" => ["project", "name"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO transfer(amount, idUser, idProject) VALUES (?, ?, ?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["amount"],
            $args["idUser"],
            $args["idProject"]
        ]);
    }
}
