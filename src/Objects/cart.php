<?php

class Cart
{
    protected $tableName = 'InCart';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function GetCart(int $idUser): array
    {
        return Request::Prepare('SELECT * FROM InCart WHERE idUser = ?', [$idUser], $this->conn)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function SetBuy(array $idProduct, int $idUser, int $orderId)
    {
        foreach ($idProduct as $product) {
            Request::Prepare('UPDATE `product` SET `status` = "notavailable" WHERE `product`.`idProduct` = ?', [$product['idProduct']], $this->conn);
            Request::Prepare('INSERT INTO `buyedProducts` (`idBuy`, `price`, `idProduct`) VALUES (?, ?, ?)', [$orderId, 150, $product['idProduct']], $this->conn);
        }
    }
}
