<?php

class Products
{
    protected $tableName = 'product';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function get()
    {

        $where = "";
        if (isset($_GET['category'])) {
            $where .= " AND `category`.`idCategory` = '" . $_GET['category'] . "'";
        }
        if (isset($_GET['brand'])) {
            $where .= " AND `brand`.`idBrand` = '" . $_GET['brand'] . "'";
        }



        $products = Request::Prepare('SELECT `originalPrice`, `resellPrice`, `modelName`, `brandName`, `categoryName`, `idProduct` FROM ' . $this->tableName . ' 
        INNER JOIN `model` ON `model`.`idModel` = `product`.`idModel` 
        INNER JOIN `brand` ON `brand`.`idBrand` = `model`.`idBrand` 
        INNER JOIN `category` ON `category`.`idCategory` = `model`.`idCategory` 
        WHERE `product`.`status` = "available" ' . $where, [], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'products' => $products]);
    }

    private function addToCart()
    {
        $user = UserRights::UserInfo($this->conn);

        if (!isset($_POST['idProduct'])) return json_encode(['status' => 401, 'errors' => 'No product ID']);

        $result = Request::Prepare('SELECT * FROM InCart WHERE idUser = ? AND idProduct = ?', [$user, $_POST['idProduct']], $this->conn)->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            Request::Prepare('INSERT INTO InCart (idUser, idProduct) VALUES (? , ?)', [$user, $_POST['idProduct']], $this->conn);
        }


        return json_encode(['status' => 201, 'product' => $_POST['idProduct']]);
    }

    private function RemoveFromCart()
    {
        $user = UserRights::UserInfo($this->conn);

        if (!isset($_POST['idProduct'])) return json_encode(['status' => 401, 'errors' => 'No product ID']);

        Request::Prepare('DELETE FROM InCart WHERE idUser = ? AND idProduct = ?', [$user['idUser'], $_POST['idProduct']], $this->conn);

        return json_encode(['status' => 201]);
    }

    private function Cart()
    {
        $user = UserRights::UserInfo($this->conn);

        $results = Request::Prepare("SELECT `originalPrice`, `resellPrice`, `modelName`, `brandName`, `categoryName`, `InCart`.`idProduct` FROM `InCart`
        INNER JOIN `product`  ON  `product`.`idProduct` = `InCart`.`idProduct`
        INNER JOIN `model` ON `model`.`idModel` = `product`.`idModel` 
        INNER JOIN `brand` ON `brand`.`idBrand` = `model`.`idBrand` 
        INNER JOIN `category` ON `category`.`idCategory` = `model`.`idCategory` 
        WHERE `product`.`status` = 'available' AND `InCart`.`idUser` = ?", [$user['idUser']], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, "products" => $results]);
    }

    public function route(array $route)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($route[1]) && $route[1] === "Cart") return $this->Cart();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route[1]) && $route[1] === "AddToCart") return $this->addToCart();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($route[1]) && $route[1] === "Remove") return $this->RemoveFromCart();
    }
}
