<?php

class Offer extends CrudClass implements CrudInterface
{
    public $name = "offer";
    public $key = "idOffer";
    protected $attributes = [
        "idOffer",
        "dateOffer",
        "price",
        "conditionOffer",
        "isAccepted",
        "idModel",
        "counterOffer",
        "idUser"
    ];
    protected $foreignKey = [
        "idModel" => ["product_model", "modelName"],
        //"counterOffer" => ["offer", "idOffer"],
        "idUser"  => ["user", "lastName"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, $this->attributes, $this->key, ["dateOffer", "counterOffer"]);

        if (!array_key_exists("counterOffer", $args)) $args["counterOffer"] = NULL;

        $query = $this->conn->prepare("INSERT INTO offer(price, conditionOffer, isAccepted, idModel, counterOffer, idUser) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["price"],
            $args["conditionOffer"],
            $args["isAccepted"],
            $args["idModel"],
            $args["counterOffer"],
            $args["idUser"]
        ]);

        $query = $this->conn->query("SELECT LAST_INSERT_ID() as id");
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}