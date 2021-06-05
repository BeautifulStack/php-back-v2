<?php

class Brand
{
    protected $tableName = 'brand';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function get()
    {
        $brands = Request::Prepare('SELECT * FROM ' . $this->tableName, [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'brands' => $brands]);
    }

    public function create()

    {
        UserRights::UserAdmin($this->conn);
        if (!isset($_POST['brandName'])) return json_encode(['status' => 400, 'error' => 'Please specify a brand name']);
        if (!isset($_POST['logo'])) return json_encode(['status' => 400, 'error' => 'Please specify a brand name']);

        Request::Prepare('INSERT INTO ' . $this->tableName . ' (brandName, logo) VALUES (?, ?)', array($_POST['brandName'], $_POST['logo']), $this->conn);

        $brandId = Request::Last_Id($this->conn);

        return json_encode(['status' => 201, 'brand' => $brandId['id']]);
    }

    public function route()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
