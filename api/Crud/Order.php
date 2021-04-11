<?php

class Order extends CrudClass implements CrudInterface
{
    protected $name = "order";
    protected $key = "idOrder";
    protected $attributes = [
        "idOrder",
        "totalPrice",
        "addressDest",
        "deliveryMode",
        "deliveryStatus",
        "isPaid",
        "orderDate",
        "idCart"
    ];
    protected $foreignKey = [
        "idCart" => ["cart", "idUser"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO `order`(totalPrice, addressDest, deliveryMode, deliveryStatus, isPaid, idCart) VALUES (?, ?, ?, ?, ?, ?); SELECT LAST_INSERT_ID() as id;");
        $query->execute([
            $args["totalPrice"],
            $args["addressDest"],
            $args["deliveryMode"],
            $args["deliveryStatus"],
            $args["isPaid"],
            $args["idCart"]
        ]);
        //return $query->fetch(PDO::FETCH_ASSOC);
    }
}
