<?php

class HandlerOffer extends HandlerImage
{


    public function route(array $pathArr): array
    {
        if ($pathArr[1] === "History") return $this->getHistory($pathArr);
        if ($pathArr[1] === "All") return $this->getOffer($pathArr);
        if ($pathArr[1] === "CounterOffer") return $this->CounterOffer($pathArr);
        return parent::route($pathArr);
    }

    public function update(): array
    {

        if ($_POST["isAccepted"] === 1) {
            $object = new Product($this->object->conn);
            $products = new HandlerProduct($object);
            $offer = $this->object->where(["idOffer" => $_POST["id"]])[0];
            var_dump($offer);

            $args = array("idOffer" => $offer["idOffer"], "idWarehouse" => 1, "idModel" => $offer["idModel"], "isDelivered" => 0, "conditionProduct" => isset($offer["conditionProduct"]) ? $offer["conditionProduct"] : "", "disponibility" => 1);
            $products->object->create($args);
        }

        return parent::update();
    }

    protected function CounterOffer(): array
    {
        $args = [];
        $args["price"] = $_POST["price"];
        $args["conditionOffer"] = $_POST["conditionOffer"];
        $args["idModel"] = $_POST["idModel"];

        $needed = ["price", "conditionOffer", "idModel"];
        foreach ($needed as $arg) {
            if (!array_key_exists($arg, $_POST)) {
                return array("errros" => "missing field $arg");
                exit();
            }
        }

        if (!isset($_SESSION["id"])) {
            return array("errros" => "You must be logged In");
            exit();
        }

        $created = $this->object->create($args);
        $new_id = $created["id"];

        $this->object->update(["id" => $_POST['id'], "counterOffer" => $new_id, "isAccepted" => 2]);
        return array("id" => $new_id);
    }

    protected function getOffer(): array
    {
        $offers = $this->object->where(["idUser" => $_SESSION['id']]);
        $tmpOffers = array();
        foreach ($offers as $offer) {

            $offer["idUser"] = "You";
            array_push($tmpOffers, $offer);
        }

        $newArray = array();
        foreach ($tmpOffers as $offer) {

            if ($offer["counterOffer"] != NULL) {
                $offer["counterOffer"] = $this->getHistoryById($offer["counterOffer"]);
                // var_dump($offer);
            }
            array_push($newArray, $offer);
        }
        return array("content" => $newArray);
    }

    public function readAll(array $pathArr): array
    {
        $db = new Database();
        $handler = new User($db->conn);
        $user = new HandlerUser($handler);
        $userSelected = $user->object->where(["idUser" => $_SESSION['id']]);
        $userSelected = end($userSelected);
        if ($userSelected["isAdmin"] === "1") {
            return array("content" => parent::readAll($pathArr));
        } else {
            return array("errors" => "You need to be an admin");
        }
    }

    protected function getHistoryById(int $id): array
    {
        $db = new Database();
        $handler = new User($db->conn);
        $users = new HandlerUser($handler);

        $result = [];

        while (True) {
            $res = $this->object->where(["idOffer" => $id])[0];
            $user = $users->object->where(["idUser" => $res["idUser"]])[0];

            if ($user["isAdmin"] == "1") {
                $res["idUser"] = "Us";
            } else {
                $res["idUser"] = "You";
            }
            array_push($result, $res);
            if (!isset($res["counterOffer"])) break;
            $id = $res["counterOffer"];
        }

        return $result;
    }

    protected function getHistory(): array
    {
        $currentId = $_GET['id'];
        return $this->getHistoryById($currentId);
    }
}
