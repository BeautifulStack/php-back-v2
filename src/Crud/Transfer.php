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
        $args = $this->check_attributes_create($args, $this->attributes, $this->key);

        $query = $this->conn->prepare("INSERT INTO transfer(amount, idUser, idProject) VALUES (?, ?, ?)");
        $query->execute([
            $args["amount"],
            $args["idUser"],
            $args["idProject"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
