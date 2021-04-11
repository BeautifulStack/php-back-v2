<?php

class HandlerLogo extends Handler
{
    protected $filename;

    public function __construct($object, string $filename)
    {
        parent::__construct($object);
        $this->filename = $filename;
    }

    protected function create()
    {
        if (!array_key_exists("logo", $_FILES)) {
            echo json_encode(array("errors" => [
                    "Missing file !"
                ])
            );
            exit;
        }

        $path = $this->object->name."/".strtolower($_POST[$this->filename]);

        $this->upload_file($path, "logo");

        parent::create();
    }
}