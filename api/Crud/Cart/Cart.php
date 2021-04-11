<?php

class Cart extends CrudClass implements CrudInterface
{
    protected $name = "cart";
    protected $key = "idCart";
    protected $attributes = [
        "idCart",
        "idUser"
    ];
    protected $foreignKey = [
        "idUser" => ["user", "lastName"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO cart(idUser) VALUES (?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["idUser"]
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
