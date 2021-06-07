<?php

class Order
{
    protected $tableName = 'buy';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function create(array $values)
    {
        Request::Prepare('INSERT INTO `buy` (`idUser`, `totalPrice`, `shippingAddress`, `payementStatus`) VALUES (?,?,?,?)', array_values($values), $this->conn);

        return Request::Last_Id($this->conn)['id'];
    }
}
