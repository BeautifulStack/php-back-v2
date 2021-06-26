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
            $prodInfos = Request::Prepare('SELECT resellPrice FROM `product` INNER JOIN model ON model.idModel = product.idModel WHERE `idProduct` = ? ', [$product['idProduct']], $this->conn)->fetch(PDO::FETCH_ASSOC);
            Request::Prepare('INSERT INTO `buyedProducts` (`idBuy`, `price`, `idProduct`) VALUES (?, ?, ?)', [$orderId, $prodInfos['resellPrice'], $product['idProduct']], $this->conn);
        }
        $this->Delete($idUser);
    }

    public function Delete(int $idUser)
    {
        Request::Prepare('DELETE FROM `InCart` WHERE `InCart`.`idUser` = ?', [$idUser], $this->conn);
    }
}
