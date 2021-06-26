<?php

class Inventory
{
    // 0: No result -> Do nothing
    // 1: Invalid warehouse id -> exit with error
    public static function handle_daily($pathArr)
    {

        $db = new Database();

        // Check if valid id warehouse
        $warehouse = new Warehouse($db->conn);
        $result = $warehouse->getById($pathArr[1]);
        if (count($result) == 0) {
            return 1;
        }

        $today = date("Y-m-d");

        $imports = Inventory::read_import_by_date_and_warehouse($db->conn, $today, $pathArr[1]);
        $exports = Inventory::read_export_by_date_and_warehouse($db->conn, $today, $pathArr[1]);

        if (count($imports) == 0 && count($exports) == 0) {
            return 0;
        }

        header("Content-Type: application/xml");

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(4);

        $writer->startElement("products");
        if (count($imports) != 0) {
            $writer->startElement("imports");
            $writer->writeAttribute("id", $pathArr[1]);
            $writer->writeAttribute("date", $today);
            foreach ($imports as $value) {

                $writer->startElement("product");
                foreach ($value as $key => $value2) {
                    $writer->writeAttribute($key, $value2);
                }
                $writer->endElement();
            }
            $writer->endElement(); // end imports
        }

        if (count($exports) != 0) {
            $writer->startElement("exports");
            $writer->writeAttribute("id", $pathArr[1]);
            $writer->writeAttribute("date", $today);
            foreach ($exports as $value) {

                $writer->startElement("product");
                foreach ($value as $key => $value2) {
                    $writer->writeAttribute($key, $value2);
                }
                $writer->endElement();
            }
            $writer->endElement(); // end exports
        }

        $writer->endElement(); // end products

        $writer->endDocument();

        $output = $writer->outputMemory();

        $writer->flush();

        return $output;
    }

    // 0: we're good
    // 1: file not found
    // 2: not a valid file
    public static function upload_inventory(): int
    {
        if (!array_key_exists("sendfile", $_FILES)) {
            return 1;
        }

        if ($_FILES["sendfile"]["type"] != "application/xml") return 2;

        $path = "data/inventory";

        if (!file_exists($path)) {
            mkdir($path);
        }

        move_uploaded_file($_FILES["sendfile"]["tmp_name"], $path . "/" . $_FILES["sendfile"]["name"]);

        return 0;
    }

    private static function read_import_by_date_and_warehouse($conn, $day, $id): array
    {
        $request = Request::Prepare(
            "SELECT product.idProduct,model.modelName,warehouse.location
                                    FROM product
                                    INNER JOIN model ON product.idModel = model.idModel
                                    INNER JOIN warehouse ON product.idWarehouse = warehouse.idWarehouse
                                    WHERE DATE(importDate) = ? AND product.idWarehouse = ? AND status = 'available'",
            [$day, $id],
            $conn
        );

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function read_export_by_date_and_warehouse($conn, $day, $id): array
    {
        $request = Request::Prepare(
            "SELECT product.idProduct,model.modelName,warehouse.location
                                    FROM product
                                    INNER JOIN model ON product.idModel = model.idModel
                                    INNER JOIN warehouse ON product.idWarehouse = warehouse.idWarehouse
                                    INNER JOIN buyedproducts on product.idProduct = buyedproducts.idProduct
                                    INNER JOIN buy on buyedproducts.idBuy = buy.idBuy
                                    WHERE DATE(buy.date) = ? AND product.idWarehouse = ? AND status = 'notavailable'",
            [$day, $id],
            $conn
        );

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
}
