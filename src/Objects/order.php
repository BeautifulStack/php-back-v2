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

    public function getById(int $id)
    {

        $idUser = UserRights::UserInfo($this->conn);

        $products = Request::Prepare('SELECT 
        date, `buy`.idBuy, totalPrice, shippingAddress, payementStatus, deliveryMode, deliveryStatus, price, modelName, brandName FROM `buy` 
        INNER JOIN buyedProducts ON buyedProducts.idBuy = buy.idBuy 
        INNER JOIN product ON buyedProducts.idProduct = product.idProduct 
        INNER JOIN model ON model.idModel = product.idModel 
        INNER JOIN brand ON brand.idBrand = model.idBrand 
        WHERE buy.idBuy = ? AND buy.idUser = ? ', [$id, $idUser], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        $productsReturn = [];
        foreach ($products as $product) {
            array_push($productsReturn, ["price" => $product['price'], "modelName" => $product['modelName'], "brandName" => $product['brandName']]);
        }


        return json_encode(['status' => 201, 'order' => [
            "idBuy" => $products[0]['idBuy'],
            "totalPrice" => $products[0]['totalPrice'],
            "shippingAddress" => $products[0]['shippingAddress'],
            "payementStatus" => $products[0]['payementStatus'],
            "deliveryMode" => $products[0]['deliveryMode'],
            "deliveryStatus" => $products[0]['deliveryStatus'],
            "date" => $products[0]['date'],
            "products" => $productsReturn
        ]]);
    }

    public function route(array $route)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($route[1]) && $route[1] !== "") return $this->getById($route[1]);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
    }
}
