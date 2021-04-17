<?php

class HandlerLogo extends Handler
{
    protected $filename;

    public function __construct($object, string $filename)
    {
        parent::__construct($object);
        $this->filename = $filename;
    }

    protected function create(): array
    {
        if (!array_key_exists("logo", $_FILES)) {
            echo json_encode(array("errors" => [
                    "Missing file !"
                ])
            );
            exit;
        }

        $path = "data/".$this->object->name."/".str_replace(" ", "-", strtolower($_POST[$this->filename]));

        $path = $this->upload_file($path, "logo");

        $_POST["logo"] = $path;

        return parent::create();
    }

    protected function update()
    {
        if (!array_key_exists("id", $_POST)) {
            echo json_encode(array("errors" => [
                    "Missing id !"
                ])
            );
            exit;
        }

        if (array_key_exists("logo", $_FILES)) {
            $path = $this->delete_file($_POST["id"], "logo");

            if (array_key_exists($this->filename, $_POST)) {
                $path = "data/".$this->object->name."/".str_replace(" ", "-", strtolower($_POST[$this->filename]));
            }

            $path = $this->upload_file($path, "logo");
            $_POST["logo"] = $path;
        }

        parent::update();

        return [];
    }

    protected function delete()
    {
        if (!array_key_exists("id", $_POST)) {
            echo json_encode(array("errors" => [
                    "Missing id !"
                ])
            );
            exit;
        }

        $this->delete_file($_POST["id"], "logo");
        parent::delete();

        return [];
    }
}