<?php

class Model
{
    protected $tableName = 'model';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function get()
    {
        $where = [];
        if (isset($_GET['brand'])) array_push($where, 'idBrand = ?');
        if (isset($_GET['category'])) array_push($where, 'idCategory = ?');

        $where = implode(' AND ', $where);

        $products = Request::Prepare('SELECT model.idBrand, modelName, brandName, idModel, model.idCategory, categoryName FROM `model` INNER JOIN brand ON brand.idBrand = model.idBrand INNER JOIN category ON category.idCategory = model.idCategory'
            . (strlen($where) > 0 ? ' WHERE ' . $where : ''), [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'models' => $products]);
    }

    public function create()

    {
        UserRights::UserAdmin($this->conn);

        // Full texts	idModel 	idBrand 	idCategory 	originalPrice 	resellPrice 	modelName 
        if (!isset($_POST['idBrand'])) return json_encode(['status' => 400, 'error' => 'Please specify a idBrand']);
        if (!isset($_POST['idCategory'])) return json_encode(['status' => 400, 'error' => 'Please specify a idCategory']);
        if (!isset($_POST['originalPrice'])) return json_encode(['status' => 400, 'error' => 'Please specify a originalPrice']);
        if (!isset($_POST['resellPrice'])) return json_encode(['status' => 400, 'error' => 'Please specify a resellPrice']);
        if (!isset($_POST['modelName'])) return json_encode(['status' => 400, 'error' => 'Please specify a modelName']);
        if (!isset($_POST['caract'])) return json_encode(['status' => 400, 'error' => 'Please specify a caract']);
        if (!isset($_FILES)) return json_encode(['status' => 400, 'error' => 'Please add Photos']);


        Request::Prepare('INSERT INTO ' . $this->tableName . ' (idBrand, idCategory, originalPrice, resellPrice, modelName) VALUES (?, ?, ?, ?, ?)', array(
            $_POST['idBrand'],
            $_POST['idCategory'],
            $_POST['originalPrice'],
            $_POST['resellPrice'],
            $_POST['modelName']
        ), $this->conn);

        $modelId = Request::Last_Id($this->conn)['id'];

        $files = $_FILES["images"];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['type'][$i] === "image/png") {
                $fileName = User::RandomString(10);
                $finalName = $fileName . $files['name'][$i];
                move_uploaded_file($files['tmp_name'][$i], DATA_PATH . "product_model/" . $finalName);
                Request::Prepare('INSERT INTO `images` (`path`, `idModel`) VALUES (? , ?)', [$finalName, $modelId], $this->conn);
            }
        }

        foreach ($_POST['caract'] as $key => $value) {
            Request::Prepare('INSERT INTO `caract` (`idModel`, `caractName`, `caractValue`) VALUES (?, ?, ?)', [$modelId, $key, $value], $this->conn);
        }

        return json_encode(['status' => 201, 'model' => $modelId]);
    }

    public function SellPossibilities()
    {
        if (!isset($_POST["idModel"])) return json_encode(['status' => 400, 'error' => 'Please specify idModel']);
        if (!isset($_POST["possibilities"])) return json_encode(['status' => 400, 'error' => 'Please specify possibilities']);

        foreach ($_POST['possibilities'] as $key => $value) {
            Request::Prepare('INSERT INTO `sellPossibilities` (`idModel`, `possibilityName`, `possibilityValues`) VALUES (?, ?, ?)', [$_POST["idModel"], $key, $value], $this->conn);
        }

        return json_encode(['status' => 201, 'model' => $_POST["idModel"]]);
    }

    public function route(array $route)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route[1]) && $route[1] === "SellPossibilities") {
            return $this->SellPossibilities();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
