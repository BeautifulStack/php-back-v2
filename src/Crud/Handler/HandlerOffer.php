<?php

class HandlerOffer extends Handler
{


    public function route(array $pathArr): array
    {
        if ($pathArr[1] === "History") return $this->getHistory($pathArr);
        if ($pathArr[1] === "All") return $this->getOffer($pathArr);
        return parent::route($pathArr);
    }

    protected function getOffer(): array
    {
        return array("content" => $this->object->where(["idUser" => $_SESSION['id']]));
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

    protected function getHistory(): array

    {
        $result = [];
        $currentId = $_GET['id'];
        while (True) {
            $res = $this->object->where(["idOffer" => $currentId])[0];
            array_push($result, $res);
            if (!isset($res["counterOffer"])) break;
            $currentId = $res["counterOffer"];
        }

        return $result;
    }
}
