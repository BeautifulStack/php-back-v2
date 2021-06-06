<?php

class Offer
{
    protected $tableName = 'offer';


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function get()
    {
        $idUser = UserRights::UserInfo($this->conn);

        $offers = Request::Prepare('SELECT * FROM `sell` WHERE idUser = ? ORDER BY dateProposition DESC', [$idUser], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'offers' => $offers]);
    }

    public function create()

    {
        $idUser = UserRights::UserInfo($this->conn);

        Request::Prepare('INSERT INTO sell (idUser) VALUES (?); ', array($idUser), $this->conn);

        $brandId = Request::Last_Id($this->conn);

        return json_encode(['status' => 201, 'category' => $brandId['id']]);
    }

    public function createOffer()
    {
        $idUser = UserRights::UserInfo($this->conn);

        if (!isset($_POST['idSell'])) {
            return json_encode(['status' => 400, 'error' => 'Please specify a Sell']);
        }

        // Full texts	idOffer	idSell	idUser	price	comment	productState	idModel	proposedBy	status	order

        if (!isset($_POST['price'])) {
            return json_encode(['status' => 400, 'error' => 'Please specify a price']);
        }
        if (!isset($_POST['comment'])) {
            return json_encode(['status' => 400, 'error' => 'Please specify a comment']);
        }
        if (!isset($_POST['productState'])) {
            return json_encode(['status' => 400, 'error' => 'Please specify a productState']);
        }
        if (!isset($_POST['idModel'])) {
            return json_encode(['status' => 400, 'error' => 'Please specify a idModel']);
        }



        $lastId = $this->getLastOffer($idUser, $_POST['idSell']);

        Request::Prepare('INSERT INTO `offer` (`idSell`, `idUser`, `price`, `comment`, `productState`, `idModel`, `proposedBy`, `status`, `order`) VALUES (?, ?, ?, ?, ?, ?, 1, \'waiting\', ?)', array(
            $_POST['idSell'],
            $idUser,
            $_POST['price'],
            $_POST['comment'],
            $_POST['productState'],
            $_POST['idModel'],
            $lastId + 1
        ), $this->conn);


        $offerId = Request::Last_Id($this->conn);

        return json_encode(['status' => 201, 'propositionId' => $offerId['id']]);
    }

    private function getLastOffer(int $idUser, int $idSell)
    {
        return Request::Prepare('SELECT MAX(`order`) as lastOrder FROM offer WHERE idSell = ? AND idUser = ? ;', array($idSell, $idUser), $this->conn)->fetch(PDO::FETCH_ASSOC)['lastOrder'];
    }

    private function getProposition(int $idSell)
    {
        $idUser = UserRights::UserInfo($this->conn);

        $props = Request::Prepare('SELECT * FROM `offer` WHERE `idSell` = ? AND `idUser` = ? ORDER BY `order` DESC', [$idSell, $idUser], $this->conn)->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['status' => 201, 'proposition' => $props]);
    }

    private function propositionAction(int $idPorposition)

    {
        $idUser = UserRights::UserInfo($this->conn);

        if (!isset($_POST['status'])) {
            return json_encode(['status' => 400, 'error' => 'Please specify a status']);
        }

        $props = Request::Prepare('SELECT * FROM `offer` WHERE `idOffer` = ? AND `idUser` = ? ORDER BY `order` DESC', [$idPorposition, $idUser], $this->conn)->fetch(PDO::FETCH_ASSOC);
        if ($props["status"] !== 'waiting') {
            return json_encode(['status' => 400, 'error' => 'Offer Already treated']);
        }

        if ($props["proposedBy"] === 0) {
            return json_encode(['status' => 400, 'error' => 'You cannot accept your own offer']);
        }

        Request::Prepare('UPDATE `offer` SET `status` = ? WHERE `offer`.`idOffer` = ?', [$_POST['status'], $idPorposition], $this->conn);
        Request::Prepare('UPDATE `sell` SET `status` = ? WHERE `sell`.`idSell` = ?', [$_POST['status'], $props['idSell']], $this->conn);

        if ($_POST['status'] === 'accept') {
            Request::Prepare('INSERT INTO `product` (`idModel`, `idWarehouse`) VALUES (? , 1)', [$props['idModel']], $this->conn);
        }

        return json_encode(['status' => 201, 'propostition' => $idPorposition]);
    }

    public function route(array $route)
    {

        if ($route[1] === "Proposition" && (!isset($route[2]) || $route[2] === "") && $_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->createOffer();
        } else if ($route[1] === "Proposition" && $route[2] !== "" && $_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->propositionAction($route[2]);
        } else if ($route[1] !== "" && $_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->getProposition($route[1]);
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->get();
        else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
    }
}
