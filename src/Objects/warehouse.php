<?php

class Warehouse
{
    protected $tableName = 'warehouse';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function get()
    {
        $brands = Request::Prepare('SELECT * FROM ' . $this->tableName, [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'warehouse' => $brands]);
    }

    public function getById($id)
    {
        return Request::Prepare('SELECT idWarehouse FROM ' . $this->tableName . " WHERE idWarehouse = ?", [$id], $this->conn)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create()

    {

        UserRights::UserAdmin($this->conn);
        if (!isset($_POST['maxCapacity'])) return json_encode(['status' => 400, 'error' => 'Please specify a maxCapacity']);
        if (!isset($_POST['location'])) return json_encode(['status' => 400, 'error' => 'Please specify a location']);
        if (!isset($_POST['addresse'])) return json_encode(['status' => 400, 'error' => 'Please specify a addresse']);
        if (!isset($_POST['publicKey'])) return json_encode(['status' => 400, 'error' => 'Please specify a publicKey']);
        if (!isset($_POST['ip'])) return json_encode(['status' => 400, 'error' => 'Please specify an ip']);


        $bc = new BlockchainClient();
        $bc->new_peer("10.0.0.14");

        Request::Prepare('INSERT INTO ' . $this->tableName . ' (maxCapacity, location, addresse, publicKey, ip) VALUES (?, ?, ?, ?, ?)', array(
            $_POST['maxCapacity'],
            $_POST['location'],
            $_POST['addresse'],
            $_POST['publicKey'],
            $_POST['ip'],
        ), $this->conn);

        $warehouse = Request::Last_Id($this->conn);


        return json_encode(['status' => 201, 'warehouse' => $warehouse['id']]);
    }

    public function route()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
