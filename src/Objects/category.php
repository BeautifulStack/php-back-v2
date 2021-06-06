<?php

class Category
{
    protected $tableName = 'category';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function get()
    {
        $brands = Request::Prepare('SELECT * FROM ' . $this->tableName, [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'categories' => $brands]);
    }

    public function create()

    {
        UserRights::UserAdmin($this->conn);
        if (!isset($_POST['categoryName'])) return json_encode(['status' => 400, 'error' => 'Please specify a category name']);

        Request::Prepare('INSERT INTO ' . $this->tableName . ' (categoryName) VALUES (?)', array($_POST['categoryName']), $this->conn);

        $brandId = Request::Last_Id($this->conn);

        return json_encode(['status' => 201, 'category' => $brandId['id']]);
    }

    public function updateCategory()
    {
        UserRights::UserAdmin($this->conn);

        if (!isset($_POST['categoryName'])) return json_encode(['status' => 400, 'error' => 'Please specify a categoryName']);
        if (!isset($_POST['idCategory'])) return json_encode(['status' => 400, 'error' => 'Please specify a idCategory']);

        Request::Prepare('UPDATE ' . $this->tableName . ' SET categoryName = ? WHERE idCategory = ?', array($_POST['categoryName'], $_POST['idCategory']), $this->conn);

        return json_encode(['status' => 201, 'category' => $_POST['categoryName']]);
    }

    public function route(array $route)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route[1]) && $route[1] !== '') {
            return $this->updateCategory();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
