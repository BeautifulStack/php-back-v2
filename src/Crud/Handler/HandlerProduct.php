<?php

class HandlerProduct extends Handler
{
    protected function readAll(array $pathArr): array
    {
        $result = parent::readAll($pathArr);
        $result = $this->object->matchModelBrand($result);

        return $result;
    }

    protected function read(array $pathArr)
    {
        $result = parent::read($pathArr);

        return $result;
    }
}
