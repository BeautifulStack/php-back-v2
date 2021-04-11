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

        $query = $this->conn->prepare("INSERT INTO promotion(name, dateBegin, dateEnd, idUser) VALUES (?, ?, ?, ?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["name"],
            $args["dateBegin"],
            $args["dateEnd"],
            $args["idUser"]
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
