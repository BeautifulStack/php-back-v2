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

    public function get()
    {

        $idUser = UserRights::UserInfo($this->conn);
        $orders = Request::Prepare('SELECT * FROM `buy` WHERE idUser = ?', [$idUser], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'orders' => $orders]);
    }

    public function route(array $route)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
    }
}
