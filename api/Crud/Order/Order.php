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
        "idCart",
        "billPath"
    ];
    protected $foreignKey = [
        "idCart" => ["cart", "idUser"]
    ];

    public function create(array $args)
    {
        $args = $this->check_attributes_create($args, count($this->attributes)-1);

        $query = $this->conn->prepare("INSERT INTO `order`(totalPrice, addressDest, deliveryMode, deliveryStatus, isPaid, idCart, billPath) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->execute([
            $args["totalPrice"],
            $args["addressDest"],
            $args["deliveryMode"],
            $args["deliveryStatus"],
            $args["isPaid"],
            $args["idCart"],
            $args["billPath"]
        ]);
    }
}
