<?php

class HandlerOffer extends HandlerImage
{


    public function route(array $pathArr): array
    {
        if ($pathArr[1] === "History") return $this->getHistory($pathArr);
        return parent::route($pathArr);
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
