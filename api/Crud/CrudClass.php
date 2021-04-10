<?php

class CrudClass
{
    protected $conn;
    protected $name;
    protected $key;
    protected $attributes;

    // Link database connection
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /// Check if inputs arguments are valid
    ///
    /// return errors if invalid
    /// if empty return all attributes
    protected function check_attributes(array $args): array
    {
        $response = [];

        if (empty($args)) {
            return $this->attributes;
        }

        // check if attributes selection is correct
        foreach ($args as $arg) {
            if (!in_array($arg, $this->attributes)){
                // Attributes selection error
                $response["errors"][] = "'".$arg."' not found in ".$this->name." attributes";
            }
        }

        if (!empty($response)){
            // Return error
            echo json_encode($response);
            exit();
        }

        return $args;
    }

    /// Check if inputs arguments are valid FOR CREATION
    ///
    /// return errors if invalid
    /// if empty return all attributes
    protected function check_attributes_create(array $args, int $len): array
    {
        $response = [];

        if (empty($args)) {
            return array_keys($this->attributes);
        }

        if (count($args) != $len) {
            $response["errors"][] = "Not enough valid arguments provided";

            // Return error
            echo json_encode($response);
            exit();
        }

        return $args;
    }

    protected function check_attributes_update(array $args)
    {
        $response = [];

        // check if attributes selection is correct
        foreach ($args as $arg) {
            if (!in_array($arg, $this->attributes)){
                // Attributes selection error
                $response["errors"][] = "'".$arg."'not found in ".$this->name." attributes";
            }
        }

        if (!empty($response)){
            // Return error
            echo json_encode($response);
            exit();
        }
    }

    public function readAll(array $args): array
    {
        $args = $this->check_attributes($args);
        $statement = implode(',', $args);

        $query = $this->conn->query("SELECT ".$statement." FROM ".$this->name);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read(array $args): array
    {
        $id = array_splice($args, 0, 1);

        $args = $this->check_attributes($args);
        $statement = implode(',', $args);

        $query = $this->conn->query("SELECT ".$statement." FROM ".$this->name." WHERE ".$this->key." = ".$id[0]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(array $args)
    {
        $id = $args[0];
        unset($args[0]);

        $this->check_attributes_update(array_keys($args));

        foreach ($args as $key => $value) {
            $query = $this->conn->prepare("UPDATE ".$this->name." SET ".$key." = ? WHERE `".$this->key."` = ".$id);
            $query->execute([$value]);
        }
    }

    public function delete(int $item)
    {
        $this->conn->query("DELETE FROM ".$this->name." WHERE ".$this->key." = ".$item);
    }
}