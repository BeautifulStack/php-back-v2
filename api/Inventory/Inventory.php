<?php

// 0: No result -> Do nothing
// 1: Invalid warehouse id -> exit with error

class Inventory
{
    public static function handle_daily($pathArr)
    {
        // Check if valid id warehouse
        $db = new Database();
        $warehouse = new Warehouse($db->conn);
        $result = $warehouse->read([$pathArr[1]]);
        if (count($result) == 0) {
            return 1;
        }


        $today = date("Y-m-d");

        $product = new Product($db->conn);
        $result = $product->read_import_by_date_and_warehouse($today, $pathArr[1]);

        if (count($result) == 0) {
            return 0;
        }

        header("Content-Type: application/xml");

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0','UTF-8');
        $writer->setIndent(4);

        $writer->startElement("products");
        $writer->startElement("imports");
        foreach ($result as $value) {
            $writer->startElement("products");
            $writer->writeElement('id', $value["idProduct"]);
            $writer->writeElement('id', $value["conditionProduct"]);
            $writer->writeElement('id', $value["modelName"]);
            $writer->writeElement('id', $value["location"]);
            $writer->writeElement('id', $value["idOffer"]);
            $writer->endElement();
        }
        $writer->endElement(); // end imports

        $writer->startElement("exports");
        $writer->endElement(); // end exports

        $writer->endElement(); // end products

        return "";
    }
}