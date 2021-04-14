<?php

class Promotion extends CrudClass implements CrudInterface
{
    protected $name = "promotion";
    protected $key = "idPromotion";
    protected $attributes = [
        "idPromotion",
        "name",
        "dateBegin",
        "dateEnd",
        "idUser",
    ];
    protected $foreignKey = [
        "idUser" => ["user", "lastName"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO promotion(name, dateBegin, dateEnd, idUser) VALUES (?, ?, ?, ?)");
        $query->execute([
            $args["name"],
            $args["dateBegin"],
            $args["dateEnd"],
            $args["idUser"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
